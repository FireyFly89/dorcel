<?php
/* Template Name: Actress/Actor page template */
get_header();
$page_id = get_the_ID();
$banner_image_id = get_post_thumbnail_id(get_the_ID());
$banner_image_url = wp_get_attachment_image_url($banner_image_id, 'source');
$widget_helper = new WidgetHelper();
$connected_tags = safe_get_serialized_custom_tags($page_id, '_tag_connection');
$connected_posts_news = $widget_helper->store_get_posts(get_non_duplicate_posts([
    'tag' => implode(',', array_merge($connected_tags, ['news'])),
    'posts_per_page' => 8,
]));
$connected_posts_films = $widget_helper->store_get_posts(get_non_duplicate_posts([
    'tag' => implode(',', array_merge($connected_tags, ['films'])),
    'posts_per_page' => 8,
]));
$photo_page = get_pages([
    'parent' => $page_id,
    'post_type' => 'page',
    'meta_key' => '_wp_page_template',
    'hierarchical' => 0,
    'meta_value' => 'page-photos.php',
]);

$photos_page_id = "";

if (!empty($photo_page)) {
    foreach($photo_page as $photo_page_data) {
        $photos_page_id = $photo_page_data->ID;
    }
}

if (!empty($gallery = get_post_meta($photos_page_id, '_gallery_images', true))) {
    $gallery = unserialize($gallery);
}
?>

<div class="page-body full-width-cover-image" role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>
    <div class="page-body__content studios">
        <div class="actress__banner">
            <div class="actress__banner__img">
                <img src="<?php echo $banner_image_url; ?>" alt="<?php echo get_the_title(); ?>">
            </div>
            <div class="actress__banner__content">
                <div class="actress__banner__title">
                    <h1><?php echo get_the_title(); ?></h1>
                    <img src="<?php echo $banner_image_url; ?>" alt="<?php echo get_the_title(); ?>">
                </div>
                <p><?php echo get_the_excerpt(); ?></p>
                <a href="#" class="btn btn--main"><?php _e('Lorem ipsum dolor', THEME_LANGUAGE_DOMAIN); ?></a>
            </div>
        </div>
        <div class="actress__content">
            <?php echo get_the_content(); ?>
        </div>

        <h2 class="section-title--left"><?php _e('Actualités', THEME_LANGUAGE_DOMAIN); ?></h2>
        <div class="actress__article__slider__wrap">
            <div class="actress__article__slider" id="actress-article-slider">
                <?php while ($connected_posts_news->have_posts()) : ?>
                    <?php
                    $connected_posts_news->the_post();
                    $image_id = get_post_thumbnail_id(get_the_ID());
                    $image_src = get_static_image('disclaimer.jpg');

                    if (!empty($image_id)) {
                        $image_src = wp_get_attachment_image_url($image_id, 'source');;
                    }

                    if (!empty($categories = get_the_category())) {
                        $categories = $categories[0]->name;
                    } else {
                        $categories = "";
                    }
                    ?>

                    <div class="actress__article__item">
                        <div class="actress__article__item__img">
                            <a href="<?php echo get_the_permalink(); ?>">
                                <img src="<?php echo $image_src; ?>" alt="<?php echo get_the_title(); ?>">
                            </a>
                        </div>
                        <div class="actress__article__item__content">
                            <p class="category"><?php echo $categories; ?></p>
                            <h3>
                                <a href="<?php echo get_the_permalink(); ?>">
                                    <?php echo get_the_title(); ?>
                                </a>
                            </h3>
                            <p><?php echo get_the_excerpt(); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <h2 class="section-title--left">
            <?php _e('Films', THEME_LANGUAGE_DOMAIN); ?>
            <br>
            <a href="#"><?php _e('Voir les films >', THEME_LANGUAGE_DOMAIN); ?></a>
        </h2>
        <div class="actress__image__slider__wrap">
            <div class="actress__image__slider" id="actress-films-slider">
                <?php while ($connected_posts_news->have_posts()) : ?>
                    <?php
                    $connected_posts_news->the_post();
                    $image_id = get_post_thumbnail_id(get_the_ID());
                    $image_src = get_static_image('disclaimer.jpg');

                    if (!empty($image_id)) {
                        $image_src = wp_get_attachment_image_url($image_id, 'source');;
                    }
                    ?>

                    <div class="actress__image__item">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <img src="<?php echo $image_src; ?>" alt="<?php echo get_the_title(); ?>">
                        </a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>

        <h2 class="section-title--left">
            <?php _e('Photos', THEME_LANGUAGE_DOMAIN); ?>
            <br>
            <a href="#"><?php _e('Voir les photos >', THEME_LANGUAGE_DOMAIN); ?></a>
        </h2>

        <div class="actress__image__slider__wrap">
            <div class="actress__image__slider" id="actress-photos-slider">
                <?php if (!empty($gallery)) : ?>
                    <?php foreach($gallery as $image) : ?>
                        <div class="actress__image__item">
                            <a href="<?php echo wp_get_attachment_image_url($image, 'source'); ?>">
                                <img src="<?php echo wp_get_attachment_image_url($image, 'large'); ?>" alt="<?php echo get_the_title($page_id); ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
