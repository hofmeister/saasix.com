<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $view->title ?></title>

        <meta charset="utf-8">
        <meta name="description" content="<?= $view->description ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
        <meta name="apple-touch-fullscreen" content="YES" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" /> 
        <meta name="robots" content="<?= UI::pageRobots() ?>" />
        <meta name="google-site-verification" content="pWXCVVSsYSHvHXNSVboxWMQ6J44pi8IKSNJZTsZz1H4" />

        <link rel="shortcut icon" href="favicon.png" /> 
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/bootstrap.tagmanager.css" rel="stylesheet" media="screen">
        <link href="css/site.css" rel="stylesheet" media="screen" />
        <link href="css/responsive.css" rel="stylesheet" media="screen" />
        <link rel="apple-touch-icon-precomposed" href="img/saasix_ios_logo.png"/>

        <script type="text/javascript" src="http://www.websnapr.com/js/websnapr.js"></script>
    </head>
    <body>
        <header>
            <div class="title line" onclick="top.location = './';">
                <img src="img/saasix_logo.png" alt="SaaSix" />
                <h1>SaaSix</h1>
                <em>The cloud application index</em>
            </div>
            <div class="line"></div>

            <ul class="nav nav-pills ">
                <li class="<?= UI::activeClass('apps', '') ?>">
                    <a href="<?= UI::url('apps', '') ?>" >Apps</a>
                </li>
                <li rel="nofollow" class="<?= UI::activeClass('apps', 'add') ?>">
                    <a href="<?= UI::url('apps', 'add') ?>" >Add application</a>
                </li>
                <li class="<?= UI::activeClass('info', 'about') ?>">
                    <a href="<?= UI::url('info', 'about') ?>" >About</a>
                </li>
                <li class="search">
                    <form>
                        <input type="hidden" name="c" value="apps" />
                        <input type="text" name="term" value="<?= $_GET['term'] ?>" class="search-query" placeholder="Search">
                    </form>
                </li>
            </ul>

        </header>

        <div class="content line">
            <?= $view->content ?>
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap.tagmanager.js"></script>
        <script src="js/init.js"></script>

        <?= UI::location('bottom') ?>

        <!-- Google Analytics -->
        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-8753732-6']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
        
        <!-- GoSquared -->
        <script type="text/javascript">
            var GoSquared = {};
            GoSquared.acct = "GSN-369329-T";
            (function(w){
                function gs(){
                    w._gstc_lt = +new Date;
                    var d = document, g = d.createElement("script");
                    g.type = "text/javascript";
                    g.src = "//d1l6p2sc9645hc.cloudfront.net/tracker.js";
                    var s = d.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(g, s);
                }
                w.addEventListener ?
                    w.addEventListener("load", gs, false) :
                    w.attachEvent("onload", gs);
            })(window);
        </script>
    </body>
</html>
