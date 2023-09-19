<?php
/* Template name: Actresses / Actors / Directors page Template */
get_header();

$page_id = get_the_ID();
$page_title = get_the_title();
$children_pages = get_children_pages($page_id);
$person_of_the_moment_id = get_post_meta($page_id, '_person_of_the_moment', true);
?>

<div class="page-body" role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>

    <div class="page-body__content studios">
        <div class="studios__headline">
            <h1><?php echo get_the_title(); ?></h1>
            <?php echo get_the_excerpt(); ?>
        </div>
        <div class="studios__banner">
            <div class="studios__banner__img">
                <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($page_id), 'source'); ?>"
                     alt="<?php echo get_the_title(); ?>">
            </div>
            <div class="studios__banner__content">
                <h2>
                    <?php _e('Actrice du moment', THEME_LANGUAGE_DOMAIN); ?>
                    <?php echo get_the_title($person_of_the_moment_id); ?>
                </h2>
                <p><?php echo get_the_excerpt($person_of_the_moment_id); ?></p>
                <a href="<?php echo get_the_permalink($person_of_the_moment_id); ?>" class="btn btn--main"><?php _e('Films', THEME_LANGUAGE_DOMAIN); ?></a>
            </div>
        </div>
        <div class="studios__photos">
            <?php while ($children_pages->have_posts()) : ?>
                <?php $children_pages->the_post(); ?>

                <div class="studios__photo">
                    <a href="<?php echo get_the_permalink(); ?>">
                        <img src="<?php echo $image_url = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'source'); ?>" alt="">
                    </a>
                    <div class="studios__photo__title"><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
