<?php

use Rdlv\WordPress\Theme\MenuBackgroundsWalker;

?>
<!DOCTYPE html>
<!--suppress HtmlRequiredLangAttribute -->
<html <?php language_attributes() ?> class="js-off fonts-on">
<head>
    <meta charset="<?php bloginfo('charset') ?>"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php $theme = CardinalTheme::get_instance() ?>

    <title><?php bloginfo('title') ?></title>
    <style> #main-css-ctrl {
            opacity: 0;
        } </style>
    <?php wp_head() ?>
</head>
<body <?php body_class('') ?>>
<?php do_action('body_top') ?>

<script type="text/html" id="nav-toggle-tpl">
    <button type="button" id="nav-toggle" data-target="#nav"
            class="header__nav-toggle nav__toggle print-off">
        <?php echo $theme->inline_symbol('menu') ?>
        <span class="visually-hidden">
            <?php _e('Navigation', CardinalTheme::TEXTDOMAIN) ?>
        </span>
    </button>
</script>

<script type="text/html" id="nav-backs-tpl">
    <div class="nav__backs print-off" id="nav-backs">
        <?php wp_nav_menu([
            'container'      => '',
            'items_wrap'     => '%3$s',
            'theme_location' => CardinalTheme::MENU_MAIN,
            'walker'         => new MenuBackgroundsWalker(),
        ]) ?>
    </div>
</script>

<header class="header">
    <div class="header__skips skips print-off">
        <div class="skips__inner">
            <ul class="skips__list">
                <li class="skips__item">
                    <a href="#main" class="skips__link">
                        <?php _e('Aller au contenu', CardinalTheme::TEXTDOMAIN) ?>
                    </a>
                </li>
                <li class="skips__item">
                    <!--suppress HtmlUnknownAnchorTarget -->
                    <a href="#nav" class="skips__link smooth-off">
                        <?php _e('Aller à la navigation', CardinalTheme::TEXTDOMAIN) ?>
                    </a>
                </li>
                <li class="skips__item">
                    <!--suppress HtmlUnknownAnchorTarget -->
                    <a href="#search" class="skips__link smooth-off">
                        <?php _e('Aller à la recherche', CardinalTheme::TEXTDOMAIN) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="header__inner container">
        <div class="header__title">
            <?php $desc = get_bloginfo('description') ?>
            <?php $title = sprintf(
                '%s<span class="visually-hidden print-off">%s</span>%s',
                $theme->inline_svg($theme->dist_url('/svg/logo.svg'), [
                    'id'    => 'icon-logo',
                    'class' => 'header__icon',
                ]),
                get_bloginfo('name'),
                $desc ? '<span class="header__slogan"><span class="header__slogan-inner"><span class="visually-hidden print-off">-</span>' . $desc . '</span></span>' : ''
            ) ?>
            <?php if (is_front_page()): ?>
                <h1 class="header__logo">
                    <?php echo $title ?>
                </h1>
            <?php else: ?>
                <a class="header__logo" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo $title ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</header>

<nav id="nav" class="nav print-off">
    <div class="nav__inner">
        <p class="visually-hidden">
            <?php _e('Navigation principale', CardinalTheme::TEXTDOMAIN) ?>
        </p>

        <?php wp_nav_menu([
            'container'      => null,
            'depth'          => 1,
            'theme_location' => CardinalTheme::MENU_MAIN,
        ]) ?>

        <div class="nav__aside nav-aside">
            <?php $contact_page = get_field('page_contact', 'options', false) ?>
            <?php if ($contact_page): ?>
                <a href="<?php the_permalink($contact_page) ?>" class="nav-aside__contact print-off">
                    <?php echo $theme->inline_symbol('contact') ?>
                    <span class="visually-hidden">Contact</span>
                </a>
            <?php endif ?>

            <div class="nav-aside__search" id="search">
                <?php get_search_form() ?>
            </div>

            <?php $networks = get_field('social_networks', 'options') ?>
            <?php if ($networks && is_array($networks)): ?>
                <div class="nav-aside__networks print-off">
                    <p class="visually-hidden">
                        <?php _e('Suivez-nous sur :', CardinalTheme::TEXTDOMAIN) ?>
                    </p>
                    <!-- @formatter:off -->
                <ul class="networks"><!--
                    <?php foreach ($networks as $network): ?>
                        --><li class="networks__item">
                            <?php echo $theme->get_network_link($network['url'], 'networks__link') ?>
                        </li><!--
                    <?php endforeach ?>
                --></ul>
                <!-- @formatter:on -->
                </div>
            <?php endif ?>
        </div>
    </div>
</nav>

<div class="wrapper">
    <main id="main" class="main">
