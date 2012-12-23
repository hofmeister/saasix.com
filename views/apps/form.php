<?
$app = $view->app;
if (!$app)
    $app = new stdClass();

UI::pageRobots('noindex');
?>

<form class="appform form-horizontal" method="POST">
    <fieldset>
        <legend><?=$title?></legend>
        <!-- URL -->
        <div class="control-group">
            <label class="control-label" for="app_url">Website</label>
            <div class="controls">
                <input type="text" name="url" id="app_url" required="true" value="<?=$app->url?>" placeholder="http://">
            </div>
        </div>
        
        <!-- Name -->
        <div class="control-group">
            <label class="control-label" for="app_name">Name</label>
            <div class="controls">
                <input type="text" name="name" id="app_name" required="true" value="<?=$app->name?>" placeholder="Application name">
            </div>
        </div>
        
        <!-- Image -->
        <div class="control-group">
            <label class="control-label" for="app_logo">Logo</label>
            
            <div class="controls">
                <img id="logo_preview" style="display:none;margin-left:10px;max-height:25px;max-width:160px;margin-bottom:5px;" src="about:none;" alt="Logo" />
                <input type="text" name="logo" id="app_logo" required="true" value="<?=$app->logo?>" placeholder="URL to logo">
            </div>
        </div>
        
        <!-- Description -->
        <div class="control-group">
            <label class="control-label" for="app_description">Description</label>
            <div class="controls">
                <textarea resizable="false" name="description" required="true" id="app_description" placeholder="Add description for the application" rows="4" cols="25"><?=$app->description?></textarea>
            </div>
        </div>
        <!-- Type  -->
        <div class="control-group">
            <label class="control-label" for="app_type">Type</label>
            <div class="controls">
                <select id="app_type" name="typeId">
                    <?=UI::options($view->types, $app->typeId)?>
                </select>
            </div>
        </div>

        <!-- Price -->
        <div class="control-group">
            <label class="control-label">Free</label>
            <div class="controls">
                <label class="checkbox ">
                    <input type="checkbox" name="price" value="FREE" <?=$app->price == 'FREE' ? 'checked="checked"':''?>>
                    Yes
                </label>
            </div>
        </div>
        
        <!-- BG Color -->
        <div class="control-group">
            <label class="control-label" for="app_bgcolor">Background</label>
            <div class="controls">
                <input type="text" name="bgcolor" id="app_bgcolor" value="<?=$app->bgcolor?>" placeholder="#FFFFFF">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="app_color">Foreground</label>
            <div class="controls">
                <input type="text" name="color" id="app_color" value="<?=$app->color?>" placeholder="#000000">
            </div>
        </div>

        <!-- Tags -->
        <div class="control-group">
            <label class="control-label">Tags</label>
            <div class="controls tagcontrol">
                <input type="text" name="tags" id="app_tags" value="<?=$app->tags?>" />
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>    
            </div>
        </div>
        
    </fieldset>
</form>

<? UI::renderAt('bottom',function() { ?>
<script type="text/javascript">
    $(function() {
        $('#app_tags').tagsManager({
            source: 'ajax.php<?=UI::url('apps', 'listtags')?>'
        });
        
        $('#app_url').blur(function() {
            var url = ""+$(this).val();
            if (!url) return;
            
            if (!/https?:\/\//i.test(url)) {
                url = 'http://'+url;
                $(this).val(url);
            }
            
            if ($('#app_name').val()) {
                return;
            }
            
            $('#app_name').val('Please wait...')
            
            var allInput =  $('.appform input,.appform textarea,.appform select');
            allInput.attr('disabled',true).addClass('disabled');
            
            $.getJSON('ajax.php<?=UI::url('apps', 'lookupUrl')?>&url='+url,function(result) {
                allInput.removeAttr('disabled').removeClass('disabled');
                $('#app_name').val('');
                
                if (result) {
                    if (result.invalid) {
                        alert("Seems that the URL you've provided leads nowhere?");
                        return;
                    }
                    
                    if (result.title) {
                        var title = result.title.replace(/(^[^a-z0-9\s]+|[^a-z0-9\s]+$)/i,'');
                        title = title.split(/[^a-z0-9\s]/i);
                        $('#app_name').val(title[0]);
                    } else {
                        $('#app_name').focus();
                    }
                    if (result.description) {
                        $('#app_description').val(result.description);
                    } else if (result.title) {
                        $('#app_description').focus();
                    }
                    
                    if (result.title 
                            && result.description) {
                        $('ul.apps input').focus();
                    }
                    
                }
            });
        });
        $('#app_url').focus();
        
        $('#app_logo').blur(function() {
            var url = ""+$(this).val();
            if (!url) return;
            
            var img = $('#logo_preview');
            
            if (!/https?:\/\//i.test(url)) {
                url = 'http://'+url;
                $(this).val(url);
            }
            
            var oldUrl = img.attr('src');
            if (oldUrl == url) {
                return;
            }
            img.hide();
            
            img.attr('src',url);
        });
        
        $('#logo_preview').bind('load',function() {
            $(this).css('display','block');
        });
        
        $('#app_logo').blur();
    });
</script>
<? }); ?>