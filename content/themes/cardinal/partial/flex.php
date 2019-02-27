<?php if (have_rows('blocks')): ?>
    <div class="blocks">
        <?php while (have_rows('blocks')): the_row() ?>
            <?php get_template_part('partial/flex-' . get_row_layout()) ?>
        <?php endwhile ?>
    </div>
<?php endif ?>
