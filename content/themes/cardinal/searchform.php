<?php $unique_id = esc_attr(uniqid('search__form-')); ?>
<?php $theme = CardinalTheme::get_instance() ?>
<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"
      class="search-form print-off<?php if (get_search_query()) echo ' search-form--filled' ?>">
    <p class="search-form__inner">
        <label for="<?php echo $unique_id; ?>" class="search-form__label">
            <?php echo $theme->inline_svg($theme->dist_url('/svg/search.svg')) ?>
            <span class="visually-hidden">
                <?php _e('Rechercher', CardinalTheme::TEXTDOMAIN) ?>
            </span>
        </label>
        <!-- @formatter:off -->
        <input type="search" id="<?php echo $unique_id; ?>" class="search-form__input"
               value="<?php echo get_search_query() ?>" name="s"/><!--
        --><button type="submit" class="search-form__submit">
            <?php _e('OK', CardinalTheme::TEXTDOMAIN) ?>
        </button>
        <!-- @formatter:on -->
    </p>
</form>