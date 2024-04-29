<?php 



class Prodect extends DB {

 protected $teble ='products';
 protected $con;
 public function __construct(){
    $this->con =$this->connect();
 }
 
 public function getAllProdect(){
    return $this->con->get($this->teble);
 }

}