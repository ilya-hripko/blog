<h1 class="title">
    <?php echo $h1 ?>
</h1>
<div class="social-block">
    <a class="btn btn-lg btn-default" href="https://www.facebook.com/sharer/sharer.php?u=<?=urlencode($this_url)?>&t=<?=urlencode($h1)?>" ><i class="fa fa-facebook"> </i></a>
    <a class="btn btn-lg btn-default" href="https://plus.google.com/share?url=<?=urlencode($this_url)?>" ><i class="fa fa-google-plus"> </i></a>
    <a class="btn btn-lg btn-default" href="https://twitter.com/share?url=<?=urlencode($this_url)?>&text=<?=urlencode($h1)?>" ><i class="fa fa-twitter"> </i></a>
</div>
<div class="page-text">
<?php echo $text_html?>
</div>