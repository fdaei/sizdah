import {spawn} from 'node:child_process'
import {setTimeout as delay} from 'node:timers/promises'
import path from 'node:path'
import {readFileSync,writeFileSync,mkdirSync,existsSync} from 'node:fs'
import {chromium} from '@playwright/test'
import assert from 'node:assert/strict'
const port=8018,base=`http://127.0.0.1:${port}`
const server=spawn('php',['-S',`127.0.0.1:${port}`,path.resolve('vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php')],{cwd:path.resolve('public'),stdio:'ignore'})
let browser
try{
 for(let i=0;i<80;i++){try{await fetch(base+'/up');break}catch{await delay(100)}}
 browser=await chromium.launch({executablePath:process.env.CHROMIUM_PATH??(existsSync('/usr/bin/chromium')?'/usr/bin/chromium':undefined),headless:true})
 const page=await browser.newPage({viewport:{width:402,height:874},reducedMotion:'reduce'})
 const errors=[];page.on('pageerror',e=>errors.push(e.message))
 await page.goto(base+'/fa?verify-native=1',{waitUntil:'networkidle'})
 await page.locator('button[data-on-click*="openMenu"]').click()
 await page.locator('[data-site="Layouts/MobileMenu"] [role="dialog"]').waitFor({state:'visible'})
 await page.keyboard.press('Escape');assert.equal(await page.locator('[data-site="Layouts/MobileMenu"]').isVisible(),false)
 await page.locator('[data-on-click="modalOpen = true"]').first().click()
 const modal=page.locator('[data-site="Components/LeadMagnetModal"]').filter({visible:true})
 await modal.locator('form').waitFor({state:'visible'})
 await modal.locator('input[name="email"]').fill('reader@example.com')
 await page.route('**/fa/newsletter',route=>route.fulfill({contentType:'application/json',body:JSON.stringify({success:'موفق',download:null})}))
 await modal.locator('[type="submit"]').click();await modal.locator('[data-lead-success]').waitFor({state:'visible'});await page.keyboard.press('Escape')
 await page.goto(base+'/fa/contact?verify-native=1',{waitUntil:'networkidle'})
 await page.locator('[aria-controls="service-options"]').click();assert.equal(await page.locator('#service-options').isVisible(),true)
 const checkbox=page.locator('input[name="service_ids[]"]').first();if(await checkbox.count())await checkbox.check()
 await page.locator('[aria-controls="country-options"]').click();await page.locator('[data-model="query"]').fill('Germany');await page.locator('[role="option"]').filter({hasText:'آلمان'}).click()
 assert.equal(await page.locator('[data-site="Components/CountrySelect"]').getAttribute('data-dial-code'),'+49')
 await page.locator('input[name="name"]').fill('آزمایش');await page.locator('input[name="phone"]').fill('123456789')
 await page.route('**/fa/contact',route=>route.fulfill({status:422,contentType:'application/json',body:JSON.stringify({errors:{phone:['شماره آزمایشی معتبر نیست']}})}))
 await page.locator('form [type="submit"]').click();await page.getByRole('alert').filter({hasText:'شماره آزمایشی معتبر نیست'}).waitFor()
 assert.deepEqual(errors,[])
 console.log('PASS: menu, keyboard close, lead modal + submission, service selector, countries, inline validation; no JS errors.')
 await page.unrouteAll()
 const routeFile='test-results/blade-baseline/routes.json'
 const paths=existsSync(routeFile)?JSON.parse(readFileSync(routeFile)):['/fa','/fa/about','/fa/services','/fa/work','/fa/insights','/fa/contact','/fa/privacy-policy','/fa/terms','/fa/native-missing'];const results=[];mkdirSync('test-results/blade-verified',{recursive:true})
 for(const width of[402,1440]){
  await page.setViewportSize({width,height:width===402?874:900})
  for(let i=0;i<paths.length;i++){
   const response=await page.goto(base+paths[i]+'?verified-native=1',{waitUntil:'networkidle'})
   for(let y=0;y<await page.evaluate(()=>document.documentElement.scrollHeight);y+=800){await page.evaluate(y=>scrollTo(0,y),y);await page.waitForTimeout(70)}
   await page.evaluate(()=>scrollTo(0,0));await page.waitForTimeout(200)
   await page.screenshot({path:`test-results/blade-verified/${i}-${width}.png`,fullPage:true})
   const data=await page.evaluate(()=>({height:document.documentElement.scrollHeight,overflow:document.documentElement.scrollWidth>innerWidth,h1:document.querySelectorAll('main h1').length,scripts:performance.getEntriesByType('resource').filter(e=>e.initiatorType==='script').map(e=>e.name)}))
   results.push({index:i,width,status:response.status(),...data});console.log(width,i,response.status(),data.height)
  }
 }
 writeFileSync('test-results/blade-verified/results.json',JSON.stringify(results,null,2))
 // The complete design is present even when JavaScript is disabled.
 const nojs=await browser.newPage({javaScriptEnabled:false,viewport:{width:402,height:874}})
 await nojs.goto(base+'/fa?native-nojs=1');assert.equal(await nojs.locator('.home-hero h1 span').count(),3);assert.ok(await nojs.locator('.home-hero h1').isVisible());assert.equal(await nojs.locator('[data-page]').count(),0)
 console.log('PASS: complete native hero visible without JavaScript.')
}finally{await browser?.close();server.kill('SIGTERM')}
