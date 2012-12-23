<?php

class UI {
    private static $locations = array();
    private static $title;
    private static $description;
    private static $robots = 'index';
    
    public static function render($viewFile,$dataArg) {
        if (is_array($dataArg))
            $view = (object) $dataArg;
        else if (is_object($dataArg)) 
            $view = $dataArg;
        else  
            $view = new stdClass();
        
        ob_start();
            require BASEPATH."/views/$viewFile.php";
        return ob_get_clean();
    }
    
    public static function options($options,$currentVal) {
        $out = "\n";
        foreach ($options as $key=>$val) {
            $optVal = $key;
            $optName = $val;
            
            if (is_int($key)) {
                $optVal = $val;
            }
            
            $selected = '';
            if ($optVal == $currentVal)
                $selected = 'selected="selected"';
                
            $out .= "<option $selected value=\"$optVal\">$optName</option>\n";
        }
        
        echo $out;
    }
    
    public static function url($controller,$method,$parms = null) {
        
        if (!$controller)
            $controller = 'index';
        
        if (!$method)
            $method = 'index';
        
        $url = "?c=$controller";
        
        if ($method != 'index')
            $url .= '&m='.$method;
        
        if ($parms) {
            
            foreach($parms as $key=>$val) {
                $url .= "&$key=$val";
            }
        }
        
        return $url;
    }
    
    public static function activeClass($controller,$method) {
        $active = '';
        if (Controller::isCurrent($controller, $method))
            $active = 'active';
        return $active;
    }
    
    public static function renderAt($location,$callback) {
        ob_start();
        
        $callback();
        
        self::$locations[$location] .= ob_get_clean();
    }
    
    public static function location($location) {
        echo self::$locations[$location];
    }
    
    public static function timeSince($time) {
        $secs = time()-$time;
        if ($secs < 60)
            return "Just now";
        
        $days = floor($secs/86400);

        $hours = floor(($secs%86400)/3600);
        $mins = floor(($secs%3600)/60);
        $secs = $secs%60;
        
        if ($days > 5)
            return date('Y-m-d',$time);
        
        if ($days > 1)
            return "$days days ago";
        if ($days > 0)
            return "$days day ago";
        
        if ($hours > 1)
            return "$hours hours ago";
        if ($hours > 0)
            return "$hours hour ago";
            
        if ($mins > 1)
            return "$mins minutes ago";
        if ($mins > 0)
            return "$mins minute ago";
        
        if ($secs > 0)
            return "$secs seconds ago";
        if ($secs > 0)
            return "$secs second ago";       
    }
    
    public static function pageTitle($title = null) {
        if ($title)
            self::$title = $title;
        return self::$title;
    }
    
    public static function pageDescription($description = null) {
        if ($description)
            self::$description = $description;
        return self::$description;
    }
    
    public static function pageRobots($robots = null) {
        if ($robots)
            self::$robots = $robots;
        return self::$robots;
    }
}