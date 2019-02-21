<div class="card">
    <h2 class="card__title">
        <a class="card__link" href="<?php the_permalink() ?>">
            <?php the_title() ?>
        </a>
    </h2>
    <?php the_post_thumbnail('medium', [
        'class'           => 'card__thumb',
        'data-object-fit' => 'cover',
        'sizes'           => '',
    ]) ?>
    <time class="index__date" datetime="<?php echo get_the_date('Y-m-d') ?>">
        <?php echo get_the_date('d M Y') ?>
    </time>
    <p class="index__excerpt">
        <span class="visually-hidden"><?php _e('Extrait :', CardinalTheme::TEXTDOMAIN) ?></span>
        <?php the_excerpt() ?>
    </p>
    <p class="card__more" aria-hidden="true">
        <a class="card__more-link" href="<?php the_permalink() ?>">
            <?php _e('Voir plus', CardinalTheme::TEXTDOMAIN) ?>
        </a>
    </p>
</div>
