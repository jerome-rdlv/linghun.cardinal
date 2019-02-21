<?php $prev = get_adjacent_post() ?>
<?php $next = get_adjacent_post(false, '', false) ?>
<?php $theme = CardinalTheme::get_instance() ?>
<p class="pager print-off">
    <?php if ($next): ?>
        <a href="<?php the_permalink($next->ID) ?>"
           class="pager__link pager__link--prev">
            <?php echo $theme->get_symbol('arrow-prev', [
                'class' => 'pager__arrow',
            ]) ?>
            <span class="visually-hidden"><?php _e('Article précédent : ', CardinalTheme::TEXTDOMAIN) ?></span>
            <span class="pager__label">
                <?php echo wp_trim_words($next->post_title, 6, '…') ?>
            </span>
        </a>
    <?php endif ?>
    <?php if ($prev && $next): ?><span class="visually-hidden"> - </span><?php endif ?>
    <?php if ($prev): ?>
        <a href="<?php the_permalink($prev->ID) ?>"
           class="pager__link pager__link--next">
            <span class="visually-hidden"><?php _e('Article suivant : ', CardinalTheme::TEXTDOMAIN) ?></span>
            <span class="pager__label">
                <?php echo wp_trim_words($prev->post_title, 6, '…') ?>
            </span>
            <?php echo $theme->get_symbol('arrow-next', [
                'class' => 'pager__arrow',
            ]) ?>
        </a>
    <?php endif ?>
</p>