<?php $theme = CardinalTheme::get_instance() ?>
<div class="flex-video">
    <?php $platform = $theme->get_network_key(get_sub_field('video', false)) ?>
    <?php wp_add_inline_script('tac', '(tarteaucitron.job = tarteaucitron.job || []).push(\'rdlv_'. $platform .'\');') ?>
    <?php $theme->video_html(get_sub_field('video'), $platform .'_player flex-video__video') ?>
</div>