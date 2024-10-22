<?php
/**
 * Class Brian is where the magic happens...
 * Class.Validate.php has already validated the POST, so once you get here, you don't have to do that anymore
 */

require_once("required/Loader.php");
//print_r(Validate::$posted);
//print_r(Cheesemaker::$you);

class Brian
{
    /**
     * Execute the query that was sent in the POST
     * Mandatory fields: token, transaction, returnvalue
     *
     * @return string    JSON result
     */
    static function _query($permission)
    {
        if(!in_array($permission, Cheesemaker::$you['permissions']) || (!isset($permission) || empty($permission)))
        {
            Logger::_logger(Cheesemaker::$you['email'] . " tried to execute a ". $permission." query");
            echo '{"code":"999","message":"Invalid permissions to query"}';
            exit();
        }

        $query = Validate::$posted['transaction'];
        $returnvalue = Validate::$posted['returnvalue'];

        $runquery = Database::_qry($query, $returnvalue);
        if($runquery['code']<>"200")
        {
            Logger::_logger(Cheesemaker::$you['email'] . " failed to execute " . $query . ": " . $runquery['message']);
        }
        echo json_encode($runquery);
    }


    /**
     * Upload function in base64 encoded format (use a site like https://base64.guru/converter/encode/image/jpg)
     * Mandatory fields: token, command, filename, filedata
     *
     * @return string    JSON result
     */
    static function _fileupload()
    {
        if (!in_array("FILEUPLOAD", Cheesemaker::$you['permissions']))
        {
            Logger::_logger(Cheesemaker::$you['email'] . " tried to upload without the right permissions");
            echo '{"code":"999","message":"Invalid permissions to upload"}';
            exit();
        }

        if (!isset(Validate::$posted['filename']) || !isset(Validate::$posted['filedata']) || strlen(Validate::$posted['filedata']) < 2) 
        {
            echo '{"code":"999","message":"The JSON for your file is invalid"}';
            exit();
        }

        $ifp = fopen(Settings::_get('folder_files').Validate::$posted['filename'], 'wb');
        if(!$ifp)
        {
            echo '{"code":"999","message":"File open failed. Permissions '. Settings::_get('folder_files').' correct?"}';
            exit();                    
        }
        else
        {
            fwrite($ifp, base64_decode(Validate::$posted['filedata']));
            fclose($ifp);
            echo '{"code":"200","message":"File was written"}';            
        }
    }

    /**
     * Function to list the uploaded files
     * Mandatory fields: token, command
     *
     * @return string    JSON result
     */
    static function _filelist()
    {
        if (!in_array("FILELIST", Cheesemaker::$you['permissions']))
        {
            Logger::_logger(Cheesemaker::$you['email'] . " tried to list files without the right permissions");
            echo '{"code":"999","message":"Invalid permissions to list files"}';
            exit();                
        }

        $result = array();
        $result['code'] = "200";
        $handle = opendir(Settings::_get('folder_files'));
        while ($datei = readdir($handle)) 
        {
            if (($datei != '.') && ($datei != '..')) 
            {
                $file = "./" . $datei;
                if (!is_dir($file))
                {
                    $result['files'][] = array('filename' => $datei, 'filedate' => date("m-d-Y", filemtime($datei)));
                }
            }
        }
        closedir($handle);
        echo json_encode($result);
    }

    /**
     * Function to delete an uploaded file
     * Mandatory fields: token, command, filename 
     *
     * @return string    JSON result
     */
    static function _filedelete()
    {
        if (!in_array("FILEDELETE", Cheesemaker::$you['permissions']))
        {
            Logger::_logger(Cheesemaker::$you['email'] . " tried to delete files without the right permissions");
            echo '{"code":"999","message":"Invalid permissions to delete files"}';                        
            exit();
        }

        if (!isset(Validate::$posted['filename']) || strpos("*", Validate::$posted['filename'])) 
        {
            echo '{"code":"999","message":"Filename is invalid"}';
            exit();            
        }

        $handle = Settings::_get('folder_files') . Validate::$posted['filename'];
        if (!file_exists($handle)) 
        {
            echo '{"code":"999","message":"File does not exist"}';
            exit();
        } 
        else 
        {
            unlink($handle);
            echo '{"code":"200","message":'.$handle.'" was deleted"}';
        }
    }       
}

$valpost = explode(" ", Validate::$posted['transaction']);
$transaction = strtoupper($valpost[0]);
switch ($transaction)
{
    case "CREATE":
        Brian::_query($transaction);
        break;
    case "ALTER":
        Brian::_query($transaction);
        break;
    case "DROP":
        Brian::_query($transaction);
        break;
    case "SHOW":
        Brian::_query($transaction);
        break;
    case "INSERT":
        Brian::_query($transaction);
        break;
    case "UPDATE":
        Brian::_query($transaction);
        break;
    case "DELETE":
        Brian::_query($transaction);
        break;
    case "SELECT":
        Brian::_query($transaction);
        break;
    case "FILEUPLOAD":
        Brian::_fileupload();
        break;
    case "FILEDELETE":
        Brian::_filedelete();
        break;        
    case "FILELIST":
        Brian::_filelist();
        break;                                          
    default:
        echo '{"code":"999","message":"Your transaction is invalid"}';
        exit();
}