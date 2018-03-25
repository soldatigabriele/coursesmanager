<?php
/*
 * Secret key and Site key get on https://www.google.com/recaptcha
 * */
return [
    'secret' => env('CAPTCHA_SECRET', '6LcKwU4UAAAAAFvsW_pfwgbfMfrctaVVtu8mBV_P'),
    'sitekey' => env('CAPTCHA_SITEKEY', '6LcKwU4UAAAAAKLS_G4g3qAWGZ82ffuc0Lv6jkZ0'),
    /**
     * @var string|null Default ``null``.
     * Custom with function name (example customRequestCaptcha) or class@method (example \App\CustomRequestCaptcha@custom).
     * Function must be return instance, read more in folder ``examples``
     */
    'request_method' => null,
];