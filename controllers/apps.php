<?php
require_once '../daos/apps.php';

class AppsController extends Controller {
     
    /**
     * @var AppDAO
     */
    private $apps;
    
    public function __construct() {
        $this->apps = new AppDAO();
    }
    
    public function index() {
        $rows = $this->apps->search(
                $_GET['term']
        );
        
        return array(
            'rows'=>$rows
        );
    }
    
    
    public function browse() {
        $rows = $this->apps->search(
                $_GET['term'],
                $_GET['typeId']
        );
        
        return array(
            'rows'=>$rows,
            'types'=>$this->apps->getTypeMap()
        );
    }
    
    public function view() {
        $id = $_GET['id'];
        
        if ($this->isSubmitted()) {
            $this->required('name','Name is required');
            $this->required('comment','Comment contents is required');
            
            $comment = $this->postObject('name','comment');
            
            $_SESSION['nickname'] = $comment->name;
            
            $this->apps->addComment($id,$comment);
            $this->refresh();
        }
        
        return array(
            'app'=>$this->apps->get($id),
            'comments'=>$this->apps->getComments($id)
        );
    }
    public function delete() {
        $id = $_GET['id'];
        
        $this->apps->delete($id);
        
        $this->redirect('apps');
    }
    
    public function add() {
        
        if ($this->isSubmitted()) {
            //Form submitted
            $this->required('name','Application name is required');
            $this->required('description','Description is required');
            $this->required('url','Website is required');
            $this->required('logo','Website logo is required');
            $this->requireURL('url','Website must contain a valid url');
            
            $app = $this->postObject('name','description','typeId',
                                    'url','price','logo','bgcolor','color');
            if (!$app->price)
                $app->price = 'NONFREE';
            
            $app->created = time();
            $app->modified = time();
            
            
            
            if ($this->apps->insert($app)) {
                
                if ($app->logo) {
                    \Cloudinary\Uploader::upload($app->logo, 
                            array("public_id" => $app->id,'format'=>'png'));
                }
                
                $tags = array();
                if ($_POST['tags'])
                    $tags = explode(',',$_POST['tags']);
                
                $this->apps->updateTags($app->id,$tags);
                
                $this->redirect('apps');
            }
            
            $this->invalid('Unknown error occured. Please try again');
        }
        
        return array(
            'types'=>$this->apps->getTypeMap()
        );
    }
    
    public function edit() {
        $id = $_GET['id'];
        $app = $this->apps->get($id);
        
        if ($this->isSubmitted()) {
            //Form submitted
            $this->required('name','Application name is required');
            $this->required('description','Description is required');
            $this->required('url','Website is required');
            $this->required('logo','Website logo is required');
            $this->requireURL('url','Website must contain a valid url');
            
            $app = $this->postObject('name','description','typeId','url',
                                    'price','logo','bgcolor','color');
            if (!$app->price)
                $app->price = 'NONFREE';
            
            $app->id = $id;
            $app->created = time();
            $app->modified = time();
            
            if ($app->logo) {
                \Cloudinary\Uploader::upload($app->logo, 
                        array("public_id" => $id,'format'=>'png'));
            }
            
            if ($this->apps->update($app)) {
                $tags = array();
                if ($_POST['tags'])
                    $tags = explode(',',$_POST['tags']);
                
                $this->apps->updateTags($app->id,$tags);
                
                $this->redirect('apps');
            }
            
            $this->invalid('Unknown error occured. Please try again');
        }
        
        return array(
            'app'=>$app,
            'types'=>$this->apps->getTypeMap()
        );
    }
    
    
    public function listtags() {
        $term = $_GET['term'];
        $tags = $this->apps->searchTags($term);
        
        $out = array();
        
        foreach($tags as $tag) {
            $out[] = $tag->name;
        }
        return $out;
    }
    
    public function lookupUrl() {
        $url = $_GET['url'];
        $html = @file_get_contents($url);
        $invalid = true;
        if ($html) {
            $invalid = false;
            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;
            $metaTags = $dom->getElementsByTagName('meta');

            foreach($metaTags as $metaTag) {
                $name = strtolower($metaTag->getAttribute('name'));
                if ($name == 'description') {
                    $description = $metaTag->getAttribute('content');
                    break;
                }
            }
        }
        
        return array(
            'title'=>$title,
            'description'=>$description,
            'invalid'=>$invalid
        );
    }
}