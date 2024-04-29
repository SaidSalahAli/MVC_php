<?php 


// namespace App\Core;
// front end code
class App {

protected $controller = "HomeController";
protected $action = "index";
protected $params = [];

function __construct(){
 
   $this->prepereURL();
   $this->render();
;
}

// extract conteoller , method and parameter..
   private function prepereURL()
   {
    $url = $_SERVER['QUERY_STRING'];
    if (!empty($url)){
        // trim url 
        $url = trim($url,"/");       
        $url = explode('/',$url);
        // defind the controller
        $this->controller = isset($url[0]) ? ucwords($url[0])."Controller" : "HomeController";
        $this->action = isset($url[1]) ? $url[1] : "index";
        // echo $this->action;
        unset($url[0],$url[1]);
        $this->params = !empty($url) ? array_values($url):[];
        // print_r( $this->params);
    }
   }
   private function render()
{
    // Check if the specified class exists
    if(class_exists($this->controller)){
        // Create an instance of the specified controller
        $controller = new $this->controller;
        // Check if the specified method exists in the controller
        if (method_exists($controller, $this->action)){
            // Call the specified method with the specified parameters on the instance
            call_user_func_array([$controller, $this->action], $this->params);
        } else {
            // If the specified method does not exist in the controller
            echo "Method does not exist: " . $this->action;
        }
    } else {
        // If the specified class does not exist
        echo "Controller does not exist: " . $this->controller;
    }
}


    
}