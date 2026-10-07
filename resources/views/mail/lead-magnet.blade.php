@php($rtl = in_array(app()->getLocale(), ['fa', 'ar'], true))
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('forms.lead_magnet_mail.subject') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Tahoma,Arial,sans-serif;color:#141414;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:12px;padding:32px;text-align:{{ $rtl ? 'right' : 'left' }};">
                    <tr>
                        <td style="font-size:16px;line-height:1.9;">
                            <p style="margin:0 0 16px;font-size:18px;font-weight:bold;">
                                {{ $name ? __('forms.lead_magnet_mail.greeting_named', ['name' => $name]) : __('forms.lead_magnet_mail.greeting') }}
                            </p>

                            <p style="margin:0 0 16px;">
                                @if ($postTitle !== '')
                                    {{ __('forms.lead_magnet_mail.body_with_title', ['title' => $postTitle]) }}
                                @else
                                    {{ __('forms.lead_magnet_mail.body') }}
                                @endif
                            </p>

                            @if ($hasAttachment)
                                <p style="margin:0 0 16px;">{{ __('forms.lead_magnet_mail.attached') }}</p>
                            @endif

                            @if ($postUrl)
                                <p style="margin:24px 0;">
                                    <a href="{{ $postUrl }}" style="display:inline-block;background:#f8b937;color:#141414;text-decoration:none;font-weight:bold;padding:12px 24px;border-radius:999px;">
                                        {{ __('forms.lead_magnet_mail.read_article') }}
                                    </a>
                                </p>
                            @endif

                            <p style="margin:24px 0 0;color:#666666;">{{ __('forms.lead_magnet_mail.signoff', ['app' => config('app.name')]) }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
