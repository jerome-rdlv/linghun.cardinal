<?php
/*
 * Plugin Name: ACF Configuration
 */

add_action('acf/init', function () {
    if (function_exists('pll_default_language')) {
        acf_update_setting('default_language', pll_default_language('locale'));
    }
    if (function_exists('pll_current_language')) {
        acf_update_setting('current_language', pll_current_language('locale'));
    }
});

// show / hide admin
add_filter('acf/settings/show_admin', function () {
    return defined('ACF_ADMIN') && ACF_ADMIN;
});

// disable update
add_filter('acf/settings/show_update', '__return_false');
add_filter('site_transient_update_plugins', function ($value) {
    if (isset($value->response['acf.php'])) {
        unset($value->response['acf.php']);
    }
    return $value;
});

// saving and loading fields
add_filter('acf/settings/save_json', function () {
    return WP_CONTENT_DIR . '/fields';
});
add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = WP_CONTENT_DIR . '/fields';
    return $paths;
});

// add search capability to custom fields
add_action('acf/save_post', function ($post_id) {
    if (empty($_POST['acf'])) {
        return;
    }

    $get_text_values = function ($raw_values, &$text_values = []) use (&$get_text_values) {
        foreach ($raw_values as $key => $raw_value) {
            if (is_array($raw_value)) {
                $get_text_values($raw_value, $text_values);
            } elseif (is_string($key) && ($field = get_field_object($key)) && in_array($field['type'],
                    ['text', 'textarea', 'wysiwyg', 'email'])) {
                $text_values[] = strip_tags($raw_value);
            }
        }
        return array_unique($text_values);
    };

    update_post_meta($post_id, '_acf_texts', implode(' ', $get_text_values($_POST['acf'])));

}, 20);

add_filter('wp_loaded', function () {
    $alias = uniqid('search_');

    // join post_meta on given fields
    add_filter('posts_join', function ($join) use ($alias) {
        if (is_search() && is_main_query()) {
            global $wpdb;
            $join .= " LEFT JOIN $wpdb->postmeta $alias";
            $join .= " ON $wpdb->posts.ID = $alias.post_id";
            $join .= " AND $alias.meta_key = '_acf_texts'";
        }
        return $join;
    });

    // add meta_value comparison
    add_filter('posts_search', function ($where) use ($alias) {
        if (is_search() && is_main_query()) {
            global $wpdb;
            $where = preg_replace(
                "/\($wpdb->posts.post_content\s+LIKE\s*(('|\").*?\\2)\s*\)/",
                "$0 OR ($alias.meta_value LIKE $1)",
                $where
            );
        }
        return $where;
    });

});

// complement excerpt with ACF texts
add_filter('get_the_excerpt', function ($text) {
    // @see wp_trim_excerpt (wp/wp-includes/formatting.php:37)
    if ($text == '') {
        $text = get_the_content('');
        $text .= get_post_meta(get_the_ID(), '_acf_texts', true);
        $text = strip_shortcodes($text);
        $text = excerpt_remove_blocks($text);

        /** This filter is documented in wp-includes/post-template.php */
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
    }
    return $text;
}, 9);

// add field name to input field classes in admin
add_action('acf/input/admin_footer', function () {
    echo <<<EOF
<script>
/** Script by https://profiles.wordpress.org/delwinv/ */
(function ($) {
    acf.add_filter('wysiwyg_tinymce_settings', function (mceInit, id) {
        //var mceInitElements = $('#' + mceInit.elements);
        var mceInitElements = $('#'+ id);
        var acfEditorField = mceInitElements.closest('.acf-field[data-type="wysiwyg"]');
        var fieldKey = acfEditorField.data('key');
        var fieldName = acfEditorField.data('name');
        var flexContentName = mceInitElements.parents('[data-type="flexible_content"]').first().data('name');
        var layoutName = mceInitElements.parents('[data-layout]').first().data('layout');
        mceInit.body_class += " acf-field-key-" + fieldKey;
        mceInit.body_class += " acf-field-name-" + fieldName;
        if (flexContentName) {
            mceInit.body_class += " acf-flex-name-" + flexContentName;
        }
        if (layoutName) {
            mceInit.body_class += " acf-layout-" + layoutName;
        }
        return mceInit;
    });
})(jQuery);
</script>
EOF;
});

