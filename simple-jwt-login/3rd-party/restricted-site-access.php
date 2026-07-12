<?php

use SimpleJWTLogin\Modules\SimpleJWTLoginSettings;
use SimpleJWTLogin\Modules\WordPressData;

## Restricted Site Access
add_filter(
    'restricted_site_access_is_restricted',
    function ($is_restricted) {
        $requestUri = isset($_SERVER['REQUEST_URI'])
            ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']))
            : '';

        $jwtSettings = new SimpleJWTLoginSettings(new WordPressData());
        $namespace   = $jwtSettings->getGeneralSettings()->getRouteNamespace();

        $namespacePath = '/' . ltrim($namespace, '/');

        if (strpos($requestUri, $namespacePath) !== false) {
            return false;
        }

        // Also handle the case where the route is passed as a POST/GET parameter
        // (used when WordPress pretty permalinks are disabled)
        $restRoute = isset($_REQUEST['rest_route'])
            ? sanitize_text_field(wp_unslash($_REQUEST['rest_route']))
            : '';

        if ($restRoute !== '' && strpos($restRoute, $namespacePath) !== false) {
            return false;
        }

        return $is_restricted;
    },
    10
);
