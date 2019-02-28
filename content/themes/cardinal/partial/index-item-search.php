<div class="card-search">
    <div class="card-search__inner">
        <h2 class="card-search__title">
            <?php the_title() ?>
        </h2>
        <time class="card-search__date visually-hidden"
              datetime="<?php echo get_the_date('Y-m-d') ?>">
            <?php echo get_the_date('d M Y') ?>
        </time>
        <p class="card-search__excerpt">
            <span class="visually-hidden"><?php _e('Extrait :', CardinalTheme::TEXTDOMAIN) ?></span>
            <?php the_excerpt() ?>
        </p>
        <p class="card-search__more" aria-hidden="true">
            <a class="card-search__link cta" href="<?php the_permalink() ?>">
            <span aria-hidden="true">
                <?php _e('Voir plus', CardinalTheme::TEXTDOMAIN) ?>
            </span>
                <span class="visually-hidden print-off">
                - <?php the_title() ?>
            </span>
            </a>
        </p>
    </div>
    <div class="card-search__thumb">
        <?php the_post_thumbnail('medium', [
            'class' => 'card-search__image',
            'sizes' => '(min-width: 780px) 26.5rem, (min-width: 600px) 40vw, 30rem',
        ]) ?>
    </div>
</div>