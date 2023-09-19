<?php
/**
 * Plugin Name: Dorcel Widgets
 * Description: Enables multiple types of widgets that can be used in the widget areas
 * Version: 0.0.1
 * Author: Krisztián Lakatos
 * Author URI: -
 *
 * @package sls-widgets
 */
if (!defined('ABSPATH')) {
    die('-1');
}

define('SLS_WIDGETS_POST_PLUGIN_PATH', __DIR__);
define('SLS_WIDGETS_POST_CLASSES_PATH', SLS_WIDGETS_POST_PLUGIN_PATH . '/classes/');
define('SLS_WIDGETS_POST_TEMPLATES_PATH', SLS_WIDGETS_POST_PLUGIN_PATH . '/templates/');
define('SLS_WIDGETS_POST_FEEDS_PATH', SLS_WIDGETS_POST_PLUGIN_PATH . '/assets/feeds/');
define('SLS_WIDGETS_POST_SCRIPTS_URL', plugins_url() . '/dorcel-widgets/assets/scripts/');
define('SLS_WIDGETS_POST_STYLES_URL', plugins_url() . '/dorcel-widgets/assets/styles/');
define('SLS_WIDGETS_POST_IMAGES_URL', plugins_url() . '/dorcel-widgets/assets/img/');

const WIDGET_APPEARANCES = [
    'article_normal' => [
        'name' => 'Normal',
        'width' => 540,
        'height' => 214,
        'mobile_res' => '100vw',
        'appearance' => ['category', 'thumbnail']
    ],
    'article_sidebar' => [
        'name' => 'Sidebar',
        'width' => 238,
        'height' => 95,
        'mobile_res' => '238px',
        'appearance' => ['category', 'thumbnail']
    ],
    'article_simple' => [
        'name' => 'Sidebar simple',
    ],
    'slider' => [
        'name' => 'Full-width slider',
        'appearance' => ['thumbnail']
    ]
];

add_action('after_setup_theme', function () {
    $more_image_sizes = [
        'fullhd' => [
            'name' => 'Full-hd image',
            'width' => 1920,
            'height' => 1080,
            'mobile_res' => '100vw',
            'appearance' => ['fullhd']
        ],
    ];

    $image_sizes = array_merge(WIDGET_APPEARANCES, $more_image_sizes);

    foreach ($image_sizes as $widget_id => $data) {
        if (array_key_exists('width', $data) && array_key_exists('height', $data)) {
            add_image_size($widget_id, $data['width'], $data['height'], ['left', 'top']);
        }
    }
});

add_filter('wp_calculate_image_sizes', function ($sizes, $size) {
    $more_image_sizes = [
        'fullhd' => [
            'name' => 'Full-hd image',
            'width' => 1920,
            'height' => 1080,
            'mobile_res' => '100vw',
            'appearance' => ['fullhd']
        ]
    ];

    $image_sizes = array_merge(WIDGET_APPEARANCES, $more_image_sizes);

    foreach ($image_sizes as $widget_id => $data) {
        if (array_key_exists('width', $data) && array_key_exists('height', $data) && array_key_exists('mobile_res', $data) && $size[0] === $data['width']) {
            $sizes = '(min-width: 1200px) ' . $data['width'] . 'px, ' . $data['mobile_res'];
        }
    }
    return $sizes;
}, 10, 2);

if (!class_exists('WidgetHelper')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'WidgetHelper.php';
}

if (!class_exists('ArticleStorage')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'ArticleStorage.php';
}

if (!class_exists('PostVideoWidget')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'PostVideoWidget.php';
}

if (!class_exists('PostArticleWidget')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'PostArticleWidget.php';
}

if (!class_exists('PostVideoFeedWidget')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'PostVideoFeedWidget.php';
}

if (!class_exists('PostMovieWidget')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'PostMovieWidget.php';
}

if (!class_exists('PostSliderWidget')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'PostSliderWidget.php';
}

if (!class_exists('PostBlogSliderWidget')) {
    require SLS_WIDGETS_POST_CLASSES_PATH . 'PostBlogSliderWidget.php';
}

add_action('widgets_init', function () {
    register_widget('PostVideoWidget');
    register_widget('PostArticleWidget');
    register_widget('PostVideoFeedWidget');
    register_widget('PostMovieWidget');
    register_widget('PostSliderWidget');
    register_widget('PostBlogSliderWidget');
});
