<?php if (false): ?>
<!-- this is only to silent warnings about extra closing tags -->
<!--suppress HtmlRequiredLangAttribute -->
<html>
<body>
<div class="wrapper">
    <main id="main" class="main">
        <?php endif ?>
    </main>

    <?php $theme = CardinalTheme::get_instance() ?>

    <footer class="footer print-off">
        <div class="footer__inner container">
            <h2 class="visually-hidden">Navigation secondaire</h2>
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
    typeof Promise === 'function' || document.write('<script src="https://unpkg.com/promise-polyfill"><\/script>');
</script>

<?php wp_footer() ?>
</body>
</html>
