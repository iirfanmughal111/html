<?php

return array(

    /**
     * Set our Sandbox and Live credentials
     */
   
	'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', 'AWrLFkwt4gHFFJfcjuhXHA_mILfX28lC99qbgwHMu9jEqCxqa9XLoMlfGQcuKanAnUyl0X5DcOfp61oP'),
    'sandbox_secret' => env('PAYPAL_SANDBOX_SECRET', 'ENAd6BeIDdrivvASLEkaOEBZSi8E0TTUIkoX6NSLQ4af3bAChzUY_v47wX0QoayHsz6cDoJ9uwO5O_08'),
    'sandbax_api_url' => env('PAYPAL_SANDBOX_API_URL', 'https://api.sandbox.paypal.com'),
   
    'live_client_id' => env('PAYPAL_CLIENT_ID', ''),
    'live_secret' => env('PAYPAL_LIVE_SECRET', ''),
	// 'live_productId' => env('PAYPAL_LIVE_PRODUCT_ID', ''),
	'live_api_url' => env('PAYPAL_LIVE_API_URL', 'https://api.paypal.com'),
	



    
    /**
     * SDK configuration settings
     */
    'settings' => array(

        /** 
         * Payment Mode
         *
         * Available options are 'sandbox' or 'live'
         */
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        
        // Specify the max connection attempt (3000 = 3 seconds)
        'http.ConnectionTimeOut' => 300000,
       
        // Specify whether or not we want to store logs
        'log.LogEnabled' => true,
        
        // Specigy the location for our paypal logs
        'log.FileName' => storage_path() . '/logs/paypal.log',
        
        /** 
         * Log Level
         *
         * Available options: 'DEBUG', 'INFO', 'WARN' or 'ERROR'
         * 
         * Logging is most verbose in the DEBUG level and decreases 
         * as you proceed towards ERROR. WARN or ERROR would be a 
         * recommended option for live environments.
         * 
         */
        'log.LogLevel' => 'DEBUG'
    ),
);