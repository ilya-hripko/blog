<?php if (isset($page) && in_array($page, ['page', 'post', 'category'])): ?>
    <ul class="breadcrumb">
        <li><a href="<?= $base_url; ?>">Test Blog</a></li>
        <?php if ($page == 'page'): ?>
            <li><b><?= $name ?></b></li>
        <?php endif; ?>
    </ul>
<?php endif; ?>