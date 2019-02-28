<?php $theme = CardinalTheme::get_instance() ?>
<ul class="list-real" data-masonry='{ "itemSelector": ".list-real__item" }'>
    <?php while (have_posts()): the_post() ?>
        <li class="list-real__item">
            <?php get_template_part('partial/index-item', 'real') ?>
        </li>
    <?php endwhile ?>
</ul>