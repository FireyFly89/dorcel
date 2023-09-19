<?php
/* Template Name: Director page template */
get_header();
$page_id = get_the_ID();
$page_title = get_the_title();
$widget_helper = new WidgetHelper();
$connected_tags = safe_get_serialized_custom_tags($page_id, '_tag_connection');
$connected_tags[] = "films";
$connected_films_highlight = $widget_helper->store_get_posts(get_non_duplicate_posts([
    'tag' => implode(',', $connected_tags),
    'posts_per_page' => 8,
]));
$connected_films = $widget_helper->store_get_posts(get_non_duplicate_posts([
    'tag' => implode(',', $connected_tags),
    'posts_per_page' => 8,
]));
?>

<div class="page-body" role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>

    <div class="page-body__content director">
        <div class="actress__banner">
            <div class="actress__banner__img">
                <img src="<?php echo $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($page_id), 'source'); ?>" alt="<?php echo get_the_title(); ?>">
            </div>
            <div class="actress__banner__content">
                <span class="actress__banner__subtitle"><?php _e("Réalisateur", THEME_LANGUAGE_DOMAIN); ?></span>
                <div class="actress__banner__title">
                    <h1><?php echo $page_title; ?></h1>
                    <img
                        src="<?php echo $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($page_id), 'source'); ?>"
                        alt="<?php echo get_the_title(); ?>">
                </div>
                <p><?php echo get_the_excerpt(); ?></p>
            </div>
        </div>

        <div class="studios__content__wrap director__collection">
            <div class="studios__collection">
                <div class="studios__collection__item--featured">
                    <h2 class="section-title--left"><?php _e('Son nouveau film', THEME_LANGUAGE_DOMAIN); ?></h2>

                    <?php while ($connected_films_highlight->have_posts()) : ?>
                        <?php
                        $connected_films_highlight->the_post();
                        $image_id = get_post_thumbnail_id(get_the_ID());
                        $image_src = get_static_image('disclaimer.jpg');

                        if (!empty($image_id)) {
                            $image_src = wp_get_attachment_image_url($image_id, 'source');;
                        }
                        ?>

                        <div class="studios__collection__item__img">
                            <a href="<?php echo get_the_permalink(); ?>">
                                <img src="<?php echo $image_src; ?>" alt="">
                            </a>
                        </div>
                        <div class="studios__collection__item__content">
                            <h3><?php echo get_the_title(); ?></h3>
                            <p><?php echo get_the_excerpt(); ?></p>
                        </div>

                    <?php endwhile; ?>

                    <?php wp_reset_postdata(); ?>
                </div>

                <div class="studios__collection__item--featured">
                    <h2 class="section-title--left"><?php _e('Son plus grand succés', THEME_LANGUAGE_DOMAIN); ?></h2>
                    <div class="studios__collection__item__img">
                        <a href="#">
                            <img src="<?php echo get_static_image('disclaimer.jpg'); ?>" alt="">
                        </a>
                    </div>
                    <div class="studios__collection__item__content">
                        <h3><?php _e('Lorem ipsum dolor sit amet', THEME_LANGUAGE_DOMAIN); ?></h3>
                        <p><?php _e('Lorem ipsum dolor sit amet, consectetur adipiscing elit. In in justo accumsan, semper tortor sed, tincidunt metus. Cras finibus feugiat metus, eu blandit odio auctor in.', THEME_LANGUAGE_DOMAIN); ?></p>
                    </div>
                </div>
            </div>

            <h2 class="section-title--left">
                <?php _e('Films', THEME_LANGUAGE_DOMAIN); ?>
                <br>
                <a href="#"><?php _e('Voir les films >', THEME_LANGUAGE_DOMAIN); ?></a>
            </h2>
            <div class="studios__catalog" id="more-content-container">
                <?php while ($connected_films->have_posts()) : ?>
                    <?php
                    $connected_films->the_post();
                    $image_id = get_post_thumbnail_id(get_the_ID());
                    $image_src = get_static_image('disclaimer.jpg');

                    if (!empty($image_id)) {
                        $image_src = wp_get_attachment_image_url($image_id, 'source');;
                    }
                    ?>

                    <div class="studios__catalog__item">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <img src="<?php echo $image_src; ?>" alt="">
                        </a>
                    </div>
                <?php endwhile; ?>

                <?php wp_reset_postdata(); ?>
            </div>
            <div class="studios__btn__wrap">
                <button href="#" class="btn btn--main" id="more-content-btn"><?php _e('Voir la suite', THEME_LANGUAGE_DOMAIN); ?></button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
