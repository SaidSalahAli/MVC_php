<?php

//  namespace App\Core;

class HomeController {
    // Your controller code goes here
    public function index(){
    //defind home page from views 
    // $this->view('home',$data);
    View::load('home');
    // "name class is".__Class__."name method is".__method__;
    }
}

?>