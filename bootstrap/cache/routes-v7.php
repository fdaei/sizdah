<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/admin/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/password-reset/request' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.password-reset.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/password-reset/reset' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.password-reset.reset',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.pages.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/manage-settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.pages.manage-settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/clients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.clients.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/clients/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.clients.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/contact-submissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.contact-submissions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/faqs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.faqs.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/faqs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.faqs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/industries' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.industries.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/industries/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.industries.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/menus' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.menus.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/menus/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.menus.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/newsletter-subscriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.newsletter-subscriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/pages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.pages.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/pages/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.pages.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/post-categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.post-categories.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/post-categories/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.post-categories.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/posts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.posts.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/posts/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.posts.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/post-tags' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.post-tags.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/post-tags/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.post-tags.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/projects' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.projects.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/projects/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.projects.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/redirects' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.redirects.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/redirects/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.redirects.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/services' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.services.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/services/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.services.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/social-links' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.social-links.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/social-links/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.social-links.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/team-members' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.team-members.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/team-members/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.team-members.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/testimonials' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.testimonials.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/testimonials/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.testimonials.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.users.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'default.livewire.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/livewire.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7soz0Imu3FHnDwLP',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/livewire.min.js.map' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RNwUmRCNUO7pgk4i',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/upload-file' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.upload-file',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zadjjSlhsmGQIOWB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sitemap.xml' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sitemap',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VYP4LvJ9urUyE9OR',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/filament/(?|exports/([^/]++)/download(*:45)|imports/([^/]++)/failed\\-rows/download(*:90))|/admin/(?|c(?|lients/([^/]++)/edit(*:132)|ontact\\-submissions/([^/]++)(?|(*:171)|/edit(*:184)))|faqs/([^/]++)/edit(*:212)|industries/([^/]++)/edit(*:244)|menus/([^/]++)/edit(*:271)|p(?|ages/([^/]++)/edit(*:301)|ost(?|\\-(?|categories/([^/]++)/edit(*:344)|tags/([^/]++)/edit(*:370))|s/([^/]++)/edit(*:394))|rojects/([^/]++)/edit(*:424))|redirects/([^/]++)/edit(*:456)|s(?|ervices/([^/]++)/edit(*:489)|ocial\\-links/([^/]++)/edit(*:523))|te(?|am\\-members/([^/]++)/edit(*:562)|stimonials/([^/]++)/edit(*:594))|users/([^/]++)/edit(*:622)|language/(en|fa)(*:646))|/livewire/preview\\-file/([^/]++)(*:687)|/sitemap\\-(fa)\\.xml(*:714)|/(fa)(?|(*:730)|/(?|work(?|(*:749)|/([^/]++)(*:766))|services(*:783)|about(*:796)|insights(?|(*:815)|/([^/]++)(*:832))|contact(?|(*:851))|newsletter(*:870)|privacy\\-policy(*:893)|terms(*:906)))|/(.*)(*:921)|/storage/(.*)(?|(*:945)))/?$}sDu',
    ),
    3 => 
    array (
      45 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.exports.download',
          ),
          1 => 
          array (
            0 => 'export',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      90 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.imports.failed-rows.download',
          ),
          1 => 
          array (
            0 => 'import',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      132 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.clients.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      171 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.contact-submissions.view',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      184 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.contact-submissions.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      212 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.faqs.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      244 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.industries.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      271 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.menus.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      301 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.pages.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      344 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.post-categories.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      370 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.post-tags.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      394 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.posts.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      424 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.projects.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.redirects.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      489 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.services.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      523 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.social-links.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      562 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.team-members.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      594 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.testimonials.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      622 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.users.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      646 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.locale',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      687 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.preview-file',
          ),
          1 => 
          array (
            0 => 'filename',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      714 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sitemap.locale',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      730 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      749 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'work.index',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      766 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'work.show',
          ),
          1 => 
          array (
            0 => 'locale',
            1 => 'project',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      783 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'services',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      796 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'about',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      815 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'insights.index',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      832 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'insights.show',
          ),
          1 => 
          array (
            0 => 'locale',
            1 => 'post',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      851 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'contact',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'contact.store',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      870 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'newsletter.store',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      893 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.privacy',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      906 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.terms',
          ),
          1 => 
          array (
            0 => 'locale',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      921 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::C1H6LZPtvtJrsOf4',
          ),
          1 => 
          array (
            0 => 'any',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      945 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local.upload',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'filament.exports.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'filament/exports/{export}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'filament.actions',
        ),
        'uses' => 'Filament\\Actions\\Exports\\Http\\Controllers\\DownloadExport@__invoke',
        'controller' => 'Filament\\Actions\\Exports\\Http\\Controllers\\DownloadExport',
        'as' => 'filament.exports.download',
        'namespace' => NULL,
        'prefix' => 'filament',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.imports.failed-rows.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'filament/imports/{import}/failed-rows/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'filament.actions',
        ),
        'uses' => 'Filament\\Actions\\Imports\\Http\\Controllers\\DownloadImportFailureCsv@__invoke',
        'controller' => 'Filament\\Actions\\Imports\\Http\\Controllers\\DownloadImportFailureCsv',
        'as' => 'filament.imports.failed-rows.download',
        'namespace' => NULL,
        'prefix' => 'filament',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/login',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
        ),
        'uses' => 'Filament\\Pages\\Auth\\Login@__invoke',
        'controller' => 'Filament\\Pages\\Auth\\Login',
        'as' => 'filament.admin.auth.login',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.password-reset.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/password-reset/request',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
        ),
        'uses' => 'Filament\\Pages\\Auth\\PasswordReset\\RequestPasswordReset@__invoke',
        'controller' => 'Filament\\Pages\\Auth\\PasswordReset\\RequestPasswordReset',
        'as' => 'filament.admin.auth.password-reset.request',
        'namespace' => NULL,
        'prefix' => 'admin/password-reset',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.password-reset.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/password-reset/reset',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'signed',
        ),
        'uses' => 'Filament\\Pages\\Auth\\PasswordReset\\ResetPassword@__invoke',
        'controller' => 'Filament\\Pages\\Auth\\PasswordReset\\ResetPassword',
        'as' => 'filament.admin.auth.password-reset.reset',
        'namespace' => NULL,
        'prefix' => 'admin/password-reset',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/logout',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'uses' => 'Filament\\Http\\Controllers\\Auth\\LogoutController@__invoke',
        'controller' => 'Filament\\Http\\Controllers\\Auth\\LogoutController',
        'as' => 'filament.admin.auth.logout',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/profile',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'uses' => 'Filament\\Pages\\Auth\\EditProfile@__invoke',
        'controller' => 'Filament\\Pages\\Auth\\EditProfile',
        'as' => 'filament.admin.auth.profile',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.pages.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'uses' => 'Filament\\Pages\\Dashboard@__invoke',
        'controller' => 'Filament\\Pages\\Dashboard',
        'as' => 'filament.admin.pages.dashboard',
        'namespace' => NULL,
        'prefix' => 'admin/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.pages.manage-settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/manage-settings',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'uses' => 'App\\Filament\\Pages\\ManageSettings@__invoke',
        'controller' => 'App\\Filament\\Pages\\ManageSettings',
        'as' => 'filament.admin.pages.manage-settings',
        'namespace' => NULL,
        'prefix' => 'admin/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.clients.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/clients',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ClientResource\\Pages\\ListClients@__invoke',
        'controller' => 'App\\Filament\\Resources\\ClientResource\\Pages\\ListClients',
        'as' => 'filament.admin.resources.clients.index',
        'namespace' => NULL,
        'prefix' => 'admin/clients',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.clients.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/clients/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ClientResource\\Pages\\CreateClient@__invoke',
        'controller' => 'App\\Filament\\Resources\\ClientResource\\Pages\\CreateClient',
        'as' => 'filament.admin.resources.clients.create',
        'namespace' => NULL,
        'prefix' => 'admin/clients',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.clients.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/clients/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ClientResource\\Pages\\EditClient@__invoke',
        'controller' => 'App\\Filament\\Resources\\ClientResource\\Pages\\EditClient',
        'as' => 'filament.admin.resources.clients.edit',
        'namespace' => NULL,
        'prefix' => 'admin/clients',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.contact-submissions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/contact-submissions',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ContactSubmissionResource\\Pages\\ListContactSubmissions@__invoke',
        'controller' => 'App\\Filament\\Resources\\ContactSubmissionResource\\Pages\\ListContactSubmissions',
        'as' => 'filament.admin.resources.contact-submissions.index',
        'namespace' => NULL,
        'prefix' => 'admin/contact-submissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.contact-submissions.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/contact-submissions/{record}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ContactSubmissionResource\\Pages\\ViewContactSubmission@__invoke',
        'controller' => 'App\\Filament\\Resources\\ContactSubmissionResource\\Pages\\ViewContactSubmission',
        'as' => 'filament.admin.resources.contact-submissions.view',
        'namespace' => NULL,
        'prefix' => 'admin/contact-submissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.contact-submissions.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/contact-submissions/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ContactSubmissionResource\\Pages\\EditContactSubmission@__invoke',
        'controller' => 'App\\Filament\\Resources\\ContactSubmissionResource\\Pages\\EditContactSubmission',
        'as' => 'filament.admin.resources.contact-submissions.edit',
        'namespace' => NULL,
        'prefix' => 'admin/contact-submissions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.faqs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/faqs',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\FaqResource\\Pages\\ListFaqs@__invoke',
        'controller' => 'App\\Filament\\Resources\\FaqResource\\Pages\\ListFaqs',
        'as' => 'filament.admin.resources.faqs.index',
        'namespace' => NULL,
        'prefix' => 'admin/faqs',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.faqs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/faqs/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\FaqResource\\Pages\\CreateFaq@__invoke',
        'controller' => 'App\\Filament\\Resources\\FaqResource\\Pages\\CreateFaq',
        'as' => 'filament.admin.resources.faqs.create',
        'namespace' => NULL,
        'prefix' => 'admin/faqs',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.faqs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/faqs/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\FaqResource\\Pages\\EditFaq@__invoke',
        'controller' => 'App\\Filament\\Resources\\FaqResource\\Pages\\EditFaq',
        'as' => 'filament.admin.resources.faqs.edit',
        'namespace' => NULL,
        'prefix' => 'admin/faqs',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.industries.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/industries',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\IndustryResource\\Pages\\ListIndustries@__invoke',
        'controller' => 'App\\Filament\\Resources\\IndustryResource\\Pages\\ListIndustries',
        'as' => 'filament.admin.resources.industries.index',
        'namespace' => NULL,
        'prefix' => 'admin/industries',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.industries.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/industries/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\IndustryResource\\Pages\\CreateIndustry@__invoke',
        'controller' => 'App\\Filament\\Resources\\IndustryResource\\Pages\\CreateIndustry',
        'as' => 'filament.admin.resources.industries.create',
        'namespace' => NULL,
        'prefix' => 'admin/industries',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.industries.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/industries/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\IndustryResource\\Pages\\EditIndustry@__invoke',
        'controller' => 'App\\Filament\\Resources\\IndustryResource\\Pages\\EditIndustry',
        'as' => 'filament.admin.resources.industries.edit',
        'namespace' => NULL,
        'prefix' => 'admin/industries',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.menus.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/menus',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\MenuResource\\Pages\\ListMenus@__invoke',
        'controller' => 'App\\Filament\\Resources\\MenuResource\\Pages\\ListMenus',
        'as' => 'filament.admin.resources.menus.index',
        'namespace' => NULL,
        'prefix' => 'admin/menus',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.menus.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/menus/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\MenuResource\\Pages\\CreateMenu@__invoke',
        'controller' => 'App\\Filament\\Resources\\MenuResource\\Pages\\CreateMenu',
        'as' => 'filament.admin.resources.menus.create',
        'namespace' => NULL,
        'prefix' => 'admin/menus',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.menus.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/menus/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\MenuResource\\Pages\\EditMenu@__invoke',
        'controller' => 'App\\Filament\\Resources\\MenuResource\\Pages\\EditMenu',
        'as' => 'filament.admin.resources.menus.edit',
        'namespace' => NULL,
        'prefix' => 'admin/menus',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.newsletter-subscriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/newsletter-subscriptions',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\NewsletterSubscriptionResource\\Pages\\ListNewsletterSubscriptions@__invoke',
        'controller' => 'App\\Filament\\Resources\\NewsletterSubscriptionResource\\Pages\\ListNewsletterSubscriptions',
        'as' => 'filament.admin.resources.newsletter-subscriptions.index',
        'namespace' => NULL,
        'prefix' => 'admin/newsletter-subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.pages.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pages',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PageResource\\Pages\\ListPages@__invoke',
        'controller' => 'App\\Filament\\Resources\\PageResource\\Pages\\ListPages',
        'as' => 'filament.admin.resources.pages.index',
        'namespace' => NULL,
        'prefix' => 'admin/pages',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.pages.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pages/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PageResource\\Pages\\CreatePage@__invoke',
        'controller' => 'App\\Filament\\Resources\\PageResource\\Pages\\CreatePage',
        'as' => 'filament.admin.resources.pages.create',
        'namespace' => NULL,
        'prefix' => 'admin/pages',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.pages.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pages/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PageResource\\Pages\\EditPage@__invoke',
        'controller' => 'App\\Filament\\Resources\\PageResource\\Pages\\EditPage',
        'as' => 'filament.admin.resources.pages.edit',
        'namespace' => NULL,
        'prefix' => 'admin/pages',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.post-categories.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/post-categories',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostCategoryResource\\Pages\\ListPostCategories@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostCategoryResource\\Pages\\ListPostCategories',
        'as' => 'filament.admin.resources.post-categories.index',
        'namespace' => NULL,
        'prefix' => 'admin/post-categories',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.post-categories.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/post-categories/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostCategoryResource\\Pages\\CreatePostCategory@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostCategoryResource\\Pages\\CreatePostCategory',
        'as' => 'filament.admin.resources.post-categories.create',
        'namespace' => NULL,
        'prefix' => 'admin/post-categories',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.post-categories.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/post-categories/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostCategoryResource\\Pages\\EditPostCategory@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostCategoryResource\\Pages\\EditPostCategory',
        'as' => 'filament.admin.resources.post-categories.edit',
        'namespace' => NULL,
        'prefix' => 'admin/post-categories',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.posts.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/posts',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostResource\\Pages\\ListPosts@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostResource\\Pages\\ListPosts',
        'as' => 'filament.admin.resources.posts.index',
        'namespace' => NULL,
        'prefix' => 'admin/posts',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.posts.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/posts/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostResource\\Pages\\CreatePost@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostResource\\Pages\\CreatePost',
        'as' => 'filament.admin.resources.posts.create',
        'namespace' => NULL,
        'prefix' => 'admin/posts',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.posts.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/posts/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostResource\\Pages\\EditPost@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostResource\\Pages\\EditPost',
        'as' => 'filament.admin.resources.posts.edit',
        'namespace' => NULL,
        'prefix' => 'admin/posts',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.post-tags.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/post-tags',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostTagResource\\Pages\\ListPostTags@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostTagResource\\Pages\\ListPostTags',
        'as' => 'filament.admin.resources.post-tags.index',
        'namespace' => NULL,
        'prefix' => 'admin/post-tags',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.post-tags.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/post-tags/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostTagResource\\Pages\\CreatePostTag@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostTagResource\\Pages\\CreatePostTag',
        'as' => 'filament.admin.resources.post-tags.create',
        'namespace' => NULL,
        'prefix' => 'admin/post-tags',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.post-tags.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/post-tags/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\PostTagResource\\Pages\\EditPostTag@__invoke',
        'controller' => 'App\\Filament\\Resources\\PostTagResource\\Pages\\EditPostTag',
        'as' => 'filament.admin.resources.post-tags.edit',
        'namespace' => NULL,
        'prefix' => 'admin/post-tags',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.projects.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/projects',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ProjectResource\\Pages\\ListProjects@__invoke',
        'controller' => 'App\\Filament\\Resources\\ProjectResource\\Pages\\ListProjects',
        'as' => 'filament.admin.resources.projects.index',
        'namespace' => NULL,
        'prefix' => 'admin/projects',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.projects.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/projects/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ProjectResource\\Pages\\CreateProject@__invoke',
        'controller' => 'App\\Filament\\Resources\\ProjectResource\\Pages\\CreateProject',
        'as' => 'filament.admin.resources.projects.create',
        'namespace' => NULL,
        'prefix' => 'admin/projects',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.projects.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/projects/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ProjectResource\\Pages\\EditProject@__invoke',
        'controller' => 'App\\Filament\\Resources\\ProjectResource\\Pages\\EditProject',
        'as' => 'filament.admin.resources.projects.edit',
        'namespace' => NULL,
        'prefix' => 'admin/projects',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.redirects.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/redirects',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\RedirectResource\\Pages\\ListRedirects@__invoke',
        'controller' => 'App\\Filament\\Resources\\RedirectResource\\Pages\\ListRedirects',
        'as' => 'filament.admin.resources.redirects.index',
        'namespace' => NULL,
        'prefix' => 'admin/redirects',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.redirects.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/redirects/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\RedirectResource\\Pages\\CreateRedirect@__invoke',
        'controller' => 'App\\Filament\\Resources\\RedirectResource\\Pages\\CreateRedirect',
        'as' => 'filament.admin.resources.redirects.create',
        'namespace' => NULL,
        'prefix' => 'admin/redirects',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.redirects.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/redirects/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\RedirectResource\\Pages\\EditRedirect@__invoke',
        'controller' => 'App\\Filament\\Resources\\RedirectResource\\Pages\\EditRedirect',
        'as' => 'filament.admin.resources.redirects.edit',
        'namespace' => NULL,
        'prefix' => 'admin/redirects',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.services.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/services',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ServiceResource\\Pages\\ListServices@__invoke',
        'controller' => 'App\\Filament\\Resources\\ServiceResource\\Pages\\ListServices',
        'as' => 'filament.admin.resources.services.index',
        'namespace' => NULL,
        'prefix' => 'admin/services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.services.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/services/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ServiceResource\\Pages\\CreateService@__invoke',
        'controller' => 'App\\Filament\\Resources\\ServiceResource\\Pages\\CreateService',
        'as' => 'filament.admin.resources.services.create',
        'namespace' => NULL,
        'prefix' => 'admin/services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.services.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/services/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\ServiceResource\\Pages\\EditService@__invoke',
        'controller' => 'App\\Filament\\Resources\\ServiceResource\\Pages\\EditService',
        'as' => 'filament.admin.resources.services.edit',
        'namespace' => NULL,
        'prefix' => 'admin/services',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.social-links.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/social-links',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\SocialLinkResource\\Pages\\ListSocialLinks@__invoke',
        'controller' => 'App\\Filament\\Resources\\SocialLinkResource\\Pages\\ListSocialLinks',
        'as' => 'filament.admin.resources.social-links.index',
        'namespace' => NULL,
        'prefix' => 'admin/social-links',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.social-links.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/social-links/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\SocialLinkResource\\Pages\\CreateSocialLink@__invoke',
        'controller' => 'App\\Filament\\Resources\\SocialLinkResource\\Pages\\CreateSocialLink',
        'as' => 'filament.admin.resources.social-links.create',
        'namespace' => NULL,
        'prefix' => 'admin/social-links',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.social-links.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/social-links/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\SocialLinkResource\\Pages\\EditSocialLink@__invoke',
        'controller' => 'App\\Filament\\Resources\\SocialLinkResource\\Pages\\EditSocialLink',
        'as' => 'filament.admin.resources.social-links.edit',
        'namespace' => NULL,
        'prefix' => 'admin/social-links',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.team-members.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/team-members',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\TeamMemberResource\\Pages\\ListTeamMembers@__invoke',
        'controller' => 'App\\Filament\\Resources\\TeamMemberResource\\Pages\\ListTeamMembers',
        'as' => 'filament.admin.resources.team-members.index',
        'namespace' => NULL,
        'prefix' => 'admin/team-members',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.team-members.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/team-members/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\TeamMemberResource\\Pages\\CreateTeamMember@__invoke',
        'controller' => 'App\\Filament\\Resources\\TeamMemberResource\\Pages\\CreateTeamMember',
        'as' => 'filament.admin.resources.team-members.create',
        'namespace' => NULL,
        'prefix' => 'admin/team-members',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.team-members.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/team-members/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\TeamMemberResource\\Pages\\EditTeamMember@__invoke',
        'controller' => 'App\\Filament\\Resources\\TeamMemberResource\\Pages\\EditTeamMember',
        'as' => 'filament.admin.resources.team-members.edit',
        'namespace' => NULL,
        'prefix' => 'admin/team-members',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.testimonials.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/testimonials',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\TestimonialResource\\Pages\\ListTestimonials@__invoke',
        'controller' => 'App\\Filament\\Resources\\TestimonialResource\\Pages\\ListTestimonials',
        'as' => 'filament.admin.resources.testimonials.index',
        'namespace' => NULL,
        'prefix' => 'admin/testimonials',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.testimonials.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/testimonials/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\TestimonialResource\\Pages\\CreateTestimonial@__invoke',
        'controller' => 'App\\Filament\\Resources\\TestimonialResource\\Pages\\CreateTestimonial',
        'as' => 'filament.admin.resources.testimonials.create',
        'namespace' => NULL,
        'prefix' => 'admin/testimonials',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.testimonials.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/testimonials/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\TestimonialResource\\Pages\\EditTestimonial@__invoke',
        'controller' => 'App\\Filament\\Resources\\TestimonialResource\\Pages\\EditTestimonial',
        'as' => 'filament.admin.resources.testimonials.edit',
        'namespace' => NULL,
        'prefix' => 'admin/testimonials',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\UserResource\\Pages\\ListUsers@__invoke',
        'controller' => 'App\\Filament\\Resources\\UserResource\\Pages\\ListUsers',
        'as' => 'filament.admin.resources.users.index',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.users.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\UserResource\\Pages\\CreateUser@__invoke',
        'controller' => 'App\\Filament\\Resources\\UserResource\\Pages\\CreateUser',
        'as' => 'filament.admin.resources.users.create',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'App\\Http\\Middleware\\SetAdminLocale',
          5 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          6 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          7 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          8 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          9 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          10 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          11 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Resources\\UserResource\\Pages\\EditUser@__invoke',
        'controller' => 'App\\Filament\\Resources\\UserResource\\Pages\\EditUser',
        'as' => 'filament.admin.resources.users.edit',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'default.livewire.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'livewire/update',
      'action' => 
      array (
        'uses' => 'Livewire\\Mechanisms\\HandleRequests\\HandleRequests@handleUpdate',
        'controller' => 'Livewire\\Mechanisms\\HandleRequests\\HandleRequests@handleUpdate',
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'default.livewire.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7soz0Imu3FHnDwLP' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/livewire.js',
      'action' => 
      array (
        'uses' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@returnJavaScriptAsFile',
        'controller' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@returnJavaScriptAsFile',
        'as' => 'generated::7soz0Imu3FHnDwLP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RNwUmRCNUO7pgk4i' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/livewire.min.js.map',
      'action' => 
      array (
        'uses' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@maps',
        'controller' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@maps',
        'as' => 'generated::RNwUmRCNUO7pgk4i',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.upload-file' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'livewire/upload-file',
      'action' => 
      array (
        'uses' => 'Livewire\\Features\\SupportFileUploads\\FileUploadController@handle',
        'controller' => 'Livewire\\Features\\SupportFileUploads\\FileUploadController@handle',
        'as' => 'livewire.upload-file',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.preview-file' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/preview-file/{filename}',
      'action' => 
      array (
        'uses' => 'Livewire\\Features\\SupportFileUploads\\FilePreviewController@handle',
        'controller' => 'Livewire\\Features\\SupportFileUploads\\FilePreviewController@handle',
        'as' => 'livewire.preview-file',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zadjjSlhsmGQIOWB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:823:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'/home/fdaei/project/my/sizdah/vendor/laravel/framework/src/Illuminate/Foundation/Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"0000000000000ea30000000000000000";}}',
        'as' => 'generated::zadjjSlhsmGQIOWB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.locale' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/language/{locale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminLocaleController@__invoke',
        'controller' => 'App\\Http\\Controllers\\AdminLocaleController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.locale',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'en|fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sitemap' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sitemap.xml',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SitemapController@index',
        'controller' => 'App\\Http\\Controllers\\SitemapController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'sitemap',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sitemap.locale' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sitemap-{locale}.xml',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\SitemapController@locale',
        'controller' => 'App\\Http\\Controllers\\SitemapController@locale',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'sitemap.locale',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@__invoke',
        'controller' => 'App\\Http\\Controllers\\HomeController',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'work.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/work',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\ProjectController@index',
        'controller' => 'App\\Http\\Controllers\\ProjectController@index',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'work.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'work.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/work/{project}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\ProjectController@show',
        'controller' => 'App\\Http\\Controllers\\ProjectController@show',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'work.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'services' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/services',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\ServiceController@__invoke',
        'controller' => 'App\\Http\\Controllers\\ServiceController',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'services',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'about' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/about',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\AboutController@__invoke',
        'controller' => 'App\\Http\\Controllers\\AboutController',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'about',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'insights.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/insights',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@index',
        'controller' => 'App\\Http\\Controllers\\PostController@index',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'insights.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'insights.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/insights/{post}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\PostController@show',
        'controller' => 'App\\Http\\Controllers\\PostController@show',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'insights.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'contact' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/contact',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\ContactController@create',
        'controller' => 'App\\Http\\Controllers\\ContactController@create',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'contact',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'contact.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{locale}/contact',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
          2 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\ContactController@store',
        'controller' => 'App\\Http\\Controllers\\ContactController@store',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'contact.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'newsletter.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '{locale}/newsletter',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
          2 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\NewsletterController@__invoke',
        'controller' => 'App\\Http\\Controllers\\NewsletterController',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'newsletter.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.privacy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/privacy-policy',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@privacy',
        'controller' => 'App\\Http\\Controllers\\LegalController@privacy',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'legal.privacy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.terms' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{locale}/terms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@terms',
        'controller' => 'App\\Http\\Controllers\\LegalController@terms',
        'namespace' => NULL,
        'prefix' => '/{locale}',
        'where' => 
        array (
          'locale' => 'fa',
        ),
        'as' => 'legal.terms',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'locale' => 'fa',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VYP4LvJ9urUyE9OR' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale.redirect',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:13:"fn () => null";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000eb80000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::VYP4LvJ9urUyE9OR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::C1H6LZPtvtJrsOf4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{any}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'locale.redirect',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:13:"fn () => null";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000eba0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::C1H6LZPtvtJrsOf4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'any' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:4:{s:6:"driver";s:5:"local";s:4:"root";s:49:"/home/fdaei/project/my/sizdah/storage/app/private";s:5:"serve";b:1;s:5:"throw";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"0000000000000ebf0000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local.upload' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:4:{s:6:"driver";s:5:"local";s:4:"root";s:49:"/home/fdaei/project/my/sizdah/storage/app/private";s:5:"serve";b:1;s:5:"throw";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:325:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ReceiveFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"0000000000000ec00000000000000000";}}',
        'as' => 'storage.local.upload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
