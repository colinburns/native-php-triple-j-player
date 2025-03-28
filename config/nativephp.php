<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */
    'name' => 'Triple J Player',

    /*
    |--------------------------------------------------------------------------
    | Window Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the initial window for your application.
    */
    'window' => [
        'width' => 400,
        'height' => 720,
        'minWidth' => 400,
        'minHeight' => 720,
        'maxWidth' => 600,
        'maxHeight' => 1000,
        'transparent' => false,
        'alwaysOnTop' => false,
        'titleBarStyle' => 'hidden', // Options are: 'default', 'hidden', 'hiddenInset'
        'vibrancy' => 'under-window', // Options are: 'under-window', 'sidebar'
        'header' => false,
        'autoHideMenuBar' => true,
        'fullscreenable' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the environment variables that will be injected
    | into your application.
    */
    'environment' => [
        'NATIVEPHP_RENDERER_URL' => env('NATIVEPHP_RENDERER_URL', 'https://localhost:1093'),
        'NATIVEPHP_APP_URL' => env('NATIVEPHP_APP_URL', 'https://localhost:8000'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Deep Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the deep links that your application will respond to.
    */
    'deeplinks' => [
        'scheme' => 'triplej',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the menu items that will be shown in your application.
    */
    'menu' => [
        'items' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tray Menu Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the menu items that will be shown in your tray menu.
    */
    'tray' => [
        'show' => false,
        'icon' => resource_path('icons/tray-icon-dark.png'),
        // 'icon_dark' => resource_path('icons/tray-icon-dark.png'),
        'items' => [
            //
        ],
    ],

    'dock' => [
        'show' => true,
        'icon' => resource_path('icons/dock-icon-dark.png'),
        'icon_dark' => resource_path('icons/dock-icon-dark.png'),
        'items' => [
            //
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Update Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure if and how the auto updater should work.
    */
    'auto_update' => [
        'desired_channel' => env('NATIVEPHP_AUTO_UPDATE_DESIRED_CHANNEL', 'latest'),
        'check_on_startup' => env('NATIVEPHP_AUTO_UPDATE_CHECK_ON_STARTUP', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the commands that should be built when running
    | NativePHP's build command.
    */
    'commands' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Compilers
    |--------------------------------------------------------------------------
    |
    | Here you may configure how your application will be compiled.
    |
    */
    'compilers' => [
        'mac' => true,
        'windows' => true,
        'linux' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Concurrency
    |--------------------------------------------------------------------------
    |
    | Here you may configure the number of concurrent processes that should
    | be used for bundling.
    |
    */
    'concurrency' => (int) env('NATIVEPHP_CONCURRENCY', 5),

    /*
    |--------------------------------------------------------------------------
    | Bundlers
    |--------------------------------------------------------------------------
    |
    | Here you may configure the built-in bundlers for your application.
    |
    */
    'bundlers' => [
        'php' => [
            'enabled' => env('NATIVEPHP_BUNDLERS_PHP_ENABLED', true),
            'concurrency' => 7,
            'prefix' => 'php',
            'extensions' => ['php', 'json', 'xml', 'yml'],
            'include' => ['app', 'bootstrap', 'config', 'database', 'lang', 'public', 'resources', 'routes', 'storage', 'vendor', '.env'],
        ],

        'node' => [
            'enabled' => env('NATIVEPHP_BUNDLERS_NODE_ENABLED', true),
            'concurrency' => 7,
            'prefix' => 'node',
            'extensions' => env('NATIVEPHP_BUNDLERS_NODE_EXTENSIONS', ['js', 'cjs', 'mjs', 'jsx', 'ts', 'css', 'scss']),
        ],

        /*
        'assets' => [
            // Copy the public folder verbatim into the NativePHP build
            'enabled' => true,
            'strategy' => 'CopyDirectoryBundleStrategy',
            'prefix' => 'assets',
            'include' => ['public'],
            // 'exclude' => [],
        ],

        'vite-processed-assets' => [
            // Leverage Vite to bundle the resources/js folder on NativePHP bundling
            'enabled' => true,
            'strategy' => 'ViteBundleStrategy',
            'prefix' => 'vite-processed-assets',
            'entrypoints' => ['resources/js/app.js'],
            // 'exclude' => [],
        ],
        */
    ],

    /*
    |--------------------------------------------------------------------------
    | Launch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the ways your application can be launched.
    |
    */
    'launch' => [
        'login' => env('NATIVEPHP_LAUNCH_ON_LOGIN', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Crypto
    |--------------------------------------------------------------------------
    |
    | Here you may configure the encryption options for your application.
    |
    */
    'crypto' => [
        'key' => env('NATIVEPHP_CRYPTO_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | OS Notifications
    |--------------------------------------------------------------------------
    |
    | Here you may configure the notifications that your application can send
    | to the user using the operating system's notification system.
    |
    */
    'notifications' => [
        'sound' => env('NATIVEPHP_NOTIFICATIONS_SOUND', true),
        'actions' => env('NATIVEPHP_NOTIFICATIONS_ACTIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | GitHub Api
    |--------------------------------------------------------------------------
    |
    | Here you may configure the GitHub api Client.
    |
    */
    'github' => [
        'personal_access_token' => env('NATIVEPHP_GITHUB_PERSONAL_ACCESS_TOKEN'),
        'timeout' => env('NATIVEPHP_GITHUB_TIMEOUT', 15),
        'tls' => env('NATIVEPHP_GITHUB_TLS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Server
    |--------------------------------------------------------------------------
    |
    | Here you may configure the HTTP server that is accessed by the main browser window and the
    | web JS bridge, which provides access from the renderer to the main process.
    |
    */
    'server' => [
        // The HTTP host the application is available at. By default, this is only localhost.
        'host' => env('NATIVEPHP_SERVER_HOST', 'localhost'),
        // The HTTP port the application is available at.
        'port' => env('NATIVEPHP_SERVER_PORT', 8000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Remote Developer Tools
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Remote Developer Tools.
    |
    */
    'remote_developer_tools' => [
        // Whether the Remote DevTools are available.
        'enabled' => env('NATIVEPHP_REMOTE_DEVELOPER_TOOLS_ENABLED', env('APP_DEBUG')),
        // The password to secure the Remote DevTools.
        'password' => env('NATIVEPHP_REMOTE_DEVELOPER_TOOLS_PASSWORD'),
        // The port to serve the Remote DevTools on.
        'port' => env('NATIVEPHP_REMOTE_DEVELOPER_TOOLS_PORT', 1093),
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Here you may configure the Storage.
    |
    */
    'storage' => [
        'base_path' => env('NATIVEPHP_STORAGE_BASE_PATH', env('HOME').'/.'.(env('APP_NAME', 'nativephp-app'))),
        'cache' => env('NATIVEPHP_STORAGE_CACHE_PATH', env('HOME').'/.'.(env('APP_NAME', 'nativephp-app')).'/cache'),
        'app' => env('NATIVEPHP_STORAGE_APP_PATH', env('HOME').'/.'.(env('APP_NAME', 'nativephp-app')).'/app_data'),
        'logs' => env('NATIVEPHP_STORAGE_LOGS_PATH', env('HOME').'/.'.(env('APP_NAME', 'nativephp-app')).'/logs'),
    ],
];
