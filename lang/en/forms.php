<?php

declare(strict_types=1);

return [

    'contact' => [
        'name'             => 'Name',
        'name_placeholder' => 'Your Name',
        'brand'            => 'Brand Name',
        'brand_placeholder' => 'Your brand name',
        'phone'            => 'Phone Number',
        'phone_placeholder'      => '123 456 78',
        'country_search'   => 'Search country...',
        'country_not_found' => 'No country found',
        'services'         => 'Services',
        'services_placeholder' => 'Select one or more services',
        'message'          => 'Short Message',
        'message_placeholder' => 'Enter your message...',
        'submit'           => 'Start a Conversation',
        'success'          => "Thanks — we've received your message and will be in touch shortly.",
        'error'            => 'Something went wrong. Please try again.',
    ],

    // Contact details panel — Figma 466:1216.
    'details' => [
        'title'        => 'Contact Details',
        'whatsapp'     => 'WhatsApp',
        'location'     => 'Location',
        'email'        => 'Email',
        'working_with' => 'Working With',
        'follow'       => 'را برای ایده‌های خلاقانه و نگاه‌های تازه به برند در شبکه‌های اجتماعی دنبال کنید:',
    ],

    'newsletter' => [
        'title'             => 'Get the content-direction checklist',
        'description'       => 'A short checklist to see whether your content follows a clear, defined path — or not.',
        'name'              => 'Full name',
        'name_placeholder'  => 'Your name',
        'email'             => 'Email',
        'email_placeholder' => 'you@example.com',
        'submit'            => 'Get checklist',
        'disclaimer'        => 'No spam — just practical notes on brand and content.',
        'success'           => "You're subscribed. Check your inbox for the checklist.",
        'already'           => "You're already on the list.",
        'dialog_label'      => 'Get the free checklist',
    ],

    // Lead-magnet file email — the file is chosen per article in the admin panel.
    'lead_magnet_mail' => [
        'subject'            => 'Your requested file',
        'subject_with_title' => 'Your requested file — :title',
        'greeting'           => 'Hi,',
        'greeting_named'     => 'Hi :name,',
        'body'               => 'Thanks for your request. The file you asked for is ready.',
        'body_with_title'    => 'Thanks for your request on “:title”. The file you asked for is ready.',
        'attached'           => 'You will find it attached to this email.',
        'read_article'       => 'Read the article',
        'signoff'            => 'Best regards, the :app team',
    ],

];
