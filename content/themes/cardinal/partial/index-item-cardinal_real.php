<div class="card-real">
    <h2 class="card__title">
        <a class="card__link" href="<?php the_permalink() ?>">
            <?php the_title() ?>
        </a>
    </h2>
    <p class="card-real__place">
        <?php the_field('place') ?>
    </p>
    <?php the_post_thumbnail('medium', [
        'class' => 'card-real__thumb',
        'sizes' => '',
    ]) ?>
</div>