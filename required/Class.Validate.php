<?php
/**
 * Validate different types of variables
 * If the request is set validate=1, then the posted datatypes are validated... --> is this a viable option OR should this be handled by frontend???
 */
class Validate
{
    public static $posted = array(); 
    /**
     * Validate the posted JSON
     *
     * @param string    $json   JSON string
     * @return array    If everything is ok, return the decoded JSON
     */
    static function _post()
    {   
        //a different HTTP method? Brian says no...
        if ($_SERVER['REQUEST_METHOD'] <> "POST") 
        {
            echo '{"code":"999","message":"Wrong method"}';
            exit();
        }

        //The $_POST superglobal is only used for Content-Type: application/x-www-form-urlencoded and not for posted JSON, so $_POST won't work
        $json = file_get_contents('php://input');
       
        // invalid json? Brian says no...
        if (!json_validate($json))
        {
            Logger::_logger("Invalid JSON was posted");
            echo '{"code":"999","message":"Invalid JSON posted"}';
            exit();
        }

        $json = json_decode($json, true); //turn all JSON into an array

        // no token? Brian says no...
        if (!isset($json['token'])) 
        {
            echo '{"code":"999","message":"Missing token"}';
            exit();
        }

        // no transaction? Brian says no...
        if (!isset($json['transaction']) || empty($json['transaction'])) 
        {
            echo '{"code":"999","message":"Missing transaction"}';
            exit();
        }

        // no returnvalue? Brian says no...
        if (!isset($json['returnvalue'])) 
        {
            echo '{"code":"999","message":"Missing returnvalue"}';
            exit();
        }        

        //return decoded JSON if we passed all the tests!
        return $json; 
    }
    /**
     * Function that checks if the database table exists and returns the sanitized name
     *
     * @return array    Boolean and a string
     */
    static function _table()
    {
        if(isset(Validate::$posted['tablename']) && !empty(Validate::$posted['tablename']))
        {
            $tablename = self::_sanitize(Validate::$posted['tablename']);
            $doesitexist = Database::_qry("SHOW TABLES LIKE '" . $tablename . "'", "rowCount");
            return array('exists' => $doesitexist, 'tablename' => $tablename);
        }
        else
        {
            return array('exists' => 0, 'tablename' => 'empty_table_name');            
        }
    }

    /**
     * Sanitize string, because we want clean tablenames and none of that fancy stuff...
     *
     * @param string    $string  Whatever you come up with
     * @return string   Returns only letters and numbers in smallcases with at least 1 non numeric character
     */
    static function _sanitize($string)
    {
        $string = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $string));
        if (empty($string) || is_numeric($string)) {
            $string = "AAA" . $string;
        }
        return $string;
    }
    
}
/**
 * Initiate the validation of the posted JSON and return an array
 */
Validate::$posted = Validate::_post();
?>