<?php

class AppDAO {
    public function get($appId) {
        return DB::fetch("SELECT apps.*,GROUP_CONCAT(T.name) as tags from apps
                LEFT JOIN tags as T on T.appid = apps.id
                WHERE id = %s",$appId);
    }
    
    public function getComments($appId) {
        $comments = DB::fetchAll("SELECT * from comments where appid = %s ORDER BY created DESC",$appId);
        usort($comments, function($a,$b) {
            return $a->created-$b->created;
        });
        return $comments;
    }
    public function addComment($appId,$dto) {
        $dto->appid = $appId;
        $dto->created = time();
        $dto->id = DB::uuid();
        return DB::insert('comments', $dto);
    }
    
    public function byType($typeId) {
        return DB::fetchAll("SELECT * from apps where typeId = %s",$typeId);
    }
    
    public function search($term = null,$type = null) {
        $sql = 'SELECT apps.*,GROUP_CONCAT(T.name) as tags from apps
                LEFT JOIN tags as T on T.appid = apps.id';
        $args = array();
        $wheres = array();
        if ($term) {
            $term = mysql_escape_string($term);
            
            $tags = preg_split('/(,|and|or|\+|"|\')/i',$term);
            
            if (is_string($tags))
                $tags = explode(',',$tags);
            $tagsVal = array();;
            foreach($tags as $tag) {
                $tagsVal[] = '"'.mysql_escape_string($tag).'"';
            }
            
            $wheres[] = "apps.name LIKE '$term%'
                                OR description LIKE '%$term%'
                                OR T.name IN (".implode(',',$tagsVal).")";
            $args[] = $tags;
        }
        
        if ($type) {
            $wheres[] = "typeId = %s";
            $args[] = $type;
        }
        
        
        if (count($wheres) > 0) {
            $where = ' WHERE ('.implode(') AND (',$wheres).')';
            $sql .= $where;
        }
        
        $sql .= ' GROUP BY apps.id';
        
        
        return DB::fetchAll($sql);
    }
    
    public function insert($app) {
        $app->id = DB::uuid();
        return DB::insert('apps',$app);
    }
    
    public function update($app) {
        return DB::update('apps',$app);
    }
    
    public function delete($id) {
        return DB::delete('apps',$id);
    }
    
    public function updateTags($appId,$tags) {
        DB::execute('DELETE FROM tags where appId = %s',$appId);
        
        foreach($tags as $tag) {
            DB::insert('tags',array(
                'appId'=>$appId,
                'name'=>$tag
            ));
        }
    }
    
    public function searchTags($term) {
        $term = mysql_escape_string($term);
        return DB::fetchAll("SELECT distinct name from tags WHERE name LIKE '$term%' limit 5");
    }
    
    public function getTypes($parent = null) {
        $sql = 'SELECT * FROM apptypes';
        $order = '  ORDER BY name ASC';
        if ($parent) {
            return DB::fetchAll($sql.' WHERE parentId = %s'.$order,$parent);
        } else {
            return DB::fetchAll($sql.$order);
        }
    }
    
    public function getTypeMap($parent = null) {
        $types = $this->getTypes($parent);
        $out = array();
        foreach($types as $type) {
            $out[$type->id] = $type->name;
        }
        return $out;
    }
    
    
}