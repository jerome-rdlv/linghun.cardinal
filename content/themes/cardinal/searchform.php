<?php $unique_id = esc_attr(uniqid('search__form-')); ?>
<?php $theme = CardinalTheme::get_instance() ?>
<form role="search" method="get" class="search print-off" action="<?php echo esc_url(home_url('/')); ?>">
    <p class="search__inner">
        <label for="<?php echo $unique_id; ?>" class="search__label">
            <?php echo $theme->inline_svg($theme->dist_url('/svg/search.svg')) ?>
            <span class="visually-hidden">
                <?php _e('Rechercher', CardinalTheme::TEXTDOMAIN) ?>
            </span>
        </label>
        <input type="search" id="<?php echo $unique_id; ?>" class="search__input"
               value="<?php echo get_search_query() ?>" name="s"/>
        <button type="submit" class="search__submit">
            <?php _e('OK', CardinalTheme::TEXTDOMAIN) ?>
        </button>
    </p>
</form>