<?php

declare(strict_types=1);

/*
 | Ziggy ships route definitions to the browser in every page's props.
 | Only public site routes go out: never the Filament/Livewire admin routes
 | (they leaked the panel's structure and added ~50 routes of payload to
 | every page). Names used in resources/js today: home, work.index, services,
 | insights.index, contact, contact.store, newsletter.store, legal.*.
 | The rest of the public set is included so new links keep working.
 */
return [
    'only' => [
        'home',
        'work.*',
        'services',
        'services.*',
        'about',
        'insights.*',
        'contact',
        'contact.*',
        'newsletter.*',
        'legal.*',
    ],
];
