<?php

class Controller {
    
    protected function render($view,$data) {
        echo UI::render($view, $data);
    }
    
    protected function isSubmitted() {
        return is_array($_POST) && count($_POST) > 0;
    }
    
    protected function required($name,$error) {
        if (!$_POST[$name])
            $this->invalid($error);
    }
    
    protected function invalid($error) {
        throw new ErrorException($error);
    }
    
    protected function requireEmail($name,$error) {
        if (!$_POST[$name]) return;
        
    }
    
    protected function requireURL($name,$error) {
        if (!$_POST[$name]) return;
        
    }
    
    protected function postObject() {
        $args = func_get_args();
        $out = new stdClass();
        foreach($args as $arg) {
            $out->$arg = $_POST[$arg];
        }
        
        return $out;
    }
    protected function refresh() {
        $parms = $_GET;
        unset($parms['c']);
        unset($parms['m']);
        $this->redirect($_GET['c'], $_GET['m'], $parms);
    }
    
    protected function redirect($controller = '',$method = '',$parms = null) {
        $url = UI::url($controller, $method, $parms);
        header("Location: $url",true,301);
        exit(0);
    }


    public static function isCurrent($controller,$method) {
        
        if (!$controller)
            $controller = 'index';
        
        if (!$method)
            $method = 'index';
        
        $currentController = safepath($_GET['c']);
        
        $currentMethod = safepath($_GET['m']);
        
        if (!$currentController)
            $currentController = 'index';
        
        if (!$currentMethod)
            $currentMethod = 'index';
        
        return $controller == $currentController 
                && $method == $currentMethod;
    }
    
    
    public static function load($name) {
        $name = safepath($name);
        $path = BASEPATH.'/controllers/'.$name.'.php';
        if (!file_exists($path)) {
            throw new Exception("Controller not found: $name in $path");
        }

        $cClass = ucfirst($name).'Controller';

        if (class_exists($cClass)) {
            return new $cClass();
        }

        require_once $path;

        if (!class_exists($cClass)) {
            throw new Exception("Controller class not found: $cClass");
        }

        return new $cClass();
    }
}