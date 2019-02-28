<div class="card">
    <div class="card__inner">
        <h2 class="card__title">
            <?php the_title() ?>
        </h2>
        <time class="index__date visually-hidden"
              datetime="<?php echo get_the_date('Y-m-d') ?>">
            <?php echo get_the_date('d M Y') ?>
        </time>
        <p class="card__excerpt">
            <span class="visually-hidden"><?php _e('Extrait :', CardinalTheme::TEXTDOMAIN) ?></span>
            <?php the_excerpt() ?>
        </p>
        <p class="card__more" aria-hidden="true">
            <a class="card__link" href="<?php the_permalink() ?>">
                <span aria-hidden="true">
                    <?php _e('Voir plus', CardinalTheme::TEXTDOMAIN) ?>
                </span>
                <span class="visually-hidden print-off">
                    -
                    <?php the_title() ?>
                </span>
            </a>
        </p>
    </div>
    <?php the_post_thumbnail('medium', [
        'class'           => 'card__thumb',
        'data-object-fit' => 'cover',
        'sizes'           => '(min-width: 1170px) 55.5rem, (min-width: 780px) 50, 100vw',
    ]) ?>
</div>
