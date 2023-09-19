<?php
/* Template Name: Dorcel Girls page template */
get_header();
$page_img_src = wp_get_attachment_image_url(get_post_thumbnail_id(get_the_ID()), 'source');
$page_title = get_the_title();
$actresses = get_children_pages(safe_get_page_id_by_template('page-actresses.php'));
?>

<div class="page-body studios__fullwidth" role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>

    <div class="studios__headline">
        <div class="studios__headline__overlay nomask">
            <img class="studios__headline__banner"
                 src="<?php echo $page_img_src; ?>"
                 alt="<?php echo $page_title; ?>">
            <h1 class="left"><?php echo $page_title; ?></h1>
        </div>
        <p><?php echo get_the_excerpt(); ?></p>
    </div>
    <div class="studios__banner--desktop">
        <img src="<?php echo $page_img_src; ?>" alt="<?php echo $page_title; ?>">
    </div>
    <div class="studios__photos" id="more-content-container">
        <?php while ($actresses->have_posts()) : ?>
            <?php
            $actresses->the_post();
            $image_id = get_post_thumbnail_id(get_the_ID());
            $image_src = get_static_image('disclaimer.jpg');

            if (!empty($image_id)) {
                $image_src = wp_get_attachment_image_url($image_id, 'source');;
            }
            ?>
            <div class="studios__photo__item">
                <a href="<?php echo get_the_permalink(); ?>" class="studios__photo">
                    <img src="<?php echo $image_src; ?>" alt="<?php echo get_the_title(); ?>">
                </a>
                <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
            </div>
        <?php endwhile; ?>

        <?php wp_reset_postdata(); ?>
    </div>
    <div class="studios__btn__wrap">
        <button href="#" class="btn btn--main" id="more-content-btn"><?php _e('Voir la suite', THEME_LANGUAGE_DOMAIN); ?></button>
    </div>
</div>

<?php get_footer();
