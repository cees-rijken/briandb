<?php
/**
 * JWT Class, based on: https://medium.com/@selieshjksofficial/creating-and-managing-jwt-tokens-in-php-b6c1fc6c1b46
 */
class Cheesemaker
{
    public static $all = array(); //all of them
    public static $you = array(); //one of them: you
               
    /**
     * Read user file containing properties array for comparing to JWT payload
     */
    public static function _blessthecheesemakers()
    {
        if(file_exists(dirname(__FILE__) . "/../CHEESEMAKERS.php"))
        {
            require_once(dirname(__FILE__) . "/../CHEESEMAKERS.php");
            foreach ($c as $k => $v) 
            { //has to be done through an array, because you cannot declare static variables in a foreach loop
                self::$all[$k] = $v;
            }
        }
    }

    /**
     * Verify the token: if there's a token in the post then validate it and compare it to CHEESEMAKERS.php array
     *
     * @return string  JSON string
     */
    public static function _authenticate()
    {
        self::_blessthecheesemakers(); //read the CHEESEMAKERS file and put everything inside array
        if(!is_array(self::$all) || (int)count(self::$all)<1) //check if there is anything in that array
        {
            Logger::_logger("Authentication failed because there are no cheesemakers...");
            echo '{"code":"999","message":"Authentication failed"}';
            exit();
        }

        if (!is_array(Validate::$posted)) 
        {
            Logger::_logger("Authentication failed because the posted array was misshapen...");
            echo '{"code":"999","message":"Authentication failed"}';
            exit();
        }

        $token = Validate::$posted['token'];
        if ((int)self::_validate($token) < 1)
        {
            Logger::_logger("Token validation failed...");
            echo '{"code":"999","message":"Token validation failed"}';
            exit();
        }

        $decodedtoken = self::_decode($token);
        $decodedid = $decodedtoken['id'];

        //if the value of the decoded id does not exist in $all
        if (!array_key_exists($decodedid, self::$all)) 
        {
            Logger::_logger("Authentication failed because you are not a cheesemaker...");
            echo '{"code":"999","message":"You are not a cheesemaker"}';
            exit();
        }

        // if all is well....
        if (isset($decodedtoken['name']) && isset(self::$all[$decodedid]['name']) && $decodedtoken['name'] == self::$all[$decodedid]['name'] && isset($decodedtoken['email']) && isset(self::$all[$decodedid]['email']) && $decodedtoken['email'] == self::$all[$decodedid]['email'] && isset($decodedtoken['pass']) && isset(self::$all[$decodedid]['pass']) && $decodedtoken['pass'] == self::$all[$decodedid]['pass']) 
        {
            self::$you['name'] = $decodedtoken['name'];
            self::$you['email'] = $decodedtoken['email'];
            self::$you['pass'] = True; //do not save passwords...
            self::$you['permissions'] = self::$all[$decodedid]['permissions'];
        }
        else //if not: stop the whole shizzle because you are not authenticated
        {
            Logger::_logger("Token " . $token . " is incomplete...");
            echo '{"code":"999","message":"Token is incomplete"}';
            exit();
        }
    }

    /**
     * Encode JSON payload and creating a token. Beware: there is no endpoint for this function so it cannot be called. It's for administrative purposes only.
     * You can easily use https://jwt.io/#debugger-io and as payload: {"id": 0,"name": "Cees","email": "hallo@ceesrijken.nl","pass": "password"}. The true values should be on of the entries in CHEESEMAKERS.php
     *
     * @param string    $payload    JSON string
     * @return string   JWT token
     */
    public static function _encode($payload)
    {
        $header = self::_base64encode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $payload = self::_base64encode(json_encode($payload));
        $signature = hash_hmac('sha256', $header . '.' . $payload, Settings::_get('secret_jwt'), true);
        $signature = self::_base64encode($signature);
        return $header . '.' . $payload . '.' . $signature;
    }

    /**
     * Check token for validity by comparing length as well as signature
     *
     * @param string    $token    Token string
     * @return bool     Result of comparing token signature and expected signature
     */    
    public static function _validate($token)
    {
        $elements = explode('.', $token);
        if (count($elements) !== 3) 
        {
            Logger::_logger("Wrong number of segments in JWT token");
            echo '{"code":"999","message":"Wrong number of segments in JWT token"}';
            exit();            
        }        

        list($header, $payload, $signature) = $elements;
        if (null === $header || null === $payload || null === $signature) 
        {
            Logger::_logger("Invalid token length");
            echo '{"code":"999","message":"Invalid token length"}';
            exit();     
        }

        $signature = self::_base64decode($signature);                
        $expectedSignature = hash_hmac('sha256', $header . '.' . $payload, Settings::_get('secret_jwt'), true);
        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Decode token and return payload
     *
     * @param string    $token    Token string
     * @return string   JSON string
     */       
    public static function _decode($token)
    {
        list(, $payload,) = explode('.', $token);
        $payload = self::_base64decode($payload);
        return json_decode($payload, true);
    }

    /**
     * Base64 ENcode function
     *
     * @param string    $data    String
     * @return string   Encoded string
     */      
    public static function _base64encode($data): string
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }
    /**
     * Base64 DEcode function
     *
     * @param string    $data    Encoded string
     * @return string   Decoded string
     */
    public static function _base64decode($data): string
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }
}

/**
 * Initiate token verifier. Needs to be activated in live situations...
 */
Cheesemaker::_authenticate();
?>