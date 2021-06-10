<?php 

class MySQLDriver  { 

    private $host;
    private $db;
    private $user;
    private $password;
    private $dbconn;

    public function __construct($host, $db, $user, $password){
        $this->host     = $host;
        $this->db       = $db;
        $this->user     = $user;
        $this->password = $password;
    } 

    public function db_connect() { 
        try{
            $dsn = "mysql:host=".$this->host.";dbname=".$this->db;
            $this->dbconn = new PDO($dsn, $this->user, $this->password);
            $this->dbconn->exec("set names utf8");

            // set the PDO error mode to exception
            $this->dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch(PDOException $e) {
            $errortxt = date('Y-m-d H:i:s')." :: readQuery :: ".$e->getMessage(). "\n";;
            file_put_contents('PDOErrors.txt', $errortxt, FILE_APPEND);
        }

    } 
    
    
    public function read($tableName, $where=null, $order=null, $limit=null) { 
        //read
        $query = 'SELECT * FROM '.$tableName;
        $query .= ($where)?' WHERE '.$where:'';
        $query .= ($limit)?' LIMIT '.$limit:'';
        $query .= ($order)?' ORDER BY '.$order:'';
	    return $this->execQuery( $query );
    } 

    public function insert($tableName, $data) { 
        //insert 
        foreach($data as $key=>$value) {
            $col[] =$key;
            $val[] = $value;
        }
        $comma_colunas = implode(",", $col);
        $comma_valores = implode("','", $val);
        $comma_valores = "'".$comma_valores."'";
        $query = "INSERT INTO ".$tableName." (".$comma_colunas.") VALUES (".$comma_valores.")";
        return $this->execQuery( $query );
    } 

    public function update($tableName, $id, $data) { 
        //update
        foreach($data as $key=>$value) {
            $set_values[] = $key."='".$value."'";
        }
        $set_values = implode(",", $set_values);
        $query = "UPDATE ".$tableName." SET ".$set_values." WHERE id=".$id ;
        return $this->execQuery( $query );
    } 

    public function delete($tableName, $id) { 
        //delete 
        $query = 'DELETE FROM '.$tableName." WHERE id=".$id ;
        return $this->execQuery( $query );
    } 
    
    public function execQuery($sql_query){
        try {
           $stmt = $this->dbconn->query($sql_query);
           // do other things if successfully inserted
           if ($stmt) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }else{
                return json_encode((bool)false);
            }
        } catch (PDOException $e) {
            $errortxt = date('Y-m-d H:i:s')." :: readQuery :: ".$e->getMessage(). "\n";;
            file_put_contents('PDOErrors.txt', $errortxt, FILE_APPEND);
        }
    }
    
} 

?>