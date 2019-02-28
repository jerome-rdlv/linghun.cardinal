<?php $theme = CardinalTheme::get_instance() ?>
<div class="list-search">
    <?php while (have_posts()): the_post() ?>
        <div class="list-search__item">
            <?php get_template_part('partial/index-item', 'search') ?>
        </div>
    <?php endwhile ?>
</div>