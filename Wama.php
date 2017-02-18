<?php
class Wama{
    private $access_token;
    private $token_type;
    
    /**
    * Constructor
    *
    * Handles oauth 2 bearer token fetch
    * @link https://www.wama.cloud/api-documentation.html
    */
    public function __construct(){
        $postvals = "username=".USERNAME."&password=".PASSWORD."&grant_type=password";
        $uri = URI_API . "oauth/token";
        $auth_response = self::curl($uri, 'POST', $postvals, true);
        $this->access_token = $auth_response['body']->access_token;
        $this->token_type = $auth_response['body']->token_type;
    }
    
    /**
    * Get all products
    *
    * return the list of products
    * @link https://www.wama.cloud/api-documentation.html#collapse1
    */

    public function get_products($request){
        $postvals = $request;
        $uri = URI_API . "api/products";
        return self::curl($uri, 'GET', $postvals);
    }
 
    /**
    * cURL
    *
    * Handles GET / POST requests for auth requests
    * @link http://php.net/manual/en/book.curl.php
    */
    private function curl($url, $method = 'GET', $postvals = null, $auth = false){
        $ch = curl_init($url);
        
        //if we are sending request to obtain bearer token
        if ($auth){
            $headers = array("Accept: application/json", "Accept-Language: en_GB");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, CLIENT_ID . ":" .CLIENT_SECRET);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        //if we are sending request with the bearer token for protected resources
        } else {
            $headers = array("Content-Type:application/json", "Authorization:{$this->token_type} {$this->access_token}");
        }
           
        $options = array(
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_TIMEOUT => 10
        );
            
            
        if ($method == 'POST'){
            $options[CURLOPT_POSTFIELDS] = $postvals;
            $options[CURLOPT_CUSTOMREQUEST] = $method;
        }
        
        curl_setopt_array($ch, $options);
           
        $response = curl_exec($ch);
        $header = substr($response, 0, curl_getinfo($ch,CURLINFO_HEADER_SIZE));
        $body = json_decode(substr($response, curl_getinfo($ch,CURLINFO_HEADER_SIZE)));
        curl_close($ch);
            
        return array('header' => $header, 'body' => $body);
    }
}

?>
