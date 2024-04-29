<?php 
// require_once('Models/Database.php');
class DB {
    protected $db;
    //function connect with
    public function connect (){
        $database = new Database (DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($database){
            $this->db = $database;
            return $this->db;
        }else{
            echo "Erorr";
        }
    }

}