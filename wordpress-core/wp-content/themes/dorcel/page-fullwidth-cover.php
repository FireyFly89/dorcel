<?php
/*
  Template Name: Full-width page with cover image
*/

get_header();
?>

<div class="page-body full-width-cover-image" role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>
    <div class="page-body__content">
        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>
