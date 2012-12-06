<?
require 'config.php';

class BSD_API {
    
    var $api_id; 
    var $api_secret;

    
    public function __construct($api_id = null, $api_secret = null) {
        // store vars requied by BSD API
        $this->api_id =  isset($api_di) ? $api_id: Config::$api_id  ;
        $this->api_secret = isset($api_secret) ? $api_secret:  Config::$api_secret  ; 
     
    }



    public function call_api($url, $query_params = array(), $post = false) {
        // prepend URL with base path for the API
        $url = Config::$http_request_base . $url;
        
        // add api_id, timestamp, and version number to query string
        $query_params['api_id'] = $this->api_id;
        if(!array_key_exists('api_ts', $query_params)) 
        {
            $query_params['api_ts'] = time();
        }

        $query_params['api_ver'] = Config::API_VER;
        
        // add api_mac to query string after using existing query and request url to build the api_mac
        $query_params['api_mac'] = $this->_build_api_mac($url, $query_params, $this->api_secret);

        // add query string to request URL
        $url .= '?' . http_build_query($query_params);
		
		
        // Initializing curl
        $ch = curl_init( $url );
         
        // Configuring curl options
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json')
        );
         
        // Setting curl options
        curl_setopt_array($ch, $options);

        //when we do a post rather then get
        if ($post)
        {
            curl_setopt($ch, CURLOPT_POST, TRUE); 
        } 

        // Getting results
        return curl_exec($ch); // Get jSON result string
	
    }



    private function _build_api_mac($url, $query, $api_secret ) {
        // break URL into parts to get the path
        $url_parts = parse_url($url);
        
        // build query string from given parameters
        $query_string = urldecode(http_build_query($query));
        
        // combine strings to build the signing string
        $signing_string = $query['api_id'] . "\n" . 
            $query['api_ts'] . "\n" . 
            $url_parts['path'] . "\n" .
            $query_string;
        
        // return encrypted hash
	    $hash = hash_hmac('sha1', $signing_string, $api_secret);

        if(Config::$output_format == Config::OUTPUT_BASE64) {
            $hash = $this->_hex2b64($hash);
        }
        // else, output_format is the default self::OUTPUT_40CHARHEX
        
        return $hash;
        
    }    



    private function _deferred_results($deferred_id) {
        $attempt = 0;
        
        // loop until result is ready or until we give up
        do {
            // delay between calls (in seconds)
            sleep(Config::$deferred_result_call_interval); 
            
            // check to see if result is ready
            $req = $this->call_api('get_deferred_results', array('deferred_id' => $deferred_id));
            
            // increment attempts counter
            $attempt++;
        } while($req->getResponseCode() == Config::HTTP_CODE_DEFERRED_RESULT_COMPILING && $attempt < Config::$deferred_result_call_max_attempts);
        
        // if the response code isn't HTTP_CODE_OK then we didn't get the result we wanted
        if($req->getResponseCode() != Config::HTTP_CODE_OK) {

            // did we go over our "max attempts"?
            if($iteration >= Config::$deferred_result_call_max_attempts) {
                throw new Exception('Could not retrieve deferred result.  Max attempts reached.', 1);
            }
            // we must have received an unexpected HTTP code
            else {
                throw new Exception('Could not retrieve deferred result.  HTTP Code ' . 
                    $req->getResponseCode() . ' was returned, with the following message: ' . 
                    $req->getResponseBody(), 2);
            }
        }
        
        // return request result
        return $req;
    }
    


    protected function _hex2b64($str) {
        $raw = '';
        $str_length = strlen($str);
        for ($i=0; $i < str_length; $i+=2) {
            $raw .= chr(hexdec(substr($str, $i, 2)));
        }
        return base64_encode($raw);
    }
    
}



?>