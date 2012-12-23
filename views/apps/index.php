
<div class="searchresults line">
    <?foreach($view->rows as $app):?>
    <div class="result">
        <section class="app" style="background-color: <?=$app->bgcolor?>;color:<?=$app->color?>;" >
            <? if($app->logo): ?>
                <img class="logo" src="<?=$app->logo?>" alt="<?=$app->name?>" />
            <? endif;?>
            <h3>
                <a href="<?=UI::url('apps','view',array('id'=>$app->id))?>">
                    <?=$app->name?>
                </a>        
            </h3>
            <p><?=$app->description?></p>
        </section>
    </div>
    <?endforeach;?>
</div>



<? UI::renderAt('bottom',function() { ?>
<script type="text/javascript">
    $(function() {
        $('.app').click(function(evt) {
           top.location = $(this).find('a').attr('href'); 
        });
    });
</script>
<? }); ?>
