<?php 



class ProdectController {

public function index (){
 $db= new Prodect;
 $data['products']= $db->getAllProdect();
 View::lode('products/index',$data);
    
}


}