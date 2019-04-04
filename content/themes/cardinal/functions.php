<?php

CardinalTheme::get_instance();

class CardinalTheme
{
    private static $instance = null;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    const TEXTDOMAIN = 'cardinal';

    const SCRIPT_INC_HEAD = 'head';
    const SCRIPT_INC_BODY_TOP = 'body_top';
    const SCRIPT_INC_BODY_BOTTOM = 'body_bottom';

    const IMG_PORTRAIT_RATIO = 568 / 320;
    const IMG_PORTRAIT_MAX_WIDTH = 800;
    const IMG_MAX_WIDTH = 2800;

    const MENU_MAIN = 'main';
    const MENU_ASIDE = 'aside';
    const MENU_FOR_EDIT_LINK = 'aside';

    private static $scriptIncs = [
        self::SCRIPT_INC_HEAD        => 'Entête',
        self::SCRIPT_INC_BODY_TOP    => 'Juste après &lt;body&gt;',
        self::SCRIPT_INC_BODY_BOTTOM => 'Juste avant &lt;/body&gt;',
    ];

    private $symbols = [
//        'logo',
//        'contact',
//        'facebook',
//        'twitter',
//        'linkedin',
//        'viadeo',
    ];

    private $theme_url = null;
    private $theme_path = null;

    private $bodyClass = [];

    public function __construct()
    {
        add_action('init', [$this, 'init']);
        add_action('admin_init', [$this, 'admin_init']);
    }

    public function init()
    {
        // misc config
        show_admin_bar(false);
        remove_theme_support('post-formats');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', ['search-form']);
        add_filter('use_default_gallery_style', '__return_false');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');


        // cf7
//        add_filter('wpcf7_load_js', '__return_false');
        add_filter('wpcf7_load_css', '__return_false');
        add_action('wp_enqueue_scripts', [$this, 'dequeue_cf7']);

        add_action('add_body_class', [$this, 'add_body_class']);
        add_filter('body_class', [$this, 'body_class']);
        add_action('get_header', function ($name) {
            $this->add_body_class('tpl-' . $name);
        });

        add_filter('wpseo_breadcrumb_output', [$this, 'fix_breadcrumb']);

        // menu
        register_nav_menus([
            self::MENU_MAIN  => 'Principal',
            self::MENU_ASIDE => 'Secondaire',
        ]);
        add_filter('nav_menu_item_title', [$this, 'prefix_current_menu_item'], 10, 2);
        add_filter('nav_menu_item_title', [$this, 'add_menu_item_picto'], 10, 3);
        add_filter('nav_menu_link_attributes', [$this, 'main_menu_add_item_id'], 10, 4);
//        add_filter('nav_menu_css_class', [$this, 'aside_menu_classes'], 10, 3);
//        add_filter('nav_menu_item_title', [$this, 'aside_menu_logo'], 10, 3);

        // images
        $this->generic_image_sizes();
        add_filter('max_srcset_image_width', function () {
            return self::IMG_MAX_WIDTH;
        });

        // content
        add_filter('excerpt_length', function () {
            return is_search() ? 60 : 12;
        });
        add_filter('excerpt_more', function () {
            return '…';
        });
        add_filter('the_excerpt', [$this, 'excerpt_remove_paragraphs']);
        add_action('pre_get_posts', [$this, 'realisations_per_page']);
        add_filter('the_content', [$this, 'transform_cta']);

        // forms
        if (function_exists('wpcf7_remove_form_tag')) {
            wpcf7_remove_form_tag('recaptcha');
            wpcf7_add_form_tag('recaptcha', function () {
                return '<div class="g-recaptcha"><p class="form__notice">Ce site est protégé par reCAPTCHA, et les
<a href="https://policies.google.com/privacy" target="_blank">règles de confidentialité</a> et les
<a href="https://policies.google.com/terms" target="_blank">conditions d’utilisation</a> de Google s’appliquent.</p>
</div>';
            });
        }

        // styles
//        wp_register_style('critical', $this->dist_url('/css/critical.css'), []);
//        wp_styles()->add_data('critical', 'critical', true);
        wp_register_style('main', $this->dist_url('/css/main.css'), [], false, 'screen');
        wp_register_style('print', $this->dist_url('/css/print.css'), [], false, 'print');

        // scripts
        wp_register_script('font-face-observer',
            $this->theme_url('/node_modules/web/fontfaceobserver/fontfaceobserver.js'), [], false, true);
        wp_register_script('fonts', $this->dist_url('/js/fonts.js'), ['font-face-observer'], false, true);
        wp_localize_script('fonts', 'webfonts', [
            ['family' => 'DINProCond'],
            ['family' => 'DINProCondBold'],
            ['family' => 'GothamBook'],
            ['family' => 'GothamMedium'],
            ['family' => 'GothamBold'],
        ]);
        wp_scripts()->add_data('fonts', 'critical', true);

        // slick
//        wp_register_script('slick', $this->theme_url('/node_modules/web/slick/slick/slick.min.js'), ['jquery'], false, true);
//        wp_register_script('slider', $this->dist_url('/js/slider.js'), ['slick'], false, true);

        // masonry
        wp_deregister_script('masonry');
        wp_register_script('masonry', $this->theme_url('/node_modules/web/masonry/dist/masonry.pkgd.min.js'), [], false,
            true);

        // ePD (tarteaucitron / TaC)
        wp_register_script('tac', $this->theme_url('/node_modules/web/tarteaucitron/tarteaucitron.js'), [], false,
            true);
        wp_add_inline_script('tac', sprintf('tarteaucitron.init(%s);', json_encode([
            'privacyUrl'     => get_privacy_policy_url(),
            'orientation'    => 'bottom',
            'adblocker'      => false,
            'showAlertSmall' => false,
            'cooieslist'     => false,
            'removeCredit'   => true,
            'moreInfoLink'   => false,
        ])));
        wp_register_script('tac-overrides', $this->dist_url('/js/tac-overrides.js'), ['tac'], false, true);

        wp_register_script('main', $this->dist_url('/js/main.js'), ['jquery', 'tac-overrides'], false, true);
        wp_scripts()->add_data('main', 'differed', true);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        // edition
        add_filter('wp_nav_menu_items', [$this, 'menu_edit_link'], 10, 2);
        add_editor_style($this->dist_url('/css/editor.css'));
        add_filter('tiny_mce_before_init', [$this, 'editor_options'], 10, 2);
        add_filter('acf/fields/wysiwyg/toolbars', [$this, 'acf_editor_options']);
        add_filter('mce_external_plugins', function ($plugins) {
            // enable custom fonts in admin
            if (file_exists($this->dist_path('/js/editor-enable-fonts.js'))) {
                $plugins['editor-enable-fonts'] = $this->dist_url('/js/editor-enable-fonts.js');
            }
            return $plugins;
        });

        // admin
        wp_register_style('admin', $this->dist_url('/css/admin.css'));
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

        // includes
        add_action('wp_head', function () {
            $this->scripts_inc(self::SCRIPT_INC_HEAD);
        }, 0);
        add_action('body_top', function () {
            $this->body_top_script();
            $this->scripts_inc(self::SCRIPT_INC_BODY_TOP);
            $this->print_symbols();
        });
        add_action('wp_footer', function () {
            $this->scripts_inc(self::SCRIPT_INC_BODY_BOTTOM);
        }, 100);
        add_filter('acf/load_field/name=scripts', [$this, 'scripts_insert_position']);
    }

    public function admin_init()
    {
        $image_default_link_type = get_option('image_default_link_type');
        if ($image_default_link_type !== 'none') {
            update_option('image_default_link_type', 'none');
        }
    }

    public function scripts_inc($position)
    {
        $scripts = get_field('scripts', 'options');
        if ($scripts) {
            foreach ($scripts as $script) {
                if ($script['position'] === $position) {
                    echo $script['script'];
                }
            }
        }
    }

    public function scripts_insert_position($field)
    {
        global $post;

        if ($post && $post->post_type === 'acf-field-group' && is_admin()) {
            return $field;
        }
        if ($field['type'] !== 'repeater') {
            return $field;
        }
        if (!isset($field['sub_fields'][0])) {
            return $field;
        }
        if ($field['sub_fields'][0]['name'] !== 'position') {
            return $field;
        }

        $field['sub_fields'][0]['choices'] = self::$scriptIncs;

        return $field;
    }

    public function body_top_script()
    {
        printf(
            "<script>%s</script>\n",
            file_get_contents(
                $this->dist_path('/js/body-top.js')
            )
        );
    }

    public function theme_url($path = '')
    {
        if ($this->theme_url === null) {
            $this->theme_url = get_template_directory_uri();
        }
        return $this->theme_url . $path;
    }

    public function theme_path($path = '')
    {
        if ($this->theme_path === null) {
            $this->theme_path = get_template_directory();
        }
        return $this->theme_path . $path;
    }

    public function dist_url($path = '')
    {
        return $this->theme_url('/assets' . $path);
    }

    public function dist_path($path = '')
    {
        return $this->theme_path('/assets' . $path);
    }

    public function get_template_name()
    {
        return is_search() ? 'search' : preg_replace('/^[^_]+_/', '', get_post_type());
    }

    private function generic_image_sizes()
    {
        // landscape / original format
        $inc = 80;
        for ($width = 320; $width <= self::IMG_MAX_WIDTH; $width += $inc) {
            add_image_size(
                $width == self::IMG_MAX_WIDTH - $inc ? 'full-l' : 'img-' . $width,
                $width,
                $width,
                false
            );
            if ($width >= 800) {
                $inc = 200;
            }
        }

        // portrait sizes for splash
        $height = 568;
        for ($width = 320, $inc = 40; $width <= 720; $width += $inc, $inc += 40) {
            for ($density = 1; $density <= 3; ++$density) {
                add_image_size(
                    'p-' . $width . '-x' . $density,
                    $width * $density,
                    $height * $density,
                    true
                );
            }
        }
    }

    public function aside_menu_logo($title, $item, $args)
    {
        if ($args->theme_location === self::MENU_ASIDE) {
            if (trailingslashit($item->url) === trailingslashit(get_home_url())) {
                $logo = $this->get_symbol('logo', [
                    'class' => 'footer__logo',
                ]);
                $title = $logo . '<span class="visually-hidden">' . $title . '</span>';
            }
        }
        return $title;
    }

    public function aside_menu_classes($classes, $item, $args)
    {
        if ($args->theme_location === self::MENU_ASIDE) {
            if (trailingslashit($item->url) === trailingslashit(get_home_url())) {
                $classes[] = 'footer__home';
            }
        }
        return $classes;
    }

    public function prefix_current_menu_item($title, $item)
    {
        if ($item->current) {
            $title = sprintf(
                    '<span class="visually-hidden">%s</span>',
                    __('Page courante : ', self::TEXTDOMAIN)
                ) . $title;
        }
        return $title;
    }

    public function add_menu_item_picto($title, $item, $args)
    {
        if ($args->theme_location === self::MENU_MAIN) {
            // add item picto
            $picto = get_field('picto', $item)['url'];
            $title = $this->inline_svg($picto, [
                    'aria-hidden' => 'true',
                    'class'       => 'menu-item-picto',
                ]) . $title;
        }
        return $title;
    }

    public function main_menu_add_item_id($atts, $item, $args)
    {
        if ($args->theme_location === self::MENU_MAIN) {
            $atts['data-id'] = $item->object_id;
        }
        return $atts;
    }

    public function add_symbol($key)
    {
        $this->symbols[] = $key;
    }

    public function get_symbol($key, $attributes = [])
    {
        return sprintf(
            '<svg aria-hidden="true"%2$s><use xlink:href="#icon-%1$s"></use></svg>',
            $key,
            $this->get_atts($attributes)
        );
    }

    public function inline_symbol($key, $attributes = [])
    {
        return $this->inline_svg(
            $this->dist_url(sprintf('/svg/%s.svg', $key)),
            $attributes
        );
    }

    public function print_symbols()
    {
        if ($this->symbols) {
            echo '<svg style="display:none;">' . "\n";
            foreach ($this->symbols as $symbol) {
                /** @noinspection PhpIncludeInspection */
                include $this->dist_path('/svg/' . $symbol . '.symbol.svg');
                echo "\n";
            }
            echo '</svg>';
        }
    }

    private function get_atts($attributes = [])
    {
        $atts = '';
        foreach ($attributes as $attribute => $value) {
            $atts .= sprintf(' %s="%s"', $attribute, $value);
        }
        return $atts;
    }

    private function protect_svg_ids($svg)
    {
        // id must begin with letter
        $prefix = 's' . substr(sha1(uniqid($svg)), 0, 5);
        preg_match_all('/id=([\'"])(.*?)\1/', $svg, $matches, PREG_SET_ORDER);
        if ($matches) {
            foreach ($matches as $match) {
                $id = $prefix . '-' . $match[2];
                $svg = str_replace(
                    'id=' . $match[1] . $match[2] . $match[1],
                    'id=' . $match[1] . $id . $match[1],
                    $svg);
                $svg = preg_replace(
                    '/#' . $match[2] . '\b/',
                    '#' . $id,
                    $svg);
            }
        }
        return $svg;
    }

    public function inline_svg($url, $atts = [])
    {
        if (preg_match('/\.svg$/i', $url)) {
            $path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);
            if (file_exists($path)) {
                $contents = file_get_contents($path);
                $contents = $this->protect_svg_ids($contents);
                if ($atts) {
                    $contents = preg_replace_callback('/<svg([^>]*)>/', function ($tag_match) use (&$atts) {
                        preg_match_all('/ +([a-z0-9\-]+)=(["\']?)(.*?)\2/i', $tag_match[1], $matches, PREG_SET_ORDER);
                        if ($matches) {
                            $oatts = [];
                            foreach ($matches as $match) {
                                $oatts[$match[1]] = $match[3];
                            }
                            $atts = array_merge($oatts, $atts);
                        }
                        return '<svg' . $this->get_atts($atts) . '>';
                    }, $contents);
                }
                return $contents;
            }
        }
        if (!isset($atts['alt'])) {
            $atts['alt'] = '';
        }
        return sprintf(
            '<img src="%1$s"%2$s>',
            $url,
            $this->get_atts($atts)
        );
    }

    public function video_html($iframe, $class = '')
    {
        $atts = [];
        foreach (['width', 'height', 'src'] as $att) {
            if (preg_match('/\b' . $att . '=(?<quote>["\'])(?<value>.*?)\g{quote}/', $iframe, $matches)) {
                $atts[$att] = $matches['value'];
            }
        }
        printf(
            '<div data-src="%s" data-width="%s" data-height="%s"%s></div>',
            $atts['src'],
            $atts['width'],
            $atts['height'],
            $class ? ' class="' . $class . '"' : ''
        );
    }

    public function video_options($video, $options, $async = false)
    {
        $src = preg_replace('/.* src=(["\'])(.*?)\1.*/', '\2', $video);
        list($url, $query) = explode('?', $src);

        foreach ($options as $option => $value) {
            if ($query) {
                $query .= '&amp;';
            }
            $query .= $option . '=' . $value;
        }
        return preg_replace(
            '/ src=(["\']).*?\1/',
            ' ' . ($async ? 'data-' : '') . 'src=\1' . $url . ($query ? '?' . $query : '') . '\1',
            $video
        );
    }

    public function get_network_key($url)
    {
        return preg_replace('/https?:\/\/([^.]+\.)*([^.]+)\.[^.]+\/.*$/i', '\2', $url);
    }

    public function get_network_link($url, $class = 'network-link')
    {
        $key = $this->get_network_key($url);
        return sprintf(
            '<a href="%1$s" class="%2$s %2$s--%3$s" target="_blank">%4$s<span class="visually-hidden">%5$s</span></a>',
            $url,
            $class,
            $key,
            $this->inline_svg($this->dist_url('/svg/' . $key . '.svg')),
            ucfirst($key)
        );
    }

    public function menu_edit_link($items, $args)
    {
        if ($args->theme_location === self::MENU_FOR_EDIT_LINK) {
            $edit_url = $this->get_edit_link_url();
            if ($edit_url) {
                $item = sprintf(
                    '<li class="menu-item edit-link"><a href="%s">%s</a></li>',
                    $edit_url,
                    __('Éditer', self::TEXTDOMAIN)
                );
                $items .= $item;
            }
        }
        return $items;
    }

    public function get_edit_link_url($pid = null, $hash = null, $url = null)
    {
        if (!is_user_logged_in()) {
            return null;
        }
        if (is_home()) {
            $pid = get_option('page_for_posts');
        }
        if ($pid == null) {
            global $post;
            $pid = $post;
        }
        if (!$url) {
            $url = get_edit_post_link($pid);
        }
        if ($hash) {
            $url .= '#' . $hash;
        }
        return $url;
    }

    public function edit_link($pid = null)
    {
        $url = $this->get_edit_link_url($pid);
        if ($url) {
            printf('<a href="%s" class="edit-link">%s</a>', $url, __('Éditer', self::TEXTDOMAIN));
        }
    }

    public function admin_enqueue_scripts()
    {
        wp_enqueue_style('admin');
    }

    public function dequeue_cf7()
    {
        wp_dequeue_script('contact-form-7');
    }

    public function enqueue_scripts()
    {
        wp_dequeue_style('wp-block-library');

        wp_enqueue_style('critical');
        wp_enqueue_style('main');
        wp_enqueue_style('print');

        wp_deregister_script('jquery');
        wp_register_script('jquery', $this->theme_url('/node_modules/web/jquery/dist/jquery.min.js'), [], false, true);

        wp_enqueue_script('main');
        wp_enqueue_script('fonts');
    }

    public function excerpt_remove_paragraphs($excerpt)
    {
        return preg_replace('/(<p[^>]*>|<\/p>)/', '', $excerpt);
    }

    public function realisations_per_page(WP_Query $query)
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        if ($query->get('post_type') !== 'cardinal_real') {
            return;
        }
        $query->set('posts_per_page', 16);
    }

    public function splash_img()
    {

    }

    public function form_render($form_id)
    {
        if (function_exists('wpcf7_contact_form')) {
            $form = wpcf7_contact_form($form_id);
            echo apply_filters('acf_form', $form->form_html(), $form);
        }
    }

    public function transform_cta($content)
    {
        return preg_replace(
            '/(<p class="content__cta"><a.*?)>(.*?)(<\/a><\/p>)/',
            '\1class="cta"><span>\2</span>\3',
            $content
        );
    }

    public function acf_wysiwyg_classes()
    {
        echo '<script src="' . $this->theme_url('/dist/js/acf-wysiwyg-styling.js') . '"></script>';
    }

    private function get_editor_buttons(
        /** @noinspection PhpUnusedParameterInspection */
        $editor_id = null
    ) {
        $buttons = [
            'styleselect',
            'bold',
//            'italic',
            'bullist',
            'numlist',
            'link',
            'unlink',
            'hr',
            'removeformat',
            'alignleft',
            'alignright',
            'aligncenter',
            'alignjustify',
            'forecolor',
            'undo',
            'redo',
            'fullscreen',
            'code',
        ];

        return $buttons;
    }

    public function front_actu()
    {
        if (get_field('display_selection') === 'selection') {
            $actu_id = get_field('selection', false, false);
            query_posts([
                'post__in'       => [$actu_id],
                'post_type'      => 'any',
                'posts_per_page' => 1,
            ]);
        } else {
            query_posts([
                'order'          => 'DESC',
                'orderby'        => 'post_date',
                'post_type'      => 'post',
                'posts_per_page' => 1,
            ]);
        }
    }

    public function archive_page()
    {
        if (is_search()) {
            return false;
        }
        $post_type = get_post_type();
        $post_type = $post_type === 'post' ? 'posts' : $post_type;
        $archive_page_id = get_option('page_for_' . $post_type);
        if ($archive_page_id) {
            query_posts([
                'post__in'       => [$archive_page_id],
                'post_type'      => 'any',
                'posts_per_page' => 1,
            ]);
            if (have_posts()) {
                the_post();
                return true;
            }
        }
        return false;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function remove_button(&$buttons, $button)
    {
        $pos = array_search($button, $buttons);
        if ($pos !== false) {
            unset($buttons[$pos]);
        }
    }

    public function acf_editor_options($toolbars)
    {
        unset($toolbars['Basic']);
        $toolbars['Full'] = [
            1 => $this->get_editor_buttons(),
        ];

        $toolbars['Bold only'] = [
            1 => [
                'bold',
                'removeformat',
                'undo',
                'redo',
                'code',
            ],
        ];

        $toolbars['Link only'] = [
            1 => [
                'link',
                'removeformat',
                'undo',
                'redo',
                'code',
            ],
        ];

        return $toolbars;
    }

    public function editor_options($init, $editor_id)
    {
        $init['entities'] = '38,amp,60,lt,62,gt,160,nbsp';
        $init['entity_encoding'] = 'named';

        // buttons
        $init['toolbar1'] = implode(', ', $this->get_editor_buttons($editor_id));
        $init['toolbar2'] = '';

        // formats
        $formats = [
            [
                'title'   => 'Titre niveau 2',
                'block'   => 'h2',
                'classes' => 'content__title',
                'wrapper' => false,
            ],
            [
                'title'   => 'Titre niveau 3',
                'block'   => 'h3',
                'classes' => 'content__subtitle',
                'wrapper' => false,
            ],
            [
                'title'   => 'Titre niveau 4',
                'block'   => 'h4',
                'classes' => 'content__subsubtitle',
                'wrapper' => false,
            ],
            [
                'title'   => 'Emphase',
                'block'   => 'p',
                'classes' => 'content__em',
                'wrapper' => false,
            ],
            [
                'title'   => 'Lien CTA',
                'block'   => 'p',
                'classes' => 'content__cta',
                'wrapper' => false,
            ],
        ];

        $init['style_formats'] = json_encode($formats);
        
        $init['textcolor_map'] = '["B81918", "Rouge", "FF6600", "Orange"]';
        $init['textcolor_rows'] = 1;
        $init['theme_advanced_more_colors'] = true;

        global $post;
        if ($post) {
            if (!isset($init['body_class'])) {
                $init['body_class'] = '';
            }
            $init['body_class'] .= ' page-template-' . preg_replace('/\.php$/', '', $post->page_template);
            if ($post->ID == get_option('page_on_front')) {
                $init['body_class'] .= ' front-page';
            }
        }

        return $init;
    }

    /**
     * @param string $class
     */
    public function add_body_class($class)
    {
        $this->bodyClass[] = $class;
    }

    /**
     * @param array $classes
     *
     * @return array
     */
    public function body_class($classes)
    {
        $pageCat = get_field('page_cat');
        $this->add_body_class('page-cat-' . $pageCat);
        if (in_array($pageCat, ['vallee', 'appel'])) {
            $this->add_body_class('splash-anim-on');
        }

        return array_merge($classes, $this->bodyClass);
    }

    public function breadcrumb()
    {
        if (!is_front_page() && function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb(
                '<nav class="Breadcrumbs"><span class="visually-hidden">' . __('Vous êtes ici :',
                    self::TEXTDOMAIN) . '</span> ',
                '</nav>'
            );
        }
    }

    function fix_breadcrumb($link_output)
    {
        /*
        $link_output = preg_replace(
            [
                '#<span xmlns:v="http://rdf.data-vocabulary.org/\#">#',
                '#<span typeof="v:Breadcrumb"><a href="(.*?)" .*?>(.*?)</a></span>#',
                '#<span typeof="v:Breadcrumb">(.*?)</span>#',
                '# property=".*?"#',
                '#</span>$#'
            ],
            [
                '',
                '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="$1" itemprop="url"><span itemprop="title">$2</span></a></span>',
                '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">$1</span></span>',
                '',
                ''
            ], $link_output
        ); */
        return $link_output;
    }

    /**
     * @param string $format
     * @param array $vars
     * @param string $regex
     * @return string Input with replacement applied
     */
    public function nvsprintf($format, $vars = [], $regex = '/{([^{}]*?)}/')
    {
        return preg_replace_callback($regex, function ($matches) use ($vars) {
            if (array_key_exists($matches[1], $vars)) {
                return $vars[$matches[1]];
            } else {
                return $matches[0];
            }
        }, $format);
    }
}
