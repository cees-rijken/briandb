<?php
/**
 * Database Class
 * Some datatypes require a length, meaning that if you supply those datatypes in your submitted JSON they also need a length. Otherwise the table manipulation will fall
 */
class Database
{
    private static $pdo;
    /**
     * Define DB connection
     *
     * @param string    $dbhost    Database server
     * @param string    $dbname    Database name
     * @param string    $dbuser    Database suser
     * @param string    $dbpass    Database user password
     * @param string    $dbchar    Database encoding
     * @return PDO      Connection object
     */     
    /**
     * 
     */
    public static function init($dbhost,$dbname,$dbuser,$dbpass,$dbchar)
    {
    $opt = array
    (
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => FALSE,
    );

    $dsn = 'mysql:host='.$dbhost.';dbname='.$dbname.';charset='.$dbchar;

    try
    {
        self::$pdo = new PDO($dsn, $dbuser, $dbpass, $opt);
    }
    catch (\PDOException $e)
    {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
        exit();
    }
        return self::$pdo;
    }

    /**
     * Query function that can handle ALL sorts of sql
     *
     * @param string    $sql            Query
     * @param string    $returnvalue    Specify the desired response
     * @return string   What you have desired
     */
    static function _qry($sql,$returnvalue='')
    {
        $result = array();
        try
        {
            $result['code'] = '200';
            $stmt = self::$pdo->prepare($sql);            
            $exec = $stmt->execute();
            switch ($returnvalue) 
            {
                case "fetch":
                    $result['message'] = $stmt->fetch();
                    break;
                case "fetchAll":
                    $result['message'] = $stmt->fetchAll();
                    break;
                case "fetchColumn":
                    $result['message'] = $stmt->fetchColumn();
                    break;
                case "fetchObject":
                    $result['message'] = $stmt->fetchObject();
                    break;
                case "rowCount":
                    $result['message'] = $stmt->rowCount();
                    break;
                case "lastInsertId":
                    $result['message'] = self::$pdo->lastInsertId();
                    break;                    
                case "fetchCount":
                    $result['message'] = $exec;
                    break;
                default:
                    $result['message'] = "OK";
            }
            return $result;
        } 
        catch (PDOException $e) 
        {
            $result['code'] = '999';
            $result['message'] = $e->getMessage();              
            return $result;
            exit;
        }
    }
}
/**
 * Initiate DB connection
 */
Database::init(Settings::_get('db_host'), Settings::_get('db_name'), Settings::_get('db_user'), Settings::_get('db_password'), Settings::_get('db_encoding'));
?>