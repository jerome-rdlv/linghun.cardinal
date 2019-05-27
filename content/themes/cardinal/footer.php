<?php if (false): ?>
<!-- this is only to silent warnings about extra closing tags -->
<!--suppress HtmlRequiredLangAttribute -->
<html>
<body>
<div class="wrapper">
    <main id="main" class="main">
        <?php endif ?>
        <div class="main__top container">
            <!--suppress HtmlUnknownAnchorTarget -->
            <a href="#header" class="main__top-link">
                <span class="visually-hidden">Remonter</span>
            </a>
        </div>
    </main>

    <?php $theme = CardinalTheme::get_instance() ?>

    <script><?php include $theme->dist_path('/js/init-fade-in.js') ?></script>

    <footer class="footer print-off">
        <div class="footer__inner container">
            <h2 class="visually-hidden">Navigation secondaire</h2>
            <p>
                <a href="<?php echo home_url() ?>" class="footer__home"
                   aria-label="<?php _e('Retour à l’accueil', CardinalTheme::TEXTDOMAIN) ?>">
                    <?php echo $theme->get_symbol('logo', [
                        'aria-hidden' => true,
                        'class'       => 'footer__logo',
                    ]) ?>
                    <?php the_field('footer_slogan', 'options') ?>
                </a>
            </p>
            <?php wp_nav_menu([
                'container'      => null,
                'depth'          => 1,
                'theme_location' => CardinalTheme::MENU_ASIDE,
            ]) ?>
        </div>
    </footer>

</div><!-- end .wrapper -->

<!--[if lt IE 9]>
<script src="https://unpkg.com/ie8"></script>
<![endif]-->

<script>
    // Polyfills
    (document.head && document.head.after) || document.write('<script src="https://unpkg.com/dom4"><\/script>');
    window.requestAnimationFrame || document.write('<script src="<?php echo $theme->dist_url('/js/request-animation-frame-polyfill.js') ?>"><\/script>');
    typeof Object.assign == 'function' || document.write('<script src="<?php echo $theme->dist_url('/js/object-assign-polyfill.js') ?>"><\/script>');
    typeof Promise === 'function' || document.write('<script src="https://unpkg.com/promise-polyfill"><\/script>');
</script>

<?php wp_footer() ?>
</body>
</html>
