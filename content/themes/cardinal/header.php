<!DOCTYPE html>
<!--suppress HtmlRequiredLangAttribute -->
<html <?php language_attributes() ?> class="js-off fonts-on">
<head>
    <meta charset="<?php bloginfo('charset') ?>"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <?php $theme = CardinalTheme::get_instance() ?>
    <style> html.js-off .js-on { display: none; } </style>
    
    <title><?php bloginfo('title') ?></title>
    <?php wp_head() ?>
</head>
<body <?php body_class('') ?>>
<?php do_action('body_top') ?>

<script type="text/html" id="nav-toggle-tpl">
    <button type="button" id="nav-toggle" data-target="#nav"
            class="header__nav-toggle nav__toggle">
        <?php echo $theme->inline_symbol('menu') ?>
        <span class="visually-hidden">
            <?php _e('Navigation', CardinalTheme::TEXTDOMAIN) ?>
        </span>
    </button>
</script>

<header class="header header--fixed">
    <div class="header__skips skips print-off">
        <div class="skips__inner container">
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
            </ul>
        </div>
    </div>

    <div class="header__inner container">
        <div class="header__title">
            <?php $title = sprintf(
                '%s<span class="visually-hidden">%s</span>',
                $theme->get_symbol('logo', [
                    'class' => 'header__icon',
                ]),
                get_bloginfo('name')
            ) ?>
            <?php if (is_front_page()): ?>
                <h1 class="header__logo">
                    <?php echo $title ?>
                    <?php if (get_bloginfo('description')): ?>
                        <span class="header__slogan">
                            <span class="visually-hidden">-</span>
                            <?php bloginfo('description') ?>
                        </span>
                    <?php endif ?>
                </h1>
            <?php else: ?>
                <a class="header__logo" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php echo $title ?>
                </a>
                <?php if (get_bloginfo('description')): ?>
                    <span class="header__slogan">
                            <span class="visually-hidden">-</span>
                            <?php bloginfo('description') ?>
                        </span>
                <?php endif ?>
            <?php endif ?>
        </div>
        
        <?php $networks = get_field('social_networks', 'options') ?>
        <?php if ($networks && is_array($networks)): ?>
            <!-- @formatter:off -->
                <ul class="header__networks print-off"><!--
                    <?php foreach ($networks as $network): ?>
                        --><li class="header__networks-item">
                            <?php echo $theme->get_network_link($network['url'], 'header__networks-link') ?>
                        </li><!--
                    <?php endforeach ?>
                --></ul>
                <!-- @formatter:on -->
        <?php endif ?>

        <?php $contact_page = get_field('page_contact', 'options', false) ?>
        <?php if ($contact_page): ?>
            <a href="<?php the_permalink($contact_page) ?>" class="header__contact print-off">
                <?php echo $theme->inline_symbol('contact') ?>
                <span class="visually-hidden">Contact</span>
            </a>
        <?php endif ?>
    </div>
</header>

<div class="wrapper">
    <main id="main" class="main">
