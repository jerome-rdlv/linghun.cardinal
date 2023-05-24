<?php


namespace Rdlv\WordPress\Theme;

use Walker;
use Walker_Nav_Menu;

class MenuBackgroundsWalker extends Walker_Nav_Menu
{
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        $thumb_id = get_post_thumbnail_id($element->object_id);
        if (!$thumb_id) {
            return;
        }

        $background = '';

//        $portraits = [];
//        foreach (wp_get_additional_image_sizes() as $key => $size) {
//            if (preg_match('/p-([0-9]+)-x1/', $key)) {
//                $srcset = wp_get_attachment_image_srcset($thumb_id, $key);
//                $portraits[$size['width']] = sprintf(
//                    '<source media="(max-width: %1$spx)" srcset="%2$s %1$sw%3$s">',
//                    $size['width'],
//                    wp_get_attachment_image_url($thumb_id, $key, false),
//                    $srcset ? ', ' . $srcset : ''
//                );
//            }
//        }
//        ksort($portraits);
//        $background .= implode("\n", $portraits);
        $background .= wp_get_attachment_image($thumb_id, 'large', false, [
            'aria-hidden' => 'true',
            'alt' => '',
            'data-object-fit' => 'cover',
            'id' => 'item-back-' . $element->object_id,
            'sizes' => '(min-width: 920px) 100vw, 920px',
        ]);

        $mobile_image = get_field('splash_mobile', $element->object_id, false);
        if ($mobile_image) {
            $background = sprintf(
                '<picture><source media="(max-width: 600px)" srcset="%s">%s</picture>',
                wp_get_attachment_image_srcset($mobile_image),
                $background
            );
        }

        $output .= $background;
    }
}