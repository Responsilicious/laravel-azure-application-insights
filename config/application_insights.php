<?php

return [
    /*
     * Microsoft Application Insights instrumentation key.
     */
    'instrumentation_key' => env('APPINSIGHTS_INSTRUMENTATIONKEY'),
    /*
     * Whether to send data
     */
    'is_enabled' => env('APPINSIGHTS_ENABLED', true),

    /**
     * Whether to track users (anonymously)
     */
    'track_anonymous_users' => env('APPINSIGHTS_TRACK_ANONYMOUS_USERS', true),

    /**
     * Set naming for cloud role and instance
     */
    'cloud_role_name' => env('WEBSITE_SITE_NAME'),
    
    'cloud_role_instance' => env('WEBSITE_INSTANCE_ID')
];
