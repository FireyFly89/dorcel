<?php
/*
  Template Name: Blog
*/

get_header();
$featured_image_url = get_the_post_thumbnail_url(get_the_ID(),'full');
?>
<div class="page-body page-blog" role="main">
    <div class="page-body__content__pre__container">
        <div class="page-body__content__pre">
            <?php echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>'); ?>
        </div>
    </div>
    <div class="page-blog__container">
        <div class="blog__slider">
            <?php dynamic_sidebar('blog_slider'); ?>
        </div>
        <div class="page-body__header">
            <?php echo get_the_content(null, false, get_page_by_path('blog')); ?>
        </div>
        <div class="page-body__content">
            <?php dynamic_sidebar('blog_widgets'); ?>
        </div>
        <div class="page-body__sidebar">
            <?php dynamic_sidebar('blog_sidebar'); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
