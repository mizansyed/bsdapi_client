<?php
class Config
{
	

    static public $api_id 		= ''; 
    static public $api_secret 	= '';
    
    static public $http_request_base = 'http://www.example.com/page/api/';
    static public $http_request_options = array(
            										'timeout' => 10,
            										'readTimeout' => array(10, 0),
            										'allowRedirects' => true,
            										'maxRedirects' => 3,
        										);
    
    static public $deferred_result_call_interval 		= 30;
    static public $deferred_result_call_max_attempts 	= 20;
    
    static public $output_format = self::OUTPUT_40CHARHEX;
    
    const HTTP_CODE_OK                          = 200;
    const HTTP_CODE_DEFERRED_RESULT             = 202;
    const HTTP_CODE_DEFERRED_RESULT_COMPILING   = 503;
    
    const OUTPUT_40CHARHEX 	= 1;
    const OUTPUT_BASE64 	= 2;
    
    const API_VER = 1;



}
?>