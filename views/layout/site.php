<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=$view->title?></title>
        
        <meta charset="utf-8">
        <meta name="description" content="<?=$view->description?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/bootstrap.tagmanager.css" rel="stylesheet" media="screen">
        <link href="css/site.css" rel="stylesheet" media="screen" />
        <link href="css/responsive.css" rel="stylesheet" media="screen" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
        <meta name="robots" content="<?=UI::pageRobots()?>" />
        <script type="text/javascript" src="http://www.websnapr.com/js/websnapr.js"></script>
    </head>
    <body>
        <header>
            <div class="title" onclick="top.location = './';">
                <h1>SaaSix</h1>
                <em>The cloud application index</em>
            </div>
            
            
            <ul class="nav nav-pills ">
                <li class="<?=UI::activeClass('apps', '')?>">
                    <a href="<?=UI::url('apps','')?>" >Apps</a>
                </li>
                <li rel="nofollow" class="<?=UI::activeClass('apps', 'add')?>">
                    <a href="<?=UI::url('apps','add')?>" >Add application</a>
                </li>
                <li class="<?=UI::activeClass('info', 'about')?>">
                    <a href="<?=UI::url('info','about')?>" >About</a>
                </li>
                <li class="search">
                    <form>
                        <input type="hidden" name="c" value="apps" />
                        <input type="text" name="term" value="<?=$_GET['term']?>" class="search-query" placeholder="Search">
                    </form>
                </li>
            </ul>
            
        </header>
        
        <div class="content line">
            <?=$view->content?>
        </div>
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap.tagmanager.js"></script>
        <script src="js/init.js"></script>
        
        <?=UI::location('bottom')?>
    </body>
</html>
