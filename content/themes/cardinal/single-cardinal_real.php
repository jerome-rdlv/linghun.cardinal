<?php $theme = CardinalTheme::get_instance() ?>
<?php $theme->add_symbol('arrow-prev') ?>
<?php $theme->add_symbol('arrow-next') ?>
<?php get_header() ?>
<?php the_post() ?>

<?php get_template_part('partial/splash') ?>

    <div class="single-real container">

        <div class="single-real__breadcrumbs">
            <p class="visually-hidden">
                <?php _e('Fil d’Ariane :', CardinalTheme::TEXTDOMAIN) ?>
            </p>
            <ul class="link-list">
                <li class="link-list__item">
                    <?php $theme->archive_page() ?>
                    <a href="<?php the_permalink() ?>">
                        <?php the_title() ?>
                    </a>
                    <span aria-hidden="true">&gt;</span>
                    <?php wp_reset_query() ?>
                </li>

                <?php $cats = get_the_terms(false, 'cardinal_real_cat') ?>
                <?php if ($cats): ?>
                    <li class="link-list__item">
                        <a href="<?php echo get_term_link($cats[0]) ?>">
                            <?php echo $cats[0]->name ?>
                        </a>
                        <span aria-hidden="true">&gt;</span>
                    </li>
                <?php endif ?>
                <li class="link-list__item link-list__item--current">
                <span>
                    <?php the_title() ?>
                </span>
                </li>
            </ul>
        </div>

        <div class="single-real__inner">

            <div class="single-real__intro">
                <h1 class="single-real__title">
                    <?php the_title() ?>
                </h1>

                <p class="single-real__place">
                    <?php the_field('place') ?>
                </p>

                <time class="visually-hidden" datetime="<?php echo get_the_date('Y-m-d') ?>">
                    <?php printf(__('Publié le %s', CardinalTheme::TEXTDOMAIN), get_the_date('d M Y')) ?>
                </time>

                <div class="single-real__content">
                    <div class="single-real__desc content">
                        <?php echo apply_filters('the_content', get_field('desc')) ?>
                    </div>
                    <div class="single-real__tech content">
                        <?php the_field('tech') ?>
                    </div>
                </div>
                <?php $img1 = get_field('img_1', false, false) ?>
                <?php if ($img1) echo wp_get_attachment_image($img1, 'medium', false, [
                    'class' => 'single-real__intro-img',
                    'sizes' => '(min-width: 1170px) 46rem, (min-width: 900px) 42vw, (min-width: 600px) 50vw, 100vw',
                ]) ?>
            </div>

            <?php $mode = get_field('mode') ?>
            <?php if ($mode === 'single' || $mode === 'duo'): ?>
                <?php $img2 = get_field('img_duo_2', false, false) ?>
                <div class="single-real__single single-real__single--<?php echo $img2 ? 'img' : 'logo' ?>">
                    <?php if ($img2): ?>
                        <?php echo wp_get_attachment_image($img2, 'medium', false, [
                            'class'           => 'single-real__single-img',
                            'data-object-fit' => 'cover',
                            'sizes'           => '(min-width: 1170px) 65.5rem, (min-width: 900px) 58vw, (min-width: 600px) 50vw, 100vw',
                        ]) ?>
                    <?php else: ?>
                        <?php echo $theme->inline_svg($theme->dist_url('/svg/logo_slogan.svg'), [
                            'alt'    => '',
                            'class'  => 'single-real__single-logo',
                            'height' => '115',
                            'width'  => '360',
                        ]) ?>
                    <?php endif ?>
                </div>
            <?php else: ?>
                <?php $img2 = get_field('img_full_2', false, false) ?>
                <div class="single-real__tall">
                    <?php if ($img2) echo wp_get_attachment_image($img2, 'medium', false, [
                        'sizes' => '(min-width: 1170px) 36rem, (min-width: 900px) 33vw, (min-width: 600px) 50vw, 100vw',
                    ]) ?>
                </div>

                <div class="single-real__duo">
                    <div class="single-real__duo-item">
                        <?php $img3 = get_field('img_full_3', false, false) ?>
                        <?php if ($img3) echo wp_get_attachment_image($img3, 'medium', false, [
                            'sizes' => '(min-width: 1170px) 26.5rem, (min-width: 900px) 25vw, (min-width: 600px) 50vw, 100vw',
                        ]) ?>
                    </div>
                    <div class="single-real__duo-item">
                        <?php $img4 = get_field('img_full_4', false, false) ?>
                        <?php if ($img4) echo wp_get_attachment_image($img4, 'medium', false, [
                            'sizes' => '(min-width: 1170px) 26.5rem, (min-width: 900px) 25vw, (min-width: 600px) 50vw, 100vw',
                        ]) ?>
                    </div>
                </div>

            <?php endif ?>
        </div>

        <div class="single__content">
            <?php get_template_part('partial/flex') ?>
        </div>

        <?php get_template_part('partial/pager') ?>
    </div>

<?php get_footer() ?>