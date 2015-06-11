<div class="comments-form content-block">
    <div class="block-title">Add Comment</div>
    <form action="<?=$base_url?>ajax/add_comment" method="post" class="testimonials-form">
        <?php if($page=='post'):?>
        <input type="hidden" name="type" value="post"/>
        <input type="hidden" name="id" value="<?=$post_id?>"/>
        <?php else:?>
        <input type="hidden" name="type" value="page"/>
        <input type="hidden" name="id" value="<?=$page_id?>"/>
        <?php endif;?>
        <div class="form-group">
            <label>Name</label>
            <input type="text" placeholder="Enter your name" name="name" class="form-control" />
        </div>
        <div class="form-group">
            <label>Comment</label>
            <textarea name="testimonial" class="form-control" <?php if($page != 'page'):?>style="height:200px"<?php endif;?>></textarea>
        </div>
        <button type="submit" class="btn btn-red">Add Comment</button>
    </form>
</div>