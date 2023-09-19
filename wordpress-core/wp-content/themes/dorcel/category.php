<?php
get_header();
$category = get_query_var('category_name');
$sidebar = sanitize_title(CATEGORY_SIDEBAR_WIDGET_VARIATIONS[0]);
$term_id = get_queried_object()->term_id;
$widget_helper = new WidgetHelper();

if (!empty($selected_sidebar = get_term_meta($term_id, 'category_sidebar_selector', true))) {
    $sidebar = $selected_sidebar;
}
?>
<div class="page-body" role="main">
    <?php maybe_show_breadcrumbs($term_id, 'category'); ?>

    <div class="page-body__content__pre">
        <h1 class="page-body__title"><?php _e('Category'); ?> - <?php echo $category; ?></h1>
    </div>

    <div class="page-body__content__pre__extra">
        <?php echo category_description(); ?>
    </div>

    <div class="page-body__content">
        <?php
        the_widget('PostArticleWidget', [
            PostArticleWidget::QUERY_METHOD => "categories",
            PostArticleWidget::QUERY_METHOD_INPUT => $category,
            PostArticleWidget::ARTICLE_NUMBER => get_option('posts_per_page'),
            PostArticleWidget::APPEARANCE => array_keys(WIDGET_APPEARANCES)[0],
        ], ['pagination' => true]);

        $highlighted_posts = $widget_helper->store_get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post__not_in' => ArticleStorage::get_instance()->get_articles(),
            'posts_per_page' => 8,
            'paged' => ($page_num = get_query_var('paged')) ? $page_num : 1,
            'category_name' => $category,
            'meta_query' => [
                [
                    'key' => '_article_highlighting',
                    'value' => 'highlighted_article'
                ],
            ]
        ]);
        ?>

        <?php if ($highlighted_posts->have_posts()) : ?>
            <div class="custom-widget post_article_widget article_normal">
                <h3 class="custom-widget__title"><?php _e('Mise en avant articles d’une catégorie', THEME_LANGUAGE_DOMAIN); ?></h3>

                <?php foreach ($highlighted_posts->posts as $post) : ?>
                    <?php setup_postdata($post); ?>
                    <?php $post_url = $url_handler->get_link_parameters(get_permalink($post->ID)); ?>

                    <div class="custom-widget__article">
                        <?php echo $widget_helper->get_widget_thumbnail($post, 'article_normal'); ?>

                        <div class="custom-widget__article__content">
                            <?php echo $widget_helper->get_the_post_categories($post->ID, 'article_normal'); ?>
                            <?php echo sprintf('<a class="custom-widget__article__title" %s><span>%s</span></a>',
                                $post_url,
                                $widget_helper->trim_by_appearance(esc_html($post->post_title), 'article_normal')
                            ); ?>
                            <?php echo sprintf('<a class="custom-widget__article__excerpt" %s><span>%s</span></a>',
                                $post_url,
                                wp_kses_post((empty($post->post_excerpt) ? $widget_helper->trim_by_appearance($post->post_content, 'article_normal', 'content') : wp_kses_post($post->post_excerpt))));
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
    <div class="page-body__sidebar">
        <?php dynamic_sidebar($sidebar); ?>
    </div>
</div>

<?php get_footer(); ?>
