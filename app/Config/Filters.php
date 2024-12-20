<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>> [filter_name => classname]
     *                                                     or [filter_name => [classname1, classname2, ...]]
     */
    public array $aliases = [
        'CorsFilter' => \App\Filters\CorsFilter::class,
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'user'     => \App\Filters\UserFilter::class,
        'admin'    => \App\Filters\AdminFilter::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            'CorsFilter'
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don't expect could bypass the filter.
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     */
    public $filters = [
        'CorsFilter' => [
        'before' => [
            'save-subscription',  // Example route
            'send-notification', // Another example route
        ],
    ],
        'user' => ['before' => [
            'home', 'contact-us', 'banner', 'logout', 'achievements', 'contacts', 'activities',
            '/site', 'album', 'intern', 'pfv', 'fdas', 'inspection', 'news', 'news/(:segment)',
            'carouselhome', 'user-location', 'submitcall', 'communityreport/*',
              
        ]],
        'admin' => ['before' => [
            '/admin-home', 'admin-logout', 'admin-dashboard', 'admin-notif', 'admin/processlogin',
            'admin-registration', 'admin-registration/*','news-store', 'news-edit', 'news-update', 'delete/*', 'carouselImages', 'carousel/*', 
             'fetchCommunityReports', 'getEmergencyCallCoordinates', 'reports-recent','fire-report/create', 'fire-report/store', 
        ]],
    ];
}
