<?php 


session_start();

class ProductController {

public function index (){
 $db= new Product;
 $data['products']= $db->getAllProduct();
 View::load('products/index',$data);
    
}

public function add(){

    View::load('products/add');

}
public function delete($id) {
    $filId = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
    $db = new Product;
    if ($db->deleteProduct($filId)) {
    
        header("Location: /product");
         $_SESSION['message']="Data deleted Successfully";
    } else {
        echo "Delete operation failed";
 
    }
}


public function store(){
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $qty = $_POST['qty'];
        $data = array(
            "name" => $name,
            "price" => $price,
            "description" => $description,
            "qty" => $qty
        );


        $db= new Product;
        if ($db->insertProduct($data)){
            $data['products']= $db->getAllProduct();
            header("Location: /product");
            $_SESSION['message']="Data add Successfully";
            // View::load('products/index',$data,);
 

        }else{
            View::load('products/add',["dngaer"=>"Data are not inserted"]);
            echo "dngaer";

        }

    }
  
}
public function edit($id) {
    // Convert the $id parameter to an integer
    $productId = (int)$id;

    $db = new Product;
    $product = $db->getRow($productId);

    if (empty($product)) {
        // Handle the case where the product is not found
        echo "Product not found.";
        return;
    }

    // If the product exists, load the edit view with the product data
    $data['product_edit'] = $product;
    View::load('products/edit', $data);
}



public function update($id) {
    if(isset($_POST['submit'])) {
        $filId = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $name = $_POST['name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $qty = $_POST['qty'];

        $dataUpdate = array(
            "name" => $name,
            "price" => $price,
            "description" => $description,
            "qty" => $qty
        );

        $db = new Product;
        if ($db->updateProduct($dataUpdate, $filId)) {
            header("Location: /product");
            $_SESSION['message'] = "Data edited successfully";
        } else {
            echo "danger";
            // View::load('products/add',["dngaer"=>"Data are not inserted"]);
        }
    }
}
}