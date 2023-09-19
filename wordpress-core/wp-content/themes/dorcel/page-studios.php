<?php
/* Template Name: Studios page template */

get_header();
$page_id = get_the_ID();
$page_title = get_the_title();
$children_pages = get_children_pages($page_id);
?>

<div class="page-body " role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>
    <div class="page-body__content studios">
        <div class="studios__headline">
            <div class="studios__headline__overlay">
                <img src="<?php echo $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($page_id), 'source'); ?>"
                     alt="<?php echo $page_title; ?>">
                <h1><?php echo $page_title; ?></h1>
            </div>
            <p><?php echo get_the_excerpt(); ?></p>
        </div>

        <div class="studios__list">
            <?php while ($children_pages->have_posts()) : ?>
                <?php $children_pages->the_post(); ?>
                <?php $image_id = get_post_thumbnail_id(get_the_ID()); ?>

                <div class="studios__item">
                    <div class="studios__item__img">
                        <a href="<?php echo get_the_permalink(); ?>">
                            <img src="<?php echo $image_url = wp_get_attachment_image_url($image_id, 'source'); ?>"/>
                        </a>
                    </div>
                    <div class="studios__item__content">
                        <div class="studios__item__content__title"><?php echo get_the_title(); ?></div>
                        <p><?php echo get_the_excerpt(); ?></p>
                        <a href="<?php echo get_the_permalink(); ?>"><?php _e('Voir tous les fims Dorcel >', THEME_LANGUAGE_DOMAIN) ?></a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php wp_reset_postdata(); ?>
    </div>
</div>

<?php get_footer(); ?>
