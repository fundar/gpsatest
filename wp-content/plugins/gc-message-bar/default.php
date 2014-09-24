<?php
    global $GC_Message_Bar_Config;
    $GC_Message_Bar_Config = array(
        'GCHOME' => 'http://getconversion.com',
        'GCPLUGINHOME' => 'http://getconversion.com/products/gc-message-bar',
        'GCSERVICES' => 'http://services.getconversion.net',
        'GCFORUM' => 'http://community.getconversion.net/forum/gc-message-bar',
        'GCROADMAP' => 'http://community.getconversion.net/roadmap/gc-message-bar',
        'GCIDEA' => 'http://community.getconversion.net/idea',
        'GCBUG' => 'http://community.getconversion.net/bug',
        'MYGC' => 'http://my.getconversion.net',
        'WPORGURL' => 'http://wordpress.org/plugins/gc-message-bar/',
        'METRIX_JS_URL' => 'js.metrix.getconversion.net/mx.min.js',
        'METRIX_ENDPOINT_URL' => 'metrix.getconversion.net/',
        'GCTYPE' => 'RELEASE', 
        'GCAPI' => 'https://api.my.getconversion.net',
        'MYGC_APIKEY' => 'c90d205af782d880ee98b5a47d04c5af',
        'SIGNING' => array(
            'enabled' => true,
            'parameter' => array(
                'name' => 'sign',
                'header_prefix' => 'X-GC-'
            ),
            //these values are used by the hash_hmac(algo, data, secret) function 
            'cipher' => array(
                'algorithm' => 'sha1', // ATM FIXED
                'secret' => 'o3um5bWHANzBFOEgB6yg'
            )
        )
    );
