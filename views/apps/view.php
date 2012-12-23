<?
$app = $view->app;
$app->name = htmlentities($app->name);
$comments = $view->comments;
$parms = array('id'=>$app->id);

UI::pageTitle($app->name);
UI::pageDescription($app->description);

?>
<article class="apppage line">
    <div class="line">
        <div class="btn-group pull-right">
            <a id="btn_open" class="btn btn-success" href="<?=$app->url?>" target="_blank">
                Open
            </a>
            <a rel="nofollow" class="btn" href="<?=UI::url('apps', 'edit',$parms)?>">
                Edit
            </a>
            <a rel="nofollow" class="btn btn-danger" href="#" onclick="confirm('Are you sure you want to delete this app?') ? top.location = '<?=UI::url('apps', 'delete',$parms)?>' : ''; return false;">
                Delete
            </a>
        </div>
    </div>
    

    
    <summary class="line" style="background-color: <?=$app->bgcolor?>;color:<?=$app->color?>;">
        <? if($app->logo): ?>
            <img class="logo" src="<?=cloudinary_url("$app->id.png", array("width" => 200, "height" => 40, "crop" => "fit"))?>" alt="<?=$app->name?>" />
        <? endif;?>
            
        <div class="screenshot">
            <script type="text/javascript">wsr_snapshot('<?=$app->url?>', '4N54zs5I40q2', 's');</script>
        </div>
        <div class="text">
            <h2><?=$app->name?></h2>
            <p>
                <?=$app->description?>
            </p>
        </div>
    </summary>
    
    
    
    <ul class="tags">
        <? if ($app->tags):
                $tags = explode(',',$app->tags);
                foreach($tags as $tag) {
                ?>
                    <li class="tag alert alert-info">
                        <a href="?c=apps&term=<?=$tag?>" > <?=$tag?></a>
                    </li>
                <?
                }
            endif;
        ?>
    </ul>
    
    
    <ul class="comments">
        <?foreach($comments as $comment):?>
        <li>
            <span class="author"><?=$comment->name?></span> 
            <span class="message"><?=  nl2br(htmlentities($comment->comment))?></span>
            <time>
                <?=UI::timeSince($comment->created);?>
            </time>
        </li>
        <?endforeach;?>
        <li class="new">
            <form method="post">
                <div>
                    <input required="true" type="text" name="name" value="<?=$_SESSION['nickname']?>" placeholder="Your name" />
                </div>
                <div>
                    <textarea id="comment_text" required="true" name="comment" placeholder="Write a comment"></textarea>
                </div>
                <button id="comment_submit" style="visibility: hidden;position: absolute;left:-9999px;"></button>
            </form>
        </li>
    </ul>
</article>

<? UI::renderAt('bottom',function() { ?>
<script type="text/javascript">
    $(function() {
        $('#comment_text')
            .focus()
            .bind('keydown',function(evt) {
                if (evt.keyCode == 13 && !evt.shiftKey) {
                    evt.preventDefault();
                    $('#comment_submit').click();
                }
            });
            
        $('.screenshot').click(function(evt) {
            evt.preventDefault();
            var url = $('#btn_open').attr('href');
            window.open(url);
        });
    });
</script>
<? }); ?>