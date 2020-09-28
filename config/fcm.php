<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'Your FCM server key'),
        'sender_id' => env('FCM_SENDER_ID', 'Your sender id'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 30.0, // in second

        //Passport Authorization
        'passport_client_id' => env('PASSPORT_CLIENT_ID', 'Your client id'),
        'passport_client_secret' => env('PASSPORT_CLIENT_SECRET', 'Your client secret'),
        'passport_grant_type' => env('PASSPORT_GRANT_TYPE', 'Your grant type'),
    ],
];
