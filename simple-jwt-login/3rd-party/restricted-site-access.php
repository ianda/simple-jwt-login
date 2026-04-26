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

        if (strpos($requestUri, '/' . ltrim($namespace, '/')) !== false) {
            return false;
        }

        return $is_restricted;
    },
    10
);
