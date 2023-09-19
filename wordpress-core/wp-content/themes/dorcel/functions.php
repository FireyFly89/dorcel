<?php
$sls_theme_version = "0.1.1";

require_once(get_template_directory() . "/classes/UrlHandler.php");
require_once(get_template_directory() . "/classes/OverrideMenuWalker.php");
$url_handler = new UrlHandler();

const THEME_LANGUAGE_DOMAIN = 'dorcel-blog';
const CATEGORY_SIDEBAR_WIDGET_VARIATIONS = [
    'Category sidebar variation 1',
    'Category sidebar variation 2',
    'Category sidebar variation 3',
    'Category sidebar variation 4',
    'Category sidebar variation 5',
];

add_action('wp_ajax_media_src_by_id', 'get_media_src');

if (!function_exists('load_scripts')) {
    function load_scripts()
    {
        global $sls_theme_version;

        // Load main stylesheet
        wp_enqueue_style('style', get_stylesheet_uri(), [], $sls_theme_version);
        //wp_enqueue_style('fontawesome', "https://use.fontawesome.com/releases/v5.7.2/css/all.css");

        wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/5273c16679.js', [], $sls_theme_version, true);
        wp_enqueue_script('jquery-cookie', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', ['jquery'], $sls_theme_version, true);

        // Load main javascript
        wp_enqueue_script('main', (get_template_directory_uri() . '/assets/scripts/main.js'), ['jquery', 'jquery-cookie', 'slick-scripts'], $sls_theme_version, false);

        // Localize useful variables, making them avaialable in javascript file (by handle name)
        wp_localize_script('main', 'resolutions', [
            'hd' => 1600,
            'hdReady' => 1366,
            'large' => 1200,
            'medium' => 992,
            'small' => 768,
            'extraSmall' => 480,
            'old' => 375,
        ]);
    }

    add_action('wp_enqueue_scripts', 'load_scripts');
}

if (!function_exists('load_admin_scripts')) {
    function load_admin_scripts()
    {
        wp_enqueue_style('dorcel-generic-admin-styles', get_template_directory_uri() . '/admin_styles.css');
        wp_enqueue_style('select2-css', get_template_directory_uri() . '/assets/less/select2.min.css');
        wp_enqueue_script('select2-js', (get_template_directory_uri() . '/assets/scripts/select2.full.min.js'), ['jquery']);
        wp_enqueue_script('dorcel-generic-admin', (get_template_directory_uri() . '/assets/scripts/admin.js'), ['jquery', 'select2-js']);
        wp_localize_script('dorcel-generic-admin', 'admin_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'security_nonce' => wp_create_nonce("admin_nonce"),
        ]);
    }
}

add_action('admin_enqueue_scripts', 'load_admin_scripts');

// Register all the known widget areas
function register_widget_areas()
{
    register_sidebar([
        'name' => 'Homepage cover slider',
        'id' => 'home_cover_slider',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Homepage Movies',
        'id' => 'home_movies_widget',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Homepage Video',
        'id' => 'home_video_widget',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Homepage Article Slider',
        'id' => 'home_article_slider',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Blog Slider',
        'id' => 'blog_slider',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Blog Sidebar',
        'id' => 'blog_sidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Blog Widgets',
        'id' => 'blog_widgets',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    foreach (CATEGORY_SIDEBAR_WIDGET_VARIATIONS as $sidebar) {
        register_sidebar([
            'name' => $sidebar,
            'id' => sanitize_title($sidebar),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => '',
        ]);
    }

    register_sidebar([
        'name' => 'Article Sidebar',
        'id' => 'article_sidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);

    register_sidebar([
        'name' => 'Article Under',
        'id' => 'article_under',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
    ]);
}

add_action('widgets_init', 'register_widget_areas');

// Set-up the theme
function theme_supports()
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('post-formats');

    // New image size for /mag page featured images on magazines
    add_image_size('magazine-image', 281, 393);

    register_nav_menus([
        'header_section' => __('Header section'),
        'footer_section' => __('Footer section'),
        'footer_other_section' => __('Footer other section'),
    ]);
}

add_action('after_setup_theme', 'theme_supports');

add_filter('wp_calculate_image_sizes', function ($sizes, $size) {
    $image_sizes = [
        'magazine-image' => [
            'name' => 'Magazine size',
            'width' => 281,
            'height' => 393,
            'mobile_res' => '48vw',
        ]
    ];

    foreach ($image_sizes as $image_id => $data) {
        if (array_key_exists('width', $data) && array_key_exists('height', $data) && array_key_exists('mobile_res', $data)) {
            $aspect_ratio_original = round($data['height'] / $data['width'], 2);
            $aspect_ratio_uploaded = round($size[1] / $size[0], 2);

            if ($aspect_ratio_original === $aspect_ratio_uploaded) {
                $sizes = '(min-width: 768px) ' . $data['width'] . 'px, ' . $data['mobile_res'];
            }
        }
    }
    return $sizes;
}, 10, 2);

// Utility function to dump more readable data
function pre_dump($data)
{
    echo "<pre style='position: relative;'>";
    var_dump($data);
    echo "</pre>";
}

function is_url_internal($url)
{
    $internal_url = $_SERVER['HTTP_HOST'];

    if (strpos($url, $internal_url) !== false) {
        return true;
    }

    return false;
}

// Retrieves a single-level menu structure
function get_named_menu($elem_name, $parent_class)
{
    $menu_elems = wp_get_nav_menu_items($elem_name);

    if (!$menu_elems) {
        return false;
    }

    $result = "";

    foreach ($menu_elems as $menu_elem) {
        $target = is_url_internal($menu_elem->url) ? '' : '_blank';
        $result .= sprintf('<li class="%s__elem"><a href="%s" target="%s">%s</a></li>', $parent_class, $menu_elem->url, $target, __($menu_elem->title));
    }

    return $result;
}

// Retrieves a multi-level menu structure (2 level only for now)
function get_named_menu_hierarchically($elem_name, $parent_class)
{
    $menu_elems = wp_get_nav_menu_items($elem_name);

    if (!$menu_elems) {
        return false;
    }

    $sub_menu_name = "sub_menu";
    $sorted_menu_elems = [];

    // Menu order automatically gets sorted by wordpress, so we don't need to manually ensure sorting
    foreach ($menu_elems as $menu_elem) {
        if ($menu_elem->post_status !== "publish") {
            continue;
        }

        $current_data = [
            "id" => $menu_elem->ID,
            "url" => $menu_elem->url,
            "title" => $menu_elem->title,
        ];

        if ($menu_elem->menu_item_parent != 0) {
            $sorted_menu_elems[$menu_elem->menu_item_parent][$sub_menu_name][$menu_elem->ID] = $current_data;
            continue;
        }

        $sorted_menu_elems[$menu_elem->ID] = $current_data;
    }

    $result = "";
    $fontawesome_icon_list = json_decode(file_get_contents(get_template_directory() . '/assets/data/fontawesome.json'));

    foreach ($sorted_menu_elems as $sorted_menu_elem) {
        $selected_icon = get_post_meta($sorted_menu_elem["id"], '_menu_item_icon', true);
        $result .= sprintf('<div class="%s__elem__main__wrapper">', $parent_class);
        $result .= sprintf('<div class="%s__elem__main__head">', $parent_class);

        if (!empty($selected_icon)) {
            foreach ($fontawesome_icon_list as $icon_family => $icons) {
                foreach ($icons as $icon_name => $icon_entity) {
                    if ($selected_icon == $icon_name) {
                        $selected_icon = ($icon_family == "brands" ? "fab" : "fa") . " fa-" . $selected_icon;
                        break 2;
                    }
                }
            }

            $result .= sprintf('<i class="%s"></i>', $selected_icon);
        }

        $result .= sprintf('<span class="%s__elem__main">%s</span>', $parent_class, __($sorted_menu_elem['title']));
        $result .= '</div>';

        if (!empty($sorted_menu_elem[$sub_menu_name])) {
            $result .= iterate_sub_menu($sorted_menu_elem[$sub_menu_name], $parent_class);
        }

        $result .= '</div>';
    }

    return $result;
}

// This function is used solely for iterating over sub menus, thus it is only a support function for (get_named_menu_hierarchically())
function iterate_sub_menu($menu_elems, $parent_class)
{
    $result = sprintf('<ul class="%s__elem__sub__wrapper">', $parent_class);

    foreach ($menu_elems as $menu_elem) {
        $target = is_url_internal($menu_elem['url']) ? '' : '_blank';
        $selected_desc = get_post_meta($menu_elem["id"], '_menu_item_under_desc', true);
        $result .= sprintf('<li class="%s__elem__sub"><a href="%s" target="%s">%s<p>%s</p></a></li>', $parent_class, $menu_elem['url'], $target, __($menu_elem['title']), $selected_desc);
    }

    $result .= "</ul>";
    return $result;
}

// Retrieves a logo from the static images, adds 'bloginfo' as alt
function get_logo($elem_name, $image_name, $alt = "")
{
    if (empty($alt)) {
        $alt = get_bloginfo();
    }

    return sprintf('<img class="%s__logo" src="%s" alt="%s" />', $elem_name, get_static_image($image_name), $alt);
}

// Retrieves a static image from custom asset path
function get_static_image($image_name)
{
    return get_template_directory_uri() . "/assets/images/" . $image_name;
}

function add_category_fields($term)
{
    ?>
    <div class="form-field">
        <label for="category_sidebar_selector"><?php _e('Category sidebar variation', THEME_LANGUAGE_DOMAIN); ?></label>
        <?php echo sprintf('<select name="%s">%s</select>', 'category_sidebar_selector', get_category_sidebar_variation_options()); ?>
    </div>
    <div class="form-field">
        <input id="show_crumbs" type="radio" name="category_breadcrumbs_visibility" value="show_crumbs"/>
        <label for="show_crumbs"
               class="selectit"><?php _e('Show breadcrumbs for this category', THEME_LANGUAGE_DOMAIN); ?></label><br/>
        <input id="hide_crumbs" type="radio" name="category_breadcrumbs_visibility" value="hide_crumbs"
               checked="checked"/>
        <label for="hide_crumbs"
               class="selectit"><?php _e('Hide breadcrumbs for this category', THEME_LANGUAGE_DOMAIN); ?></label><br/>
    </div>
    <?php
}

add_action('category_add_form_fields', 'add_category_fields', 10, 2);

function extra_category_fields($category_object)
{
    $category_id = $category_object->term_id;
    $selected_sidebar = get_term_meta($category_id, 'category_sidebar_selector', true);
    $breadcrumbs_visibility = get_term_meta($category_id, 'category_breadcrumbs_visibility', true);

    if (empty($breadcrumbs_visibility)) {
        $breadcrumbs_visibility = 'show_crumbs';
    }
    ?>
    <tr class="form-field term-parent-wrap">
        <th scope="row">
            <label for="parent"><?php _e("Category sidebar variation", THEME_LANGUAGE_DOMAIN); ?></label>
        </th>
        <td>
            <?php echo sprintf('<select name="%s">%s</select>', 'category_sidebar_selector', get_category_sidebar_variation_options($selected_sidebar)); ?>
            <p class="description"><?php _e("Here you can select which sidebar variation you'd like to use for this category", THEME_LANGUAGE_DOMAIN); ?></p>
        </td>
    </tr>
    <tr class="form-field term-parent-wrap">
        <th scope="row">
            <label for="parent"><?php _e("Breadcrumbs visibility", THEME_LANGUAGE_DOMAIN); ?></label>
        </th>
        <td>
            <input id="show_crumbs" type="radio" name="category_breadcrumbs_visibility"
                   value="show_crumbs" <?php checked('show_crumbs', $breadcrumbs_visibility); ?> />
            <label for="show_crumbs"
                   class="selectit"><?php _e('Show breadcrumbs for this category', THEME_LANGUAGE_DOMAIN); ?></label><br/>
            <input id="hide_crumbs" type="radio" name="category_breadcrumbs_visibility"
                   value="hide_crumbs" <?php checked('hide_crumbs', $breadcrumbs_visibility); ?> />
            <label for="hide_crumbs"
                   class="selectit"><?php _e('Hide breadcrumbs for this category', THEME_LANGUAGE_DOMAIN); ?></label><br/>
        </td>
    </tr>
    <?php
}

add_action('category_edit_form_fields', 'extra_category_fields', 10, 2);

function get_category_sidebar_variation_options(string $selected_sidebar = "")
{
    if (empty(CATEGORY_SIDEBAR_WIDGET_VARIATIONS)) {
        return false;
    }

    $sidebar_variations = "";

    foreach (CATEGORY_SIDEBAR_WIDGET_VARIATIONS as $sidebar) {
        $sidebar_slug = sanitize_title($sidebar);
        $sidebar_variations .= sprintf('<option ' . ($sidebar_slug === $selected_sidebar ? 'selected' : '') . ' value="%s">%s</option>', $sidebar_slug, $sidebar);
    }

    return $sidebar_variations;
}

/*
 *
 */
function save_extra_category_fields($term_id, $tt_id)
{
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        return;
    }

    update_metadatas('term', (int)$term_id, [
        'category_sidebar_selector',
        'category_breadcrumbs_visibility',
    ]);
}

add_action('edit_category', 'save_extra_category_fields', 10, 2);
add_action('create_category', 'save_extra_category_fields', 10, 2);

/*
 * Adds extra field to the post screen enabling the user to be able to
 */
function breadcrumbs_visibility($post)
{
    $visibility = get_post_meta($post->ID, '_breadcrumbs_visibility', true);

    if (empty($visibility)) {
        $visibility = 'show_crumbs';
    }
    ?>
    <div class="alignleft">
        <input id="show_crumbs" type="radio" name="_breadcrumbs_visibility"
               value="show_crumbs"<?php checked('show_crumbs', $visibility); ?> />
        <label for="show_crumbs"
               class="selectit"><?php _e('Show breadcrumbs for this article', THEME_LANGUAGE_DOMAIN); ?></label><br/>
        <input id="hide_crumbs" type="radio" name="_breadcrumbs_visibility"
               value="hide_crumbs"<?php checked('hide_crumbs', $visibility); ?> />
        <label for="hide_crumbs"
               class="selectit"><?php _e('Hide breadcrumbs for this article', THEME_LANGUAGE_DOMAIN); ?></label><br/>
    </div>
    <br clear="all">
    <?php
}

/*
 * Adds extra field to the post screen enabling the user to be able to set a tag, that connects the page to posts that has this tag set
 */
function tag_connection($post)
{
    $tag_connections = get_post_meta($post->ID, '_tag_connection', true);
    $tags = get_tags();

    if (!empty($tag_connections)) {
        $tag_connections = unserialize($tag_connections);
    }
    ?>
    <div class="alignleft">
        <label for="tag_connection"
               class="selectit"><?php _e('Set this when you\'d like to connect this page with some other content through a tag', THEME_LANGUAGE_DOMAIN); ?></label>
        <br/>
        <input type="hidden" name="_tag_connection" value=""/>
        <select name="_tag_connection[]" id="tag_connection" class="form-control" multiple="multiple">
            <?php
            $tags_output = [];

            foreach ($tags as $tag) {
                $selected = false;

                if (is_array($tag_connections) && in_array($tag->slug, $tag_connections)) {
                    $selected = true;
                }

                echo sprintf('<option value="%s" %s>%s</option>', $tag->slug, $selected === true ? 'selected="selected"' : "", $tag->slug);
                $tags_output[] = $tag->slug;
            }

            if (is_array($tag_connections)) {
                foreach ($tag_connections as $tag_connection) {
                    if (!in_array($tag_connection, $tags_output)) {
                        echo sprintf('<option value="%s" selected="selected">%s</option>', $tag_connection, $tag_connection);
                    }
                }
            }
            ?>
        </select>
    </div>
    <br clear="all">
    <?php
}

/*
 * Adds extra field to the post screen enabling the user to be able to select the "Person of the moment"
 */
function person_of_the_moment($post)
{
    $person_page_id = get_post_meta($post->ID, '_person_of_the_moment', true);
    $children_pages = get_children_pages($post->ID);
    ?>
    <div class="alignleft">
        <?php if ($children_pages->have_posts()) : ?>
            <label for="person_of_the_moment"
                   class="selectit"><?php _e('Only set for collection pages (ex: Actresses, Actors)', THEME_LANGUAGE_DOMAIN); ?></label>
            <br/>
            <select name="_person_of_the_moment" id="person_of_the_moment">
                <?php
                foreach ($children_pages->get_posts() as $post) {
                    echo sprintf('<option value="%s" %s>%s</option>', $post->ID, ($person_page_id == $post->ID ? "selected" : ""), $post->post_title);
                }
                ?>
            </select>
        <?php else: ?>
            <?php _e('No children pages found!', THEME_LANGUAGE_DOMAIN); ?>
        <?php endif; ?>
    </div>
    <br clear="all">
    <?php
}

/*
 * Adds extra field to the post screen enabling user to be able to select the template type of the article
 */
function article_template($post)
{
    $type = get_post_meta($post->ID, '_article_template', true);

    if (empty($type)) {
        $type = 'normal_template';
    }
    ?>
    <div class="alignleft">
        <input id="normal_template" type="radio" name="_article_template"
               value="normal_template"<?php checked('normal_template', $type); ?> />
        <label for="normal_template"
               class="selectit"><?php _e('Display sidebar for article', THEME_LANGUAGE_DOMAIN); ?></label><br/>
        <input id="fullwidth_template" type="radio" name="_article_template"
               value="fullwidth_template"<?php checked('fullwidth_template', $type); ?> />
        <label for="fullwidth_template"
               class="selectit"><?php _e('Full-width article', THEME_LANGUAGE_DOMAIN); ?></label><br/>
    </div>
    <br clear="all">
    <?php
}

/*
 * Adds extra field to the post screen enabling user to be able to select the template type of the article
 */
function article_highlighting($post)
{
    $type = get_post_meta($post->ID, '_article_highlighting', true);

    if (empty($type)) {
        $type = 'non_highlighted_article';
    }
    ?>
    <div class="alignleft">
        <input id="non_highlighted_article" type="radio" name="_article_highlighting"
               value="non_highlighted_article"<?php checked('non_highlighted_article', $type); ?> />
        <label for="non_highlighted_article"
               class="selectit"><?php _e('Do not highlight the article in the category', THEME_LANGUAGE_DOMAIN); ?></label><br/>
        <input id="highlighted_article" type="radio" name="_article_highlighting"
               value="highlighted_article"<?php checked('highlighted_article', $type); ?> />
        <label for="highlighted_article"
               class="selectit"><?php _e('Highlight the article in the category', THEME_LANGUAGE_DOMAIN); ?></label><br/>
    </div>
    <br clear="all">
    <?php
}

function actress_gallery($post)
{
    $image_ids = [];
    $gallery = get_post_meta($post->ID, '_gallery_images', true);
    $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);

    if ($pageTemplate != 'page-photos.php') {
        // A bit clunky way, but it works fine... hides this element completely, if the template is not set to actress page
        echo '<style>#actress_gallery { display: none; }</style>';
        return $post;
    }

    if (!empty($gallery)) {
        $image_ids = unserialize($gallery);
    }
    ?>
    <div class="alignleft gallery-images-holder">
        <?php get_template_part('templates/gallery', 'image'); ?>

        <?php foreach ($image_ids as $image_id) {
            if (!empty($image_id)) {
                set_query_var('image_id', $image_id);
                get_template_part('templates/gallery', 'image');
            }
        } ?>
        <div class="button-wrapper">
            <button class="add-gallery-image"><?php _e('Add image', THEME_LANGUAGE_DOMAIN) ?></button>
        </div>
    </div>
    <br clear="all">
    <?php
    return $post;
}

/**
 * Returns a single image URL to an AJAX request by given media ID
 */
function get_media_src()
{
    check_ajax_referer('admin_nonce', 'security_nonce');

    if (empty($media_id = $_POST['media_field_id'])) {
        wp_die('Unknown media field id!');
    }

    $media_src = wp_get_attachment_image_url($media_id, 'thumbnail');

    if (!empty($media_src)) {
        wp_die(json_encode($media_src));
    }

    wp_die('Could not find media!');
}

/*
 * Runs when saving / updating post. (does functions that are required when saving a post ex: saves extra field's metadata)
 */
function save_post_options($post_id)
{
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
        return;
    }

    update_metadatas('post', (int)$post_id, [
        '_breadcrumbs_visibility',
        '_article_template',
        '_gallery_images',
    ]);
}

add_action('save_post', 'save_post_options');

function save_page_options($post_id)
{
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        return;
    }

    update_metadatas('post', (int)$post_id, [
        '_person_of_the_moment',
        '_tag_connection',
        '_gallery_images',
    ]);
}

add_action('save_post_page', 'save_page_options', 10, 2);

/*
 * Dynamic function call to Wordpress's built-in functions (update_post_metadata, update_term_metadata, etc..)
 */
function update_metadatas(string $meta_type, int $meta_id, array $metadatas)
{
    foreach ($metadatas as $metadata) {
        if (!empty($_POST[$metadata]) || (isset($_POST[$metadata]) && empty($_POST[$metadata]))) {
            if (is_array($_POST[$metadata])) {
                call_user_func('update_' . $meta_type . '_meta', $meta_id, $metadata, serialize(array_filter($_POST[$metadata])));
                continue;
            }

            call_user_func('update_' . $meta_type . '_meta', $meta_id, $metadata, $_POST[$metadata]);
        }
    }
}

/*
 * $socials should be an associative array that includes "icon" and "url"
 */
function get_social_elems(string $section, array $socials)
{
    if (empty($section)) {
        return "";
    }

    $social_elems = sprintf('<div class="%s-section__social">', $section);

    foreach ($socials as $social) {
        $social_elems .= sprintf('<div class="obfuscated-url" data-type="%s" data-target="%s">%s</div>',
            $social['type'],
            $social['param'],
            sprintf('<i class="fab fa-%s"></i>', $social['icon'])
        );
    }

    return $social_elems . '</div>';
}

function maybe_show_breadcrumbs($meta_id, $type)
{
    if ($type === 'post') {
        $meta_value = get_post_meta($meta_id, '_breadcrumbs_visibility', true);
    } else {
        $meta_value = get_term_meta($meta_id, 'category_breadcrumbs_visibility', true);
    }

    if ($meta_value !== 'hide_crumbs') {
        echo maybe_yoast_breadcrumb('<div class="page-body__breadcrumb">', '</div>');
    }
}

function maybe_yoast_breadcrumb($before = '', $after = '', $display = true)
{
    if (!is_plugin_active('wordpress-seo/wp-seo.php')) {
        return false;
    }

    return yoast_breadcrumb($before, $after, $display);
}

function is_breadcrumbs($meta_id, $type)
{
    if ($type === 'post') {
        $meta_value = get_post_meta($meta_id, '_breadcrumbs_visibility', true);
    } else {
        $meta_value = get_term_meta($meta_id, 'category_breadcrumbs_visibility', true);
    }

    if ($meta_value !== 'hide_crumbs') {
        return true;
    }

    return false;
}

// Register the custom Magazines post type
add_action('init', function () {
    global $wpdb;

    register_post_type('magazine', [
        'public' => true,
        'label' => __('Magazines', THEME_LANGUAGE_DOMAIN),
        'menu_icon' => 'dashicons-book',
        'taxonomies' => ['category'],
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);

    $query = $wpdb->query($wpdb->prepare(
        'SELECT ID FROM ' . $wpdb->posts . '
        WHERE post_title = "%s"
        AND post_type = "%s"
        AND post_status = "%s"',
        "Mag",
        "page",
        "publish"
    ));

    // We need to insert the "Mag" page for Magazines to be shown. (ONLY IF PAGE-MAG EXISTS)
    if (empty($query)) {
        wp_insert_post([
            'post_title' => 'Mag',
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'page'
        ]);
    }

    if (!empty($_GET['recount_term']) && !empty($_GET['recount_tax'])) {
        wp_update_term_count_now([$_GET['recount_term']], $_GET['recount_tax']);
    }

    $widget_helper = new WidgetHelper();
    $widget_helper->safe_set_current_site_locale();
});

function get_children_pages($parent_id)
{
    return new WP_Query([
        'post_type' => 'page',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'post_parent' => $parent_id,
        'order' => 'DESC',
        'orderby' => 'post_date'
    ]);
}

function get_non_duplicate_posts(array $args = [])
{
    return array_merge([
        'post_type' => 'post',
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'post_date',
        'post__not_in' => ArticleStorage::get_instance()->get_articles(),
    ], $args);
}

function safe_get_hardcoded_tag(string $tag_slug)
{
    if (!empty($tag_slug) && !empty($tag_data = get_term_by('slug', 'films', 'post_tag'))) {
        if (property_exists($tag_data, "term_id")) {
            return $tag_data->term_id;
        }
    }

    return "";
}

function safe_get_serialized_custom_tags(int $id, string $post_meta) {
    $data = get_post_meta($id, $post_meta, true);

    if (empty($data)) {
       return [];
    }

    if (is_array($tags = unserialize($data))) {
        return $tags;
    }

    return [];
}

function safe_get_page_id_by_template($page_template)
{
    $page_details = get_pages([
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'hierarchical' => 0,
        'meta_value' => $page_template,
    ]);

    if (!empty($page_details) && array_key_exists(0, $page_details) && property_exists($page_details[0], "ID")) {
        return $page_details[0]->ID;
    }

    return false;
}

add_filter('excerpt_length', function ($length) {
    return 40;
}, 999);

// Adding extra meta boxes for defined screen type (ex: post, page)
add_action('add_meta_boxes', function () {
    add_meta_box('person_of_the_moment', __('Person of the moment'), 'person_of_the_moment', 'page', 'normal', 'high');
    add_meta_box('tag_connection', __('Tag connection'), 'tag_connection', 'page', 'normal', 'high');
    add_meta_box('breadcrumbs_visibility', __('Breadcrumbs visibility'), 'breadcrumbs_visibility', 'post', 'normal', 'high');
    add_meta_box('actress_gallery', __('Actress/Actor gallery'), 'actress_gallery', 'page', 'normal', 'high');
    add_meta_box('article_highlighting', __('Article highlighting in category'), 'article_highlighting', 'post', 'normal', 'high');
    add_meta_box('article_template', __('Article template'), 'article_template', 'post', 'normal', 'high');
});

// To fix broken image names...
if (!empty($_GET['debug'])) {
    add_action('init', function () {
//        global $wpdb;
//
//        $accentNamedImages = $wpdb->get_results(sprintf(
//            'SELECT %1$s.id, %1$s.guid FROM %1$s WHERE %1$s.id in (3462,12531,12929,12935,14817,14826,15081,15082,15410,19115,19120,19121,19122,19154,19155,27493,27512,27513,29373,29389,29390,29674,29699,29862,30895) ',
//            $wpdb->posts
//        ));
//
//        $path = '/var/www/clients/client2/web77/web/web/wp-content/uploads/';
//        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
//
//        foreach($objects as $name => $object){
//            if (preg_match('/[^a-zA-Z0-9\-\.\_\/\s]/', $name, $matches)) {
//                $fixedString = preg_replace('/├®/', 'é', $name);
//                $fixedString = preg_replace('/├¿/', 'è', $fixedString);
//                $fixedString = preg_replace('/├á/', 'à', $fixedString);
//                pre_dump($name);
////                rename($name, $fixedString);
//            }
//        }

//        $images = new FilesystemIterator('/var/www/clients/client2/web77/web/web/wp-content/uploads/2014/12');
//        $imageSizes = get_intermediate_image_sizes();
//
//        foreach ($images as $image) {
//            preg_match('/├®/', $image->getFilename(), $matches);
//
//            if (!empty($matches)) {
//                pre_dump($image->getFilename());
//            }
//            $split = preg_split('/[^a-zA-Z0-9\-._]/', $image->getFilename());

//            if (!empty($split) && count($split) > 1) {
//                $split = array_filter($split);
//                $queryString = 'SELECT %1$s.id, %1$s.guid FROM %1$s WHERE';
//
//                foreach ($split as $key => $string) {
//                    if ($key > 0) {
//                        if (count($split) - 1 > $key) {
//                            $queryString .= ' %1$s.guid like "%%' . $string . '%%" ';
//                        } else {
//                            $queryString .= ' and %1$s.guid like "%%' . $string . '" ';
//                        }
//                    } else {
//                        $queryString .= ' %1$s.guid like "%%' . $string . '%%" ';
//                    }
//                }
//
//                $queryString = sprintf($queryString, $wpdb->posts);
//                $results = $wpdb->get_results($queryString);
//
//                pre_dump($queryString);
//                pre_dump($results);
//
//                if (!empty($results)) {
////                    foreach($results as $result) {
////                        foreach($imageSizes as $imageSize) {
////                            pre_dump(wp_get_attachment_image_src($result->id, $imageSize));
////                        }
////                    }
//                }
//            }
//        }

//        foreach($accentNamedImages as $accentNamedImage) {
//            $imageFilePath = get_attached_file($accentNamedImage->id);
//
//            if (empty($imageFilePath)) {
//                continue;
//            }
//
////            pre_dump($imageFilePath);
////            rename($imageFilePath, $imageFilePath);
//        }
//        die();
    });
}
