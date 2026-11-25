import '../css/app.css'
import '../css/site-components.css'
import '../css/site-native.css'
import { visibleAnimation } from './lib/visibleAnimation'
import { afterPageLoad } from './lib/afterPageLoad'

// PHP owns the document. These enhancements never replace server-rendered content.
const all = <T extends Element = HTMLElement>(selector: string, root: ParentNode = document) => [
  ...root.querySelectorAll<T>(selector),
]
const component = (node: Element, name: string) =>
  node.closest<HTMLElement>(`[data-site="${name}"]`)
const reduced = window.matchMedia('(prefers-reduced-motion: reduce)')
let returnFocus: HTMLElement | null = null

function setDialog(dialog: HTMLElement | null, open: boolean) {
  if (!dialog) return
  dialog.hidden = !open
  if (open) {
    returnFocus = document.activeElement as HTMLElement
    document.body.style.overflow = 'hidden'
    dialog.querySelector<HTMLElement>('input:not([tabindex="-1"]),button,[tabindex="-1"]')?.focus()
  } else {
    document.body.style.overflow = ''
    returnFocus?.focus()
  }
}

function toggleControl(button: HTMLElement, target: HTMLElement | null) {
  if (!target) return
  target.hidden = !target.hidden
  button.setAttribute('aria-expanded', String(!target.hidden))
}

function flash(message: string, error = false) {
  const region = document.createElement('div')
  region.className =
    'fixed inset-x-4 top-32 z-menu mx-auto max-w-xl rounded-lg border border-brand bg-ink-1000 p-6 text-paper shadow-lg'
  region.setAttribute('role', error ? 'alert' : 'status')
  const text = document.createElement('p')
  text.textContent = message
  const close = document.createElement('button')
  close.type = 'button'
  close.className = 'mt-4 text-brand'
  close.textContent = 'بستن'
  close.onclick = () => region.remove()
  region.append(text, close)
  document.body.append(region)
}

for (const stamp of all<HTMLInputElement>('input[name="form_started_at"]'))
  stamp.value = String(Date.now())

// Dialogs keep keyboard focus within the active panel and restore it on close.
document.addEventListener('keydown', (event) => {
  const dialog = all<HTMLElement>(
    '[data-site="Layouts/MobileMenu"],[data-site="Components/LeadMagnetModal"]',
  ).find((el) => !el.hidden)
  if (!dialog) return
  if (event.key === 'Escape') {
    event.preventDefault()
    setDialog(dialog, false)
  }
  if (event.key === 'Tab') {
    const focusable = all<HTMLElement>(
      'a[href],button:not([disabled]),input:not([tabindex="-1"]),textarea,select',
      dialog,
    ).filter((el) => el.getClientRects().length)
    const first = focusable[0],
      last = focusable.at(-1)
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault()
      last?.focus()
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault()
      first?.focus()
    }
  }
})

document.addEventListener('click', async (event) => {
  const target = event.target as Element
  const control = target.closest<HTMLElement>('[data-on-click]')
  if (!control) return
  const action = control.getAttribute('data-on-click') ?? ''
  if (action.includes('openMenu'))
    setDialog(document.querySelector('[data-site="Layouts/MobileMenu"]'), true)
  else if (action === "emit('close')" || action === 'close')
    setDialog(
      control.closest('[data-site="Layouts/MobileMenu"],[data-site="Components/LeadMagnetModal"]'),
      false,
    )
  else if (action.includes("emit('navigate')"))
    setDialog(control.closest('[data-site="Layouts/MobileMenu"]'), false)
  else if (action === 'modalOpen = true') {
    event.preventDefault()
    const banner = component(control, 'Components/LeadMagnetBanner')
    const modal = banner?.nextElementSibling as HTMLElement | null
    setDialog(
      modal?.matches('[data-site="Components/LeadMagnetModal"]')
        ? modal
        : (banner?.parentElement?.querySelector('[data-site="Components/LeadMagnetModal"]') ??
            null),
      true,
    )
  } else if (action.includes('servicesOpen ='))
    toggleControl(control, document.getElementById('service-options'))
  else if (action === 'toggle')
    toggleControl(control, control.parentElement?.querySelector('#country-options') ?? null)
  else if (action === 'select(country)') {
    const country = JSON.parse(control.dataset.argCountry ?? '{}') as {
      iso2: string
      dialCode: string
      nameFa: string
    }
    const picker = component(control, 'Components/CountrySelect')
    const button = picker?.querySelector<HTMLButtonElement>(':scope > button')
    const flag = button?.querySelector<HTMLImageElement>('img')
    if (flag) flag.src = `/site/flags/${country.iso2.toLowerCase()}.svg`
    if (button?.querySelector('span')) button.querySelector('span')!.textContent = country.nameFa
    if (picker) picker.dataset.dialCode = country.dialCode
    const options = picker?.querySelector<HTMLElement>('#country-options')
    if (options) options.hidden = true
    button?.setAttribute('aria-expanded', 'false')
    for (const option of all<HTMLElement>('[role="option"]', picker ?? document)) {
      const selected = option === control
      option.setAttribute('aria-selected', String(selected))
      option.classList.toggle('bg-brand-200', selected)
    }
    button?.focus()
  } else if (action.startsWith('open[item.id]')) {
    const row = control.closest('li')
    const tree = row?.querySelector<HTMLElement>(':scope > [data-site="Layouts/MobileNavTree"]')
    toggleControl(control, tree ?? null)
  } else if (action === 'copyLink') {
    try {
      await navigator.clipboard.writeText(location.href)
      const label = document.createElement('span')
      label.textContent = 'لینک کپی شد'
      label.setAttribute('role', 'status')
      label.className =
        'absolute inline-end-full top-1/2 me-2 -translate-y-1/2 whitespace-nowrap rounded-sm bg-ink-1000 px-2 py-1 text-label-md text-paper'
      control.parentElement?.append(label)
      setTimeout(() => label.remove(), 2000)
    } catch {
      flash('برای کپی، آدرس صفحه را از نوار مرورگر انتخاب کنید.')
    }
  } else if (action === 'dismissed = true')
    control.closest('[data-site="Components/FlashMessages"]')?.remove()
  else if (action.includes("emit('select', option.value)")) {
    const option = JSON.parse(control.dataset.argOption ?? '{}') as { value: string | null }
    const gallery = control.closest('main')
    for (const image of all<HTMLElement>('[data-gallery-tag]', gallery ?? document))
      image.hidden = option.value !== null && image.dataset.galleryTag !== option.value
    const nav = component(control, 'Components/FilterChips')
    for (const button of all<HTMLButtonElement>('button[aria-pressed]', nav ?? document)) {
      const active = button === control
      button.setAttribute('aria-pressed', String(active))
      button.classList.toggle('text-brand', active)
      button.classList.toggle('text-paper', !active)
      button.classList.toggle('bg-ink-900', active)
      for (const cap of all<HTMLElement>('[aria-hidden]', button)) {
        cap.classList.toggle('bg-brand', active)
        cap.classList.toggle('bg-ink-300', !active)
      }
    }
  } else if (action.startsWith('go($event')) {
    const href = control.getAttribute('href')
    const anchor = href?.startsWith('#')
      ? document.getElementById(decodeURIComponent(href.slice(1)))
      : null
    if (anchor) {
      event.preventDefault()
      anchor.scrollIntoView({ behavior: reduced.matches ? 'auto' : 'smooth', block: 'start' })
      history.replaceState(null, '', href)
      anchor.setAttribute('tabindex', '-1')
      anchor.focus({ preventScroll: true })
    }
  }
})

document.addEventListener('input', (event) => {
  const input = event.target as HTMLInputElement
  if (input.dataset.model === 'query') {
    const q = input.value.toLowerCase().trim()
    for (const row of all<HTMLElement>(
      'li',
      component(input, 'Components/CountrySelect') ?? document,
    ))
      row.hidden =
        !row.textContent?.toLowerCase().includes(q) &&
        !row
          .querySelector('[data-arg-country]')
          ?.getAttribute('data-arg-country')
          ?.toLowerCase()
          .includes(q)
  }
})

document.addEventListener('change', (event) => {
  const input = event.target as HTMLInputElement
  if (input.name === 'service_ids[]') {
    const labels = all<HTMLInputElement>(
      'input[name="service_ids[]"]:checked',
      input.form ?? document,
    )
      .map((el) => el.parentElement?.textContent?.trim())
      .filter(Boolean)
    const trigger = document.querySelector<HTMLElement>('[aria-controls="service-options"] span')
    if (trigger) {
      trigger.textContent = labels.length ? labels.join('، ') : 'خدمات مورد نیاز شما'
      trigger.classList.toggle('text-ink-600', !labels.length)
      trigger.classList.toggle('text-ink-1000', !!labels.length)
    }
  }
  if (input.name === 'rating') {
    const section = component(input, 'Components/StarRating')
    if (!section) return
    const form = document.createElement('form')
    form.action = section.dataset.ratingAction ?? ''
    form.method = 'post'
    const rating = document.createElement('input')
    rating.name = 'rating'
    rating.value = input.value
    const token = document.createElement('input')
    token.name = '_token'
    token.value = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? ''
    form.append(rating, token)
    document.body.append(form)
    form.submit()
  }
})

// Forms retain normal POST actions as a fallback. Enhancement gives inline errors and download feedback.
for (const form of all<HTMLFormElement>('form[data-on-submit="submit"]')) {
  const modal = component(form, 'Components/LeadMagnetModal')
  if (modal) {
    for (const [name, value] of Object.entries({
      source: modal.dataset.source ?? 'home',
      post_id: modal.dataset.postId ?? '',
    })) {
      const input = document.createElement('input')
      input.type = 'hidden'
      input.name = name
      input.value = value
      form.append(input)
    }
  }
  form.addEventListener('submit', async (event) => {
    event.preventDefault()
    if (form.dataset.submitting) return
    form.dataset.submitting = 'true'
    for (const message of all('[data-validation-error]', form)) message.remove()
    const data = new FormData(form)
    if (component(form, 'Pages/Contact')) {
      const local = String(data.get('phone') ?? '').trim(),
        picker = form.querySelector<HTMLElement>('[data-site="Components/CountrySelect"]')
      data.set('phone', local ? `${picker?.dataset.dialCode ?? '+968'} ${local}` : '')
    }
    const submit = form.querySelector<HTMLButtonElement>('[type="submit"]')
    if (submit) submit.disabled = true
    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: data,
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
      })
      const result = (await response.json()) as {
        message?: string
        success?: string
        errors?: Record<string, string[]>
        download?: string | null
      }
      if (!response.ok) {
        if (result.errors) {
          for (const [name, messages] of Object.entries(result.errors)) {
            const field = all<HTMLInputElement | HTMLTextAreaElement>('input,textarea', form).find(
              (el) => el.name === name || el.name === name + '[]',
            )
            const p = document.createElement('p')
            p.dataset.validationError = 'true'
            p.className = 'mt-2 text-label-md text-brand'
            p.setAttribute('role', 'alert')
            p.textContent = messages[0]
            ;(field?.parentElement ?? form).append(p)
            field?.setAttribute('aria-invalid', 'true')
          }
          form.querySelector<HTMLElement>('[aria-invalid="true"]')?.focus()
        } else flash(result.message ?? 'مشکلی پیش آمد. لطفاً دوباره تلاش کنید.', true)
        return
      }
      form.reset()
      const stamp = form.querySelector<HTMLInputElement>('[name="form_started_at"]')
      if (stamp) stamp.value = String(Date.now())
      if (modal) {
        const panel = modal.querySelector<HTMLElement>('[role="dialog"]')
        const content = panel?.querySelector<HTMLElement>('[data-lead-content]')
        if (content) content.hidden = true
        const success = panel?.querySelector<HTMLElement>('[data-lead-success]')
        if (success) success.hidden = false
        if (result.download) {
          const link = success?.querySelector<HTMLAnchorElement>('[data-download]')
          if (link) {
            link.href = result.download
            link.hidden = false
          }
          const download = document.createElement('a')
          download.href = result.download
          download.download = ''
          download.click()
        }
      } else flash(result.success ?? result.message ?? 'پیام شما دریافت شد.')
    } catch {
      flash('ارتباط برقرار نشد. لطفاً دوباره تلاش کنید.', true)
    } finally {
      delete form.dataset.submitting
      if (submit) submit.disabled = false
    }
  })
}

// Client and team marquees run only while visible. Dragging and reduced motion remain available.
for (const track of all<HTMLElement>('[data-ref="clientTrack"],[data-ref="teamTrack"]')) {
  const first = track.querySelector<HTMLElement>('ul')
  if (!first) continue
  let position = 0,
    width = 0,
    last = 0,
    hovered = false,
    drag: number | null = null,
    x = 0
  const direction = document.documentElement.dir === 'rtl' ? 1 : -1
  const measure = () => {
    width = first.getBoundingClientRect().width
  }
  measure()
  new ResizeObserver(measure).observe(first)
  const render = () => {
    if (width > 0) position = ((position % width) + width) % width
    track.style.transform = `translate3d(${direction * position}px,0,0)`
  }
  const viewport = track.parentElement!
  viewport.style.touchAction = 'pan-y'
  viewport.addEventListener('pointerenter', (e) => {
    if (e.pointerType === 'mouse') hovered = true
  })
  viewport.addEventListener('pointerleave', () => (hovered = false))
  viewport.addEventListener('pointerdown', (e) => {
    if (e.pointerType === 'mouse' && e.button !== 0) return
    drag = e.pointerId
    x = e.clientX
    viewport.setPointerCapture(e.pointerId)
  })
  viewport.addEventListener('pointermove', (e) => {
    if (drag !== e.pointerId) return
    position += direction * (e.clientX - x)
    x = e.clientX
    render()
  })
  const end = () => {
    drag = null
  }
  viewport.addEventListener('pointerup', end)
  viewport.addEventListener('pointercancel', end)
  if (!reduced.matches)
    visibleAnimation(viewport, (time) => {
      const delta = last ? Math.min(time - last, 100) : 0
      last = time
      if (!hovered && drag === null && width > 0) {
        position += ((width / (track.dataset.ref === 'teamTrack' ? 40 : 32)) * delta) / 1000
        render()
      }
    })
}

for (const root of all<HTMLElement>('[data-site="Components/TestimonialCarousel"]')) {
  const viewport = root.querySelector<HTMLElement>('.testimonial-viewport'),
    track = root.querySelector<HTMLElement>('[data-ref="track"]'),
    first = track?.querySelector<HTMLElement>('ul')
  if (!viewport || !track || !first) continue
  const count = first.children.length
  if (!count) continue
  const dots = all<HTMLButtonElement>('[role="tab"]', root)
  let step = 0,
    perView = 1,
    offset = 0,
    active = 0,
    last = 0,
    dwell = 0,
    drag: number | null = null,
    x = 0,
    hovered = false
  const direction = document.documentElement.dir === 'rtl' ? 1 : -1
  const measure = () => {
    perView = matchMedia('(min-width:1024px)').matches
      ? 4
      : matchMedia('(min-width:640px)').matches
        ? 2
        : 1
    step = (viewport.clientWidth - 26 * (perView - 1)) / perView + 26
    track.style.setProperty('--testimonial-card', `${step - 26}px`)
    viewport.classList.toggle('is-continuous', perView === 4)
    offset = active * step
    render()
  }
  const render = () => {
    const width = count * step
    if (width > 0) offset = ((offset % width) + width) % width
    track.style.transform = `translate3d(${direction * offset}px,0,0)`
    active = Math.round(offset / step) % count
    dots.forEach((dot, i) => {
      dot.classList.toggle('is-active', i === active)
      dot.setAttribute('aria-selected', String(i === active))
      let fill = dot.querySelector('.testimonial-dot-fill')
      if (i === active && !fill) {
        fill = document.createElement('span')
        fill.className = 'testimonial-dot-fill'
        dot.append(fill)
      } else if (i !== active) fill?.remove()
    })
  }
  measure()
  new ResizeObserver(measure).observe(viewport)
  dots.forEach((dot, i) =>
    dot.addEventListener('click', () => {
      offset = i * step
      dwell = 0
      render()
    }),
  )
  viewport.addEventListener('pointerenter', (e) => {
    if (e.pointerType === 'mouse') hovered = true
  })
  viewport.addEventListener('pointerleave', () => (hovered = false))
  viewport.addEventListener('pointerdown', (e) => {
    if (e.pointerType === 'mouse' && e.button !== 0) return
    drag = e.pointerId
    x = e.clientX
    viewport.setPointerCapture(e.pointerId)
  })
  viewport.addEventListener('pointermove', (e) => {
    if (drag !== e.pointerId) return
    offset += direction * (e.clientX - x)
    x = e.clientX
    render()
  })
  const end = () => {
    drag = null
    if (perView !== 4) {
      offset = Math.round(offset / step) * step
      dwell = 0
      render()
    }
  }
  viewport.addEventListener('pointerup', end)
  viewport.addEventListener('pointercancel', end)
  if (count > 1 && !reduced.matches)
    visibleAnimation(viewport, (time) => {
      const delta = last ? Math.min(time - last, 100) : 0
      last = time
      if (drag !== null || hovered) return
      if (perView === 4) {
        offset += ((step / 5) * delta) / 1000
        render()
      } else {
        dwell += delta
        if (dwell >= 4500) {
          offset = (active + 1) * step
          dwell = 0
          render()
        }
      }
    })
}

for (const root of all<HTMLElement>('[data-site="Components/ProjectShowcase"]')) {
  const projects = JSON.parse(root.dataset.projects ?? '[]') as {
    title: string
    url: string
    industry?: string
    excerpt?: string
  }[]
  let active = 0,
    touchX = 0
  const swipe = root.querySelector<HTMLElement>('[data-on-touchstart="startSwipe"]')
  const slides = swipe ? all<HTMLImageElement>('img[data-slide-index]', swipe) : []
  const select = (index: number) => {
    if (!projects.length) return
    active = (index + projects.length) % projects.length
    for (const el of all<HTMLElement>('[data-active-on]', root)) {
      const ownIndex = Number(
        el.dataset.argIndex ??
          el.dataset.slideIndex ??
          el.closest<HTMLElement>('[data-index]')?.dataset.index ??
          -1,
      )
      const on = (el.dataset.activeOn ?? '').split(/\s+/).filter(Boolean),
        off = (el.dataset.activeOff ?? '').split(/\s+/).filter(Boolean)
      for (const cls of [...on, ...off]) el.classList.remove(cls)
      for (const cls of ownIndex === active ? on : off) el.classList.add(cls)
      if (el.matches('button[data-arg-index]')) {
        if (ownIndex === active) el.setAttribute('aria-current', 'true')
        else el.removeAttribute('aria-current')
      }
    }
    for (const slide of slides) {
      const index = Number(slide.dataset.slideIndex)
      slide.style.transform = `translateX(${(index - active) * 100}%)`
    }
    for (const el of all<HTMLElement>('[data-show="active === index"]', root))
      el.hidden = Number(el.closest<HTMLElement>('[data-index]')?.dataset.index) !== active
    const project = projects[active]
    const mobile = root.querySelector<HTMLElement>('[data-project-mobile]')
    if (mobile) {
      for (const a of all<HTMLAnchorElement>('a', mobile)) a.href = project.url
      const title = mobile.querySelector<HTMLElement>('[data-project-title]')
      if (title) title.textContent = project.title
      const industry = mobile.querySelector<HTMLElement>('[data-project-industry]')
      if (industry) industry.textContent = project.industry ?? ''
      const excerpt = mobile.querySelector<HTMLElement>('[data-project-excerpt]')
      if (excerpt) excerpt.textContent = project.excerpt ?? ''
    }
  }
  for (const el of all<HTMLElement>(
    '[data-on-click="active = index"],[data-on-mouseenter="active = index"],[data-on-focusin="active = index"]',
    root,
  )) {
    const activate = () =>
      select(
        Number(el.dataset.argIndex ?? el.closest<HTMLElement>('[data-index]')?.dataset.index ?? 0),
      )
    el.addEventListener('click', activate)
    el.addEventListener('mouseenter', activate)
    el.addEventListener('focusin', activate)
  }
  swipe?.addEventListener('touchstart', (e) => (touchX = e.changedTouches[0].clientX), {
    passive: true,
  })
  swipe?.addEventListener(
    'touchend',
    (e) => {
      const distance = e.changedTouches[0].clientX - touchX
      if (Math.abs(distance) > 40) select(active + (distance > 0 ? -1 : 1))
    },
    { passive: true },
  )
  if (!reduced.matches) {
    let visible = false
    new IntersectionObserver(([entry]) => (visible = entry.isIntersecting)).observe(root)
    setInterval(() => {
      if (visible && !document.hidden) select(active + 1)
    }, 5000)
  }
}

for (const image of all<HTMLImageElement>('[data-site="Components/ClientLogo"] img'))
  image.addEventListener(
    'error',
    () => {
      const label = document.createElement('span')
      label.className = 'text-title-sm text-ink-400'
      label.textContent = image.alt
      image.replaceWith(label)
    },
    { once: true },
  )
if (!reduced.matches && !matchMedia('(pointer:coarse)').matches)
  afterPageLoad(() => {
    void import('./lib/motion').then((m) => m.initMotion())
  })

for (const scroller of all<HTMLElement>('[data-ref="scroller"],[data-ref="metaRow"]')) {
  let drag = false,
    moved = false,
    x = 0,
    scroll = 0
  scroller.addEventListener('pointerdown', (event) => {
    if (event.pointerType !== 'mouse' || event.button !== 0) return
    drag = true
    moved = false
    x = event.clientX
    scroll = scroller.scrollLeft
  })
  scroller.addEventListener('pointermove', (event) => {
    if (!drag) return
    const distance = event.clientX - x
    if (Math.abs(distance) > 5) {
      moved = true
      scroller.setPointerCapture(event.pointerId)
      scroller.scrollLeft = scroll - distance
      event.preventDefault()
    }
  })
  scroller.addEventListener('pointerup', () => (drag = false))
  scroller.addEventListener('pointercancel', () => (drag = false))
  scroller.addEventListener(
    'click',
    (event) => {
      if (moved) {
        event.preventDefault()
        event.stopPropagation()
        moved = false
      }
    },
    true,
  )
  scroller.addEventListener('dragstart', (event) => event.preventDefault())
}
for (const root of all<HTMLElement>('[data-site="Components/StarRating"]')) {
  const labels = all<HTMLLabelElement>('label', root)
  const render = (value: number) =>
    labels.forEach((label) => {
      const on = Number(label.querySelector<HTMLInputElement>('input')?.value ?? 0) <= value
      label.classList.toggle('text-brand', on)
      label.classList.toggle('text-ink-700', !on)
    })
  labels.forEach((label) =>
    label.addEventListener('mouseenter', () =>
      render(Number(label.querySelector<HTMLInputElement>('input')?.value ?? 0)),
    ),
  )
  root.addEventListener('mouseleave', () =>
    render(Number(root.querySelector<HTMLInputElement>('input:checked')?.value ?? 0)),
  )
}
