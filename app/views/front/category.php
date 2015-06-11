<div class="content-block">
    <h1 class="title"><?php echo $h1 ?></h1>
    <div class="social-block">
        <a class="btn btn-lg btn-default" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($this_url) ?>&t=<?= urlencode($h1) ?>" ><i class="fa fa-facebook"> </i></a>
        <a class="btn btn-lg btn-default" href="https://plus.google.com/share?url=<?= urlencode($this_url) ?>" ><i class="fa fa-google-plus"> </i></a>
        <a class="btn btn-lg btn-default" href="https://twitter.com/share?url=<?= urlencode($this_url) ?>&text=<?= urlencode($h1) ?>" ><i class="fa fa-twitter"> </i></a>
    </div>
    <div class="text">
        <?php echo $text_html; ?>
    </div>
    <div class="clearfix"></div>
</div>

<?php if (isset($posts) && !empty($posts)): ?>
    <?php foreach ($posts as $P): ?>
        <div class="content-block">

            <h2 class="block-title carriers-list-title"><a href="<?=  generate_url('blogPost', ['title'=>$P->title, 'id'=>$P->id], $base_url)?>"><?php echo trim($P->title) ?></a></h2>

            <div class="posts-list-item">
                <div class="posts-rating">
                    <input type="hidden" name="val" value="<?= $P->rating_score ?>"/>
                    <input type="hidden" name="votes" value="<?= $P->rating_count ?>"/>
                    <input type="hidden" name="post_id" value="<?= $P->id ?>"/>
                </div>
                <div class="posts-list-item-crop-post"><?php echo trim($P->post_crop) ?></div>
                <div class="posts-list-item-author"><b>Author:</b> <?php echo trim($P->author) ?> :: Posted at <?php echo date($this->config->item('date_format_full'), $P->created_at)?></div>        
            </div>    


        </div>
    <?php endforeach; ?>
<?php endif; ?>
