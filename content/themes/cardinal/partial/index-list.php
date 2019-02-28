<?php $theme = CardinalTheme::get_instance() ?>
<?php $template_name = $theme->get_template_name() ?>
<div class="index__list">
    <?php while (have_posts()): the_post() ?>
        <div class="index__item">
            <?php get_template_part('partial/index-item', $template_name) ?>
        </div>
    <?php endwhile ?>
</div>