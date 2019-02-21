<div class="card-search">
    <h2 class="card-search__label">
        <a class="card-search__link" href="<?php the_permalink() ?>">
            <?php the_title() ?>
        </a>
    </h2>
    <?php the_post_thumbnail('medium', [
        'class' => 'card-search__thumb',
        'sizes' => '',
    ]) ?>
    <time class="card-search__date" datetime="<?php echo get_the_date('Y-m-d') ?>">
        <?php echo get_the_date('d M Y') ?>
    </time>
    <p class="card-search__excerpt">
        <span class="visually-hidden"><?php _e('Extrait :', CardinalTheme::TEXTDOMAIN) ?></span>
        <?php the_excerpt() ?>
    </p>
    <p class="card-search__more" aria-hidden="true">
        <a class="card-search__more-link" href="<?php the_permalink() ?>">
            <?php _e('Voir plus', CardinalTheme::TEXTDOMAIN) ?>
        </a>
    </p>
</div>