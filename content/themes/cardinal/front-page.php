<?php /* Template name: Accueil */ ?>
<?php $theme = CardinalTheme::get_instance() ?>
<?php get_header() ?>
<?php the_post() ?>

<?php $theme->front_actu() ?>
<?php if (have_posts()): ?>
    <?php the_post() ?>
    <div class="front-actu has-background">
        <?php the_post_thumbnail('large', [
            'class'           => 'front-actu__thumb has-background__image',
            'data-object-fit' => 'cover',
            'sizes'           => '100vw',
            'style'           => 'object-position: 70% 26%',
        ]) ?>
        <div class="front-actu__inner has-background__inner container">
            <h2 class="visually-hidden">
                <?php _e('Actualité du moment', CardinalTheme::TEXTDOMAIN) ?>
            </h2>
            <div class="front-actu__content">
                <h2 class="front-actu__title">
                    <?php the_title() ?>
                </h2>
                <p class="front-actu__excerpt">
                    <span class="visually-hidden"><?php _e('Extrait :', CardinalTheme::TEXTDOMAIN) ?></span>
                    <?php the_excerpt() ?>
                </p>
                <p class="front-actu__more">
                    <a href="<?php the_permalink() ?>"
                       aria-label="<?php _e('Lire l’actualité du moment', CardinalTheme::TEXTDOMAIN) ?>"
                       class="front-actu__link cta">
                        <?php _e('Voir plus', CardinalTheme::TEXTDOMAIN) ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
<?php endif ?>
<?php wp_reset_query() ?>

    <div class="front-content">
        <div class="front-content__inner content container">
            <?php the_content() ?>
        </div>
    </div>

<?php get_footer() ?>