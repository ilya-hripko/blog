<div class="content-block">
    <h1 class="title"><?php echo $h1 ?></h1>
    <div class="social-block">
        <a class="btn btn-lg btn-default" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($this_url) ?>&t=<?= urlencode($h1) ?>" ><i class="fa fa-facebook"> </i></a>
        <a class="btn btn-lg btn-default" href="https://plus.google.com/share?url=<?= urlencode($this_url) ?>" ><i class="fa fa-google-plus"> </i></a>
        <a class="btn btn-lg btn-default" href="https://twitter.com/share?url=<?= urlencode($this_url) ?>&text=<?= urlencode($h1) ?>" ><i class="fa fa-twitter"> </i></a>
    </div>
    <?php if($this->user_id):?>
    <a class="btn btn-success btn-add-post" href="#" post_id="<?=$post->id?>">Edit Post</a>
    <?php if($this->user_id==$post->user_id):?>
    <a class="btn btn-danger btn-delete-post" href="#" post_id="<?=$post->id?>">Delete Post</a>
    <?php endif; ?>
    <br/><br/>
    
    <?php endif;?>
    <div class="posts-rating">
        <input type="hidden" name="val" value="<?= $post->rating_score ?>"/>
        <input type="hidden" name="votes" value="<?= $post->rating_count ?>"/>
        <input type="hidden" name="post_id" value="<?= $post->id ?>"/>
    </div>
    <div class="text">
        <?php echo $text_html; ?>
    </div>
    <div class="posts-author"><b>Author:</b> <?php echo $author ?> :: Posted at <?php echo date($this->config->item('date_format_full'), $created_at)?></div> 
</div>
