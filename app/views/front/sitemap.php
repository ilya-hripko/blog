<h1 class="title">Sitemap</h1>
<div class="page-text">
<?php if(isset($directions)&&!empty($directions)):?>
<ul>
    <?php if(!isset($prev_page)):?>
    <?php foreach ($top_menu as $TM): ?>
        <li><a href="<?php echo base_url() . ($TM->url == '' ? '' : $TM->url . '/') ?>"><?php echo $TM->name ?></a></li>
    <?php endforeach; ?>
    <?php endif;?>
    <?php foreach($directions as $D):?>
    <li>
        <a href="<?=  generate_url($D->from_type==0 ? 'movingTo' : 'movingFromTo', ['to'=>$D->to_name, 'from'=>$D->from_name, 'id'=>$D->id], $base_url)?>">
            <?php if($D->from_type==0):?>
            Moving to <?=$D->to_name?>
            <?php else:?>
            Moving from <?=$D->from_name?> to <?=$D->to_name?>
            <?php endif;?>
        </a>
    </li>
    <?php endforeach;?>
</ul>
<?php endif; ?>
    <div>
        <?php if(isset($prev_page)):?>
        <a href="<?=$base_url?>sitemap.html?page=<?=$prev_page?>" class="btn btn-default"><< Prev</a>
        <?php endif;?>
        <?php if(isset($next_page)):?>
        <a href="<?=$base_url?>sitemap.html?page=<?=$next_page?>" class="btn btn-default">Next >></a>
        <?php endif;?>
    </div>
    <br/>
</div>
