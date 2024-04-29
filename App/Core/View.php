<?php 



class View
{
    public static function lode($view_name, $view_data=[])

    {
        // echo $view_name;
        $file = VIEWS.$view_name.'.php';
        // echo $file;
        if(file_exists($file)){
            extract($view_data);
            ob_start();
            require($file);
            ob_end_flush();
        }else{
            echo "This view : ".$view_name."  :is dose not exist";
        }
    }
}