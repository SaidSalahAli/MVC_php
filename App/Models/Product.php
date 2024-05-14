<?php 
class Product extends DB {
 protected $table ='products';
 protected $con;
 public function __construct(){
    $this->con =$this->connect();
 }
 public function getAllProduct(){
    return $this->con->get($this->table);
 }

 public function insertProduct($data){
    return $this->con->insert($this->table,$data);
 }
 public function deleteProduct($id){
          return $this->con->delete($this->table,"id = $id");
  }
   
  public function getRow($id){
   return $this->con->getSingleProduct($this->table,"$id");
}
public function updateProduct($data, $id) {
   return $this->con->update($this->table, $data, "id = $id");
}

}