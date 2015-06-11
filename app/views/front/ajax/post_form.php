<form class="form-horizontal">
    <?php if(isset($post)):?>
    <input type="hidden" name="post_id" value="<?=$post->id?>" />
    <?php endif;?>
    <div class="form-group">
        <label class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
            <input type="text" class="form-control required" placeholder="Title" name="title" value="<?=isset($post) ? $post->title : ''?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Category</label>
        <div class="col-sm-10">
            <select class="form-control" name="category_id">
                <?php foreach($categories as $C):?>
                <option value="<?=$C->id?>"<?php if(isset($post)&&$post->category_id == $C->id):?> selected="selected"<?php endif;?>><?=$C->name?></option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">SEO title</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="SEO title" name="seo_title" value="<?=isset($post) ? $post->seo_title : ''?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">SEO description</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="SEO description" name="seo_description" value="<?=isset($post) ? $post->seo_description : ''?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">SEO keywords</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="SEO keywords" name="seo_keywords" value="<?=isset($post) ? $post->seo_keywords : ''?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Post Crop</label>
        <div class="col-sm-10">
            <textarea class="form-control required" name="post_crop"><?=isset($post) ? $post->post_crop : ''?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Post</label>
        <div class="col-sm-10">
            <textarea class="form-control required" name="post"><?=isset($post) ? $post->post : ''?></textarea>
        </div>
    </div>
</form>