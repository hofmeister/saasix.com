<?php

class DB {
    private static $link;
    
    public static function connect() {
        global $CONFIG;
        self::$link = mysql_pconnect($CONFIG->DB_HOST,$CONFIG->DB_USER,$CONFIG->DB_PASS);
        
        mysql_select_db($CONFIG->DB_NAME,self::$link);
        self::q('SET NAMES UTF8');
        
    }
    
    protected static function q($query) {
        $r = mysql_query($query,self::$link);
        if ($r)
            return $r;
        throw new Exception(mysql_error(self::$link));
    }
    
    public static function execute($query) {
        $args = func_get_args();
        array_shift($args);
        $query = self::formatQuery($query,$args);
        
        $r = self::q($query);
        return mysql_affected_rows(self::$link) > 0;
    }
    
    public static function fetch($query) {
        $query .= " LIMIT 1";
        
        $args = func_get_args();
        array_shift($args);
        $query = self::formatQuery($query,$args);
        
        $r = self::q($query);
        return mysql_fetch_object($r);
    }
    
    public static function fetchCount($query) {
        $args = func_get_args();
        array_shift($args);
        $query = self::formatQuery($query,$args);
        
        $r = self::q($query);
        
        $result = mysql_fetch_object($r);
        return $result->count ? $result->count : 0;
    }
    
    public static function fetchAll($query) {
        $args = func_get_args();
        array_shift($args);
        $query = self::formatQuery($query,$args);
        
        $r = self::q($query);
        $out = array();
        while($row = mysql_fetch_object($r)) {
            $out[] = $row;
        }
        return $out;
    }
    
    
    public static function delete($table,$id) {
        $query = 'DELETE FROM '.$table.' WHERE id = %s';
        
        $r = self::execute($query,$id);
        return mysql_affected_rows(self::$link) > 0;
    }
    
    
    public static function insert($table,$dto) {
        $query = 'INSERT IGNORE INTO '.$table.' SET ';
        
        $query = self::buildSetter($query,$dto);
        
        $r = self::q($query);
        return mysql_affected_rows(self::$link) > 0;
    }
    
    public static function update($table,$dto) {
        $query = 'UPDATE '.$table.' SET ';
        
        $query = self::buildSetter($query,$dto);
        
        $query .= self::formatQuery(' WHERE id = %s',array($dto->id));
        
        $r = self::q($query);
        return mysql_affected_rows(self::$link) > 0;
    }
    
    private static function buildSetter($query,$dto) {
        $out = '';
        $first = true;
        foreach($dto as $field=>$value) {
            $query .= $first ? '' : ',';
            $query .= "`$field` = %s";
            $args[] = $value;
            $first = false;
        }
        
        return self::formatQuery($query,$args);
    }
    
    private static function formatQuery($query,$args) {
        if (!$args || count($args) == 0) 
            return $query;
        
        $sqlArgs = array();
        foreach($args as $arg) {
            if (is_numeric($arg))
                $sqlArgs[] = $arg;
            else
                $sqlArgs[] = '"'.mysql_escape_string($arg).'"';
        }
        $out = vsprintf($query, $sqlArgs);
        
        if (!$out) {
            throw new Exception("Mismatch: $query vs. [".implode(',', $sqlArgs).']');
        }
        return $out;
    }
    
    public static function uuid() {
        //D48D08C6-319B-4C57-944F-E5E684B6048A
        $uid = md5(microtime().time().rand(100000, 999999));
        return substr($uid, 0,8)
                    .'-'.substr($uid,8,4)
                    .'-'.substr($uid,12,4)
                    .'-'.substr($uid,16,4)
                    .'-'.substr($uid,20);
    }
    
}