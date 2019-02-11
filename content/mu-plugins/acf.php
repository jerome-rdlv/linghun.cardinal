<?php
/*
 * Plugin Name: ACF Configuration
 */

include_once __DIR__ . '/acf-pro/acf.php';
add_action('acf/init', function () {

    acf_update_setting('path', __DIR__ . '/acf-pro/');
    acf_update_setting('dir', plugins_url('acf-pro', __FILE__) . '/');

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
add_filter('acf/settings/save_json', function ($path) {
    return WP_CONTENT_DIR .'/fields';
});
add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = WP_CONTENT_DIR .'/fields';
    return $paths;
});

// add search capability to custom fields
add_filter('wp_loaded', function () {
    $fields = apply_filters('acf_search_fields', []);
    if ($fields) {
        $alias = uniqid('meta_');
        
        // join post_meta on given fields
        add_filter('posts_join', function ($join) use ($alias, $fields) {
            if (is_search() && is_main_query()) {
                global $wpdb;
                $join .= " LEFT JOIN $wpdb->postmeta $alias ON ";
                $join .= " $wpdb->posts.ID = $alias.post_id AND ";
//                $join .= "$alias.meta_key NOT LIKE '\_%' AND (";
                foreach ($fields as $index => $field) {
                    if ($index > 0) {
                        $join .= " OR ";
                    }
                    $check = preg_replace('/\\\\[_%]/', '', $field);
                    if (strpos($check, '%') !== false || strpos($check, '_') !== false) {
//                        $join .= "$alias.meta_key LIKE '". str_replace('_', '\_', $field) ."'";
                        $join .= "$alias.meta_key LIKE '". $field ."'";
                    }
                    else {
                        $join .= "$alias.meta_key = '$field'";
                    }
                }
//                $join .= ")";
            }
            return $join;

        });
        
        // add meta_value comparison
        add_filter('posts_search', function ($where) use ($alias, $fields) {
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
        
        // distinct (because join may lead to duplication)
        add_filter('posts_distinct', function ($distinct) {
            return is_search() ? 'DISTINCT' : $distinct;
        });
    }
});

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

