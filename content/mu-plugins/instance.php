<?php
/*
 * Plugin Name: Instance
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$loads = [
//    __DIR__ .'/post-order/post-order.php',
//    __DIR__ .'/social-network/social-network.php',
    __DIR__ . '/disable-comments/disable-comments.php',
    __DIR__ . '/allow-svg/allow-svg.php',
    __DIR__ . '/acf-code-field/acf-code-field.php',
    __DIR__ . '/cpt-parent/cpt-parent.php',
//    __DIR__ .'/acf-options-for-polylang/bea-acf-options-for-polylang.php',
];

foreach ($loads as $load) {
    require_once $load;
}

add_action('plugin_row_meta', function ($plugin_meta, $plugin_file, $plugin_data, $status) use ($loads) {
    if ($status === 'mustuse' && $plugin_file === basename(__FILE__)) {
        $plugin_meta[] = sprintf(
            '<div style="margin-top:-2.1em;">%s</div>',
            implode(
                ', ',
                array_map(function ($item) {
                    return basename($item, '.php');
                }, $loads)
            )
        );
    }
    return $plugin_meta;
}, 10, 4);

add_action('init', function () {

    if (!is_admin()) {
        // needed by Gutenberg on admin screens
        wp_deregister_script('wp-embed');
    }

    add_filter('xmlrpc_enabled', '__return_false');

    unregister_taxonomy_for_object_type('post_tag', 'post');
    unregister_taxonomy_for_object_type('category', 'post');

    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'  => 'Paramètres d’instance',
            'menu_title'  => 'Instance',
            'menu_slug'   => 'instance-options',
            'capability'  => 'edit_posts',
            'parent_slug' => 'options-general.php',
        ));
    }

    // search in custom fields (joker % authorized)
    add_filter('acf_search_fields', function ($fields) {
        return [
        ];
    });
    
    // display search page field on reading settings page
//    add_action('admin_init', function () {
//        $option_name = 'page_for_search';
//        register_setting('reading', $option_name, array(
//            'type' => 'integer',
//        ));
//        add_settings_field(
//            $option_name,
//            '<label for="' . $option_name . '">Parent page for search</label>',
//            function () use ($option_name) {
//                echo wp_dropdown_pages(array(
//                    'name'             => $option_name,
//                    'id'               => $option_name,
//                    'echo'             => 0,
//                    'show_option_none' => '— Select —',
//                    'selected'         => get_option($option_name),
//                ));
//            },
//            'reading'
//        );
//    });
    
    
    add_filter('cpt_has_parent_page', function ($has_parent, $post_type) {
        return array_search($post_type, ['cardinal_presse', 'cardinal_real']) !== false ? true : $has_parent;
    }, 10, 2);

    // CPT
    $presse_page = get_option('page_for_cardinal_presse');
    register_taxonomy('cardinal_presse_cat', 'cardinal_presse', [
        'labels'            => [
            'name'          => 'Catégories',
            'singular_name' => 'Catégorie',
        ],
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_admin_column' => true,
        'hierarchical'      => true,
        'rewrite'           => [
            'slug'       => ($presse_page ? get_page_uri($presse_page) . '/' : '') . 'cat',
            'with_front' => false,
        ],
    ]);
    register_post_type('cardinal_presse', [
        'labels'      => [
            'name'          => 'Presse',
            'singular_name' => 'Presse',
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => [
            'title',
//            'editor',
            'author',
            'thumbnail',
            'revisions',
        ],
        'rewrite'     => [
            'slug'       => 'presse',
            'with_front' => false,
            'pages'      => true,
        ],
        'taxonomies'  => [
            'cardinal_presse_cat',
        ],
    ]);
    
    $reals_page = get_option('page_for_cardinal_real');
    register_taxonomy('cardinal_real_cat', 'cardinal_real', [
        'labels'            => [
            'name'          => 'Catégories',
            'singular_name' => 'Catégorie',
        ],
        'meta_box_cb'       => 'post_categories_meta_box',
        'show_admin_column' => true,
        'hierarchical'      => true,
        'rewrite'           => [
            'slug'       => ($reals_page ? get_page_uri($reals_page) . '/' : '') . 'cat',
            'with_front' => false,
        ],
    ]);
    register_post_type('cardinal_real', [
        'labels'      => [
            'name'          => 'Réalisations',
            'singular_name' => 'Réalisation',
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => [
            'title',
//            'editor',
            'author',
            'thumbnail',
            'revisions',
        ],
        'rewrite'     => [
            'slug'       => 'realisations',
            'with_front' => false,
            'pages'      => true,
        ],
        'taxonomies'  => [
            'cardinal_real_cat',
        ],
    ]);
});

/**
 * Replace placeholder with privacy page url.
 */
add_filter('acf_form', function ($html) {
    return str_replace(
        [
            '{privacy_policy_url}',
            '{title}',
        ],
        [
            get_privacy_policy_url(),
            get_bloginfo('name'),
        ],
        $html
    );
});

/**
 * Skip email if form is newsletter form.
 */
add_filter('wpcf7_skip_mail', function ($skip_mail, $form) {
    /** @var WPCF7_ContactForm $form */
    $newsletter_page_id = get_field('page_newsletter', 'options', false);
    $newsletter_form_id = get_field('form', $newsletter_page_id, false);
    if ($form->id() == $newsletter_form_id) {
        return true;
    }
    return $skip_mail;
}, 10, 2);

/**
 * @see wp/wp-includes/general-template.php:2840
 */
add_filter('get_site_icon_url', function ($url, $size) {
    if (in_array($size, [32, 192])) {
        $favicon_id = get_field('favicon', 'options', false);
        if ($favicon_id) {
            $url = wp_get_attachment_image_url($favicon_id, [$size, $size]);
        }
    }
    return $url;
}, 10, 4);

add_action('wp_footer', function () {
    echo sprintf('<script type="application/ld+json">%s</script>', json_encode([
        '@context'  => 'http://schema.org/',
        '@type'     => 'Organization',
        'name'      => get_field('coord_name', 'options'),
        'url'       => get_home_url(),
        'address'   => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => get_field('coord_street', 'options'),
            'postalCode'      => get_field('coord_zip', 'options'),
            'addressLocality' => get_field('coord_city', 'options'),
            'addressCountry'  => 'France',

        ],
        'telephone' => get_field('coord_tel', 'options'),
        'faxNumber' => get_field('coord_fax', 'options'),
        'email'     => get_field('coord_email', 'options'),
        'logo'      => get_field('logo', 'options')['url'],
    ]));
});

// google map api key
//add_action('acf/init', function () {
//    acf_update_setting('google_api_key', get_field('google_map_api_key', 'option'));
//});

