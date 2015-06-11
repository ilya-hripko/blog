<!DOCTYPE html>
<html lang="en">
    <head>    
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">   
        <title><?php echo $title; ?></title>
        <link rel="shortcut icon" href="<?php echo $base_url . 'images/favicon.png' ?>" type="image/png">
        <?php if (isset($description) && !empty($description)): ?>
            <meta name="description" content="<?= $description ?>" />
        <?php endif; ?>
        <?php if (isset($keywords) && !empty($keywords)): ?>
            <meta name="keywords" content="<?= $keywords ?>" />
        <?php endif; ?>

        <?php if (isset($prev_link) && $prev_link): ?>
            <link rel="prev" href="<?= $prev_link ?>" />
        <?php endif; ?>
        <?php if (isset($next_link) && $next_link): ?>
            <link rel="next" href="<?= $next_link ?>" />
        <?php endif; ?>   
        <link href="<?= $base_url ?>assets/bootstrap/css/bootstrap.min.css?ver=<?= $this->config->item('assets_version') ?>" rel="stylesheet">
        <link href="<?= $base_url ?>assets/font-awesome/font-awesome.min.css?ver=<?= $this->config->item('assets_version') ?>" rel="stylesheet">
        <link href="<?= $base_url ?>assets/datepicker/css/bootstrap-datepicker.min.css?ver=<?= $this->config->item('assets_version') ?>" rel="stylesheet">
        <link href="<?= $base_url ?>assets/rating/styles/jquery.rating.css?ver=<?= $this->config->item('assets_version') ?>" rel="stylesheet">
        <link href="<?= $base_url ?>assets/css/front.css?ver=<?= $this->config->item('assets_version') ?>" rel="stylesheet">
        <script src="<?= $base_url ?>assets/js/jquery.js?ver=1"></script>
        <script src="<?= $base_url ?>assets/bxslider/jquery.bxslider.min.js"></script>
        <script src="<?= $base_url ?>assets/rating/js/jquery.rating-2.0.min.js?ver=1"></script>
        <script src="<?= $base_url ?>assets/bootstrap/js/bootstrap.min.js?ver=<?= $this->config->item('assets_version') ?>"></script>
        <script src="<?= $base_url ?>assets/datepicker/js/bootstrap-datepicker.js?ver=<?= $this->config->item('assets_version') ?>"></script>
    </head>

    <body  class="<?= isset($page) ? $page : '' ?>">
        <div class="container">
            <header id="header">
                <div class="row-logo clearfix">
                    <div class="logo">
                        <h2><a id="logo" href="<?= $base_url ?>"><span class="first-char">Test</span>blog.com</a></h2>
                        <p class="tagline">Making your blog fast and safe</p>
                    </div>
                    <nav class="primary">
                        <ul class="sf-menu sf-js-enabled  hidden-sm hidden-xs" id="topnav">
                            <?php foreach ($top_menu as $i => $TM): ?>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item <?php echo $TM->url == '' ? 'hidden-md' : '' ?> <?php if (isset($page_id) && $page_id == $TM->id): ?>current-menu-item<?php endif; ?>">
                                    <a href="<?php echo base_url() . ($TM->url == '' ? '' : $TM->url . '/') ?>"><?php echo $TM->name ?></a>
                                    <span class="bg-menu"></span>
                                </li>
                            <?php endforeach; ?>
                            <?php if($this->user_id):?>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item">
                                    <a href="<?php echo base_url()?>logout">Logout</a>
                                    <span class="bg-menu"></span>
                                </li>
                            <?php else:?>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item">
                                    <a href="<?php echo base_url()?>login">Login</a>
                                    <span class="bg-menu"></span>
                                </li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page page_item">
                                    <a href="<?php echo base_url()?>signup">Sign up</a>
                                    <span class="bg-menu"></span>
                                </li>
                            <?php endif;?>
                        </ul>
                        <select class="select-menu visible-xs visible-sm">
                            <option value="#">Navigate to...</option>
                            <?php foreach ($top_menu as $i => $TM): ?>
                                <option value="<?php echo base_url() . ($TM->url == '' ? '' : $TM->url . '/') ?>" <?php if (isset($page_id) && $page_id == $TM->id): ?>selected="selected"<?php endif; ?>>&nbsp;<?php echo $TM->name ?></option>
                            <?php endforeach; ?>
                        </select>				
                    </nav><!--.primary-->
                </div>
            </header>


            <?php
            include APPPATH . 'views/front/parts/breadcrumbs.php';
            ?>

            <?php if ($page != 'page_contacts'): ?>

                <div class="row">
                    <div class="col-lg-9 col-md-9">
                        <?php if (!in_array($page, ['post'])): ?>
                            <!-- CONTENT -->
                            <div class="content-block">
                                <?php echo $html ?>
                            </div>
                            <!--/CONTENT -->
                        <?php else: ?>
                            <?php echo $html ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-3 col-md-3">
                        <!-- RIGHT MENU -->
                        <?php if($this->user_id):?>
                            <div class="content-block">
                                <div class="block-title"> Hello, <?=$this->user_name?> </div>
                                <ul class="popular-directions">
                                    <li><a class="btn-add-post" href="#">Add Post</a></li>
                                    <li><a class="btn-add-category" href="#">Add Category</a></li>
                                </ul>
                            </div>
                        <?php endif;?>
                        <?php if (isset($blog_categories)): ?>
                            <div class="content-block">
                                <div class="block-title"> Categories </div>
                                <ul class="popular-directions">
                                    <?php foreach ($blog_categories as $BC): ?>
                                        <li>
                                            <a href="<?php echo generate_url('blogCategory', array('name' => $BC->name, 'id' => $BC->id), $base_url) ?>">
                                                <?= $BC->name ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif ?>

                        <!--/RIGTH MENU -->
                    </div>
                </div>
            <?php else: ?>
                <div class="content-block">
                    <?php echo $html ?>
                </div>
            <?php endif; ?>
            <div class="footer">
                <!-- FOOTER -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="footer-text">
                            <p>
                                2015 &copy; Test Blog
                            </p>
                        </div>
                    </div>
                </div>
                <!--/FOOTER -->
            </div>
        </div>

        <script src="<?= $base_url ?>assets/js/jquery.validate.js"></script>
        <script src="<?= $base_url ?>assets/js/testblog/core.js?ver=<?= $this->config->item('assets_version') ?>"></script>
        <script src="<?= $base_url ?>assets/js/testblog/auth.js?ver=<?= $this->config->item('assets_version') ?>"></script>
        <script src="<?= $base_url ?>assets/js/testblog/front.js?ver=<?= $this->config->item('assets_version') ?>"></script>
        <?php if($this->user_id):?>
        <script src="<?= $base_url ?>assets/js/testblog/blog.js?ver=<?= $this->config->item('assets_version') ?>"></script>
        <?php
        include APPPATH . 'views/front/parts/user_modals.php';
        ?>
        <?php endif;?>
        <script type="text/javascript">
            TB.feature('config', {
                base_url: '<?= $base_url ?>',
                page: '<?= $page ?>'
            });
            TB.front.init();
            TB.blog.init();
        </script>
    </body>
</html>    