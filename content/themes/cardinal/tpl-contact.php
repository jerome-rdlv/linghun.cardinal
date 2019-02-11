<?php /* Template name: Contact */ ?>
<?php $theme = CardinalTheme::get_instance() ?>
<?php remove_action('wp_enqueue_scripts', array($theme, 'dequeue_cf7')) ?>
<?php get_header() ?>
<?php the_post() ?>

<?php $contact_form_id = get_field('form', false, false) ?>
<?php if ($contact_form_id): ?>
    <?php $theme->form_render($contact_form_id) ?>
<?php endif ?>

<?php get_footer() ?>