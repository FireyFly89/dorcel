<?php
/* Template Name: Actors & Actresses Photos page template */
get_header();
$parent_page_id = wp_get_post_parent_id(get_the_ID());
$banner_image_id = get_post_thumbnail_id(get_the_ID());

if (!empty($gallery = get_post_meta(get_the_ID(), '_gallery_images', true))) {
    $gallery = unserialize($gallery);
}
?>

<div class="page-body " role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>
    <div class="page-body__content studios">
        <div class="studios__banner">
            <div class="studios__banner__img">
                <img src="<?php echo wp_get_attachment_image_url($banner_image_id, 'source'); ?>" alt="<?php echo get_the_title(); ?>">
            </div>
            <div class="studios__banner__content">
                <h1><?php echo get_the_title(); ?></h1>
            </div>
        </div>

        <div class="studios__photos" columns="3">
            <?php if (!empty($gallery)) : ?>
                <?php foreach($gallery as $image) : ?>
                    <div class="studios__photo">
                        <a href="<?php echo wp_get_attachment_image_url($image, 'source'); ?>">
                            <img class="unbordered" src="<?php echo wp_get_attachment_image_url($image, 'large'); ?>" alt="<?php echo get_the_title($parent_page_id); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer();
