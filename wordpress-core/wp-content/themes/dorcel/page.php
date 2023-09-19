<?php
/*
  Template Name: Normal page
*/

get_header();
?>

<div class="page-body" role="main">
    <div class="page-body__content__pre">
        <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
    </div>

    <div class="page-body__content full-width">
        <div class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
