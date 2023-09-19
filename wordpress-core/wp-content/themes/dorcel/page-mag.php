<?php
/*
*  Template Name: Blog
*/

get_header();
$widget_helper = new WidgetHelper();
$paged = get_query_var("paged");
$page_number = !empty($paged) ? $paged : 0;
$query_result = $widget_helper->store_get_posts([
    'post_type' => 'magazine',
    'post_status' => 'publish',
    'post__not_in' => ArticleStorage::get_instance()->get_articles(),
    'posts_per_page' => 6,
    'paged' => $page_number,
]);
$posts = $query_result->get_posts();
$pagination = $widget_helper->widget_pagination($query_result->max_num_pages);
?>

<div class="page-body page-magazine" role="main">
    <div class="page-body__content__pre">
        <?php echo get_the_content(null, false, get_page_by_path('mag')); ?>
    </div>

    <div class="page-body__content">
        <?php foreach ($posts as $post) : ?>
            <?php setup_postdata($post); ?>
            <?php $link_data = $url_handler->get_link_parameters(get_permalink($post->ID)); ?>

            <div class="magazine">
                <?php echo $widget_helper->get_widget_thumbnail($post, '', 'magazine-image'); ?>
                <div class="magazine__meta">
                    <div
                        class="magazine__meta__category"><?php echo $widget_helper->get_the_post_categories($post->ID, 'article_normal'); ?></div>
                    <a class="mag" <?php echo $link_data; ?>>
                        <div class="magazine__meta__title"><?php echo esc_html($post->post_title); ?></div>
                        <div
                            class="magazine__meta__excerpt"><?php echo wp_kses_post((empty($post->post_excerpt) ? $widget_helper->trim_by_appearance($post->post_content, 10) : $post->post_excerpt)); ?></div>
                    </a>
                </div>
            </div>

        <?php endforeach; ?>

        <?php if (!empty($pagination)) : ?>
            <div class="pagination">
                <?php foreach ($pagination as $page) {
                    echo $page;
                } ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
