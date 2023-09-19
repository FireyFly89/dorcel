<?php
get_header();
$template_type = get_post_meta(get_the_ID(), '_article_template', true) === 'fullwidth_template';
$extra_class = $template_type === true ? 'full-width ' : '';
$widget_helper = new WidgetHelper();
$post_date = get_the_date('Y-m-d G:i');
$categories = get_the_category();
$featured_image_url = get_the_post_thumbnail_url(get_the_ID(),'full');

if (!empty($categories)) {
    $query_args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'cat' => $categories[0]->cat_ID
    ];
}
?>

<?php if ($featured_image_url) { ?>
    <div class="article__banner">
        <img src="<?php echo $featured_image_url; ?>" alt="<?php echo get_the_title(); ?>">
    </div>
<?php } ?>

<div class="page-body" role="main">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <?php $extra_class .= (is_breadcrumbs(get_the_ID(), 'post') ? 'breadcrumb ' : ''); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('page-body__content ' . $extra_class); ?>>
            <?php maybe_show_breadcrumbs(get_the_ID(), 'post'); ?>

            <div class="article__header">
                <h1 class="article__title"><?php echo get_the_title(); ?></h1>
            </div>
            <div class="article__content">
                <?php the_content(); ?>
                <div class="article__share">
                    <div class="btn btn--social btn--facebook obfuscated-url" data-type="faceshare" data-target="<?php echo get_permalink(); ?>">
                        <?php _e('Partager sur Facebook', THEME_LANGUAGE_DOMAIN); ?>
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <div class="btn btn--social btn--twitter obfuscated-url" data-type="twitshare" data-target="<?php echo get_permalink(); ?>">
                        <?php _e('Partager sur Twitter', THEME_LANGUAGE_DOMAIN); ?>
                        <i class="fab fa-twitter"></i>
                    </div>
                </div>
            </div>

            <div class="article__siblings">
                <?php if (isset($query_args)) : ?>
                    <?php $previous_article = new WP_Query(array_merge($query_args, [
                        'date_query' => [
                            'column' => 'post_date',
                            'before' => $post_date,
                        ]]));

                    if ($previous_article->have_posts()) :
                        $post = $previous_article->posts[0];
                        setup_postdata($post);
                        $post_link_params = $url_handler->get_link_parameters(get_permalink())
                        ?>

                        <div class="article--prev">
                            <?php echo $widget_helper->get_widget_thumbnail($post, 'article_normal'); ?>

                            <div class="article--prev__meta">
                                <i class="fas fa-chevron-left"></i>
                                <a <?php echo $post_link_params; ?> class="article--prev__title">
                                    <?php echo $widget_helper->trim_by_appearance(esc_html(get_the_title()), 'article_sidebar'); ?>
                                </a>
                            </div>
                        </div>

                        <?php wp_reset_query(); ?>
                    <?php endif; ?>

                    <?php $next_article = new WP_Query(array_merge($query_args, [
                        'date_query' => [
                            'column' => 'post_date',
                            'after' => $post_date,
                        ]]));

                    if ($next_article->have_posts()) :
                        $post = $next_article->posts[0];
                        setup_postdata($post);
                        $post_link_params = $url_handler->get_link_parameters(get_permalink())
                        ?>

                        <div class="article--next">
                            <?php echo $widget_helper->get_widget_thumbnail($post, 'article_normal'); ?>

                            <div class="article--next__meta">
                                <a <?php echo $post_link_params; ?> class="article--next__title">
                                    <?php echo $widget_helper->trim_by_appearance(esc_html(get_the_title()), 'article_sidebar'); ?>
                                </a>
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>

                        <?php wp_reset_query(); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php dynamic_sidebar('article_under'); // Query has been reset here, thus we can do a new query for next and previous article ?>

        </article>
    <?php endwhile; ?>

    <?php if ($template_type !== true) : ?>
        <div class="page-body__sidebar">
            <?php dynamic_sidebar('article_sidebar'); ?>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
