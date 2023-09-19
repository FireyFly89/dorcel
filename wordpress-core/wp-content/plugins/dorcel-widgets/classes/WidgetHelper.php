<?php

/**
 * Class WidgetHelper
 *
 * Serves as a helper class for all article based widgets
 * (Always prepend image input fields with "media_" (needed for language switching))
 */
class WidgetHelper
{
    /**
     * @var array
     */
    private $query_methods;

    /**
     * @var array
     */
    private $title_types;

    /**
     * WidgetHelper constructor.
     */
    public function __construct()
    {
        $this->query_methods = [
            'latest' => __('Latest articles', 'dorcel-widgets'),
            'categories' => __('Articles with categories', 'dorcel-widgets'),
            'categories_multiple' => __('Articles with multiple categories at the same time', 'dorcel-widgets'),
            'tags' => __('Articles with tags', 'dorcel-widgets'),
            'tags_multiple' => __('Articles with multiple tags at the same time', 'dorcel-widgets'),
            'context' => __('Get the articles from the page context'),
        ];

        $this->title_types = [
            'h1' => 'h1',
            'h2' => 'h2',
            'h3' => 'h3',
            'h4' => 'h4',
            'h5' => 'h5',
            'h6' => 'h6',
            'span' => __('Normal text', 'dorcel-widgets'),
        ];

        add_action('admin_enqueue_scripts', [$this, 'widget_scripts']);
        add_action('wp_ajax_data_by_language', [$this, 'get_widget_data']);
        add_action('wp_ajax_media_src_by_id', [$this, 'get_media_src']);
    }

    /**
     * Enqueue and localize wp scripts
     */
    public final function widget_scripts()
    {
        global $sls_theme_version;
        wp_enqueue_style('dorcel-widget-admin-styles', SLS_WIDGETS_POST_STYLES_URL . 'admin_styles.css', [], $sls_theme_version);
        wp_enqueue_script('dorcel-widget-admin-scripts', SLS_WIDGETS_POST_SCRIPTS_URL . 'admin_scripts.js', ['jquery'], $sls_theme_version, true);
        wp_localize_script('dorcel-widget-admin-scripts', 'widget_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'query_methods' => $this->query_methods,
            'security_nonce' => wp_create_nonce("widgets_nonce"),
        ]);
    }

    /**
     * @param string $key
     * @return array
     */
    public final function get_query_methods(string $key = "")
    {
        if (!empty($key) && array_key_exists($key, $this->query_methods)) {
            return $this->query_methods[$key];
        }

        return $this->query_methods;
    }

    /**
     * @param string $key
     * @return array
     */
    public final function get_title_types(string $key = "")
    {
        if (!empty($key) && array_key_exists($key, $this->title_types)) {
            return $this->title_types[$key];
        }

        return $this->title_types;
    }

    /**
     * Handles
     *
     * @param string $type
     * @param array $args
     * @return bool|string
     */
    public final function get_widget_form_field(string $type, array $args)
    {
        if (empty($args['id']) || empty($args['name'])) {
            return false;
        }

        switch ($type) {
            case 'dropdown':
                return $this->generate_widget_dropdown($args);
                break;
            case 'input':
                return $this->generate_widget_input($args);
                break;
            case 'media_library':
                return $this->generate_widget_media($args);
                break;
            default:
                error_log('Invalid or unknown widget type!');
                return false;
                break;
        }
    }

    /**
     * Builds the "id" parameter of a field by given parameters
     *
     * @param array $args
     * @return bool|mixed|string
     */
    private final function get_field_id(array $args)
    {
        if (empty($args) && array_key_exists('id', $args)) {
            return false;
        }

        $field_id = $args['id'];

        if (!empty($args['language'])) {
            $field_id .= "-" . $this->get_widget_language($args);
        }

        if (!empty($args['associative'])) {
            $field_id .= "-" . $args['associative'];
        }

        return $field_id;
    }

    /**
     * Returns the value of a field if given, or a default value if given. Returns empty otherwise.
     *
     * @param array $args
     * @return mixed|string|array
     */
    private final function get_value_or_default(array $args)
    {
        if (!empty($args['value'])) {
            $value = $args['value'];

            if (!empty($args['language'])) {
                $language_code = $args['language'];

                if (is_array($args['language'])) {
                    $language_code = array_keys($args['language'])[0];
                }

                if (array_key_exists($language_code, $value)) {
                    $value = $value[$language_code];
                } else {
                    $value = "";
                }
            }

            if (!empty($args['associative'])) {
                $value = $value[$args['associative']];
            }

            return $value;
        }

        if (!empty($args['default'])) {
            return $args['default'];
        }

        if (!empty($args['options']) && is_array($args['options'])) {
            $this->get_array_first_element($args['options']);
        }

        return "";
    }

    /**
     * Builds the "name" attribute of a field by given parameters
     *
     * @param $args
     * @return string
     */
    private final function build_field_name($args)
    {
        if (empty($args['name'])) {
            return "";
        }

        $full_field_name = $args['name'];

        if (!empty($args['language'])) {
            $full_field_name .= '[' . $this->get_widget_language($args) . ']';
        }

        if (!empty($args['associative'])) {
            $full_field_name .= '[' . $args['associative'] . ']';
        }

        if (!empty($args['multiselect'])) {
            $full_field_name .= '[]';
        }

        return $full_field_name;
    }

    private final function get_widget_language($args)
    {
        $language_code = $args['language'];

        if (is_array($args['language'])) {
            $language_code = array_keys($args['language'])[0];
        }

        return $language_code;
    }

    /**
     * Used in Wordpress required "update" method in widget that extends the WP_Widget class.
     * Updates values recursively, and handles our unique multilanguage solution
     *
     * @param array $field_options
     * @param array $instance
     * @param array $old_instance
     * @return array
     */
    public final function update_widget_values(array $field_options, array $instance, array $old_instance)
    {
        $return_values = [];
        $merged_instances = $this->recursive_array_merge($old_instance, $instance, $field_options);

        foreach ($field_options as $option) {
            $multilang = false;
            $associative = false;
            $field_name = $option;

            if (is_array($option)) {
                $field_name = $field_name['name'];

                if (array_key_exists('multilang', $option)) {
                    $multilang = $option['multilang'];
                }

                if (array_key_exists('associative', $option)) {
                    $associative = $option['associative'];
                }
            }

            if (!empty($merged_instances[$field_name])) {
                if ($multilang === true) {
                    $available_language_codes = $this->get_all_available_languages(true);

                    foreach ($available_language_codes as $code) {
                        if ($associative === false && !array_key_exists($code, $merged_instances[$field_name])) {
                            $merged_instances[$field_name][$code] = "";
                        } else if ($associative === true) {
                            foreach ($merged_instances[$field_name] as $key => $associative_field) {
                                if (!array_key_exists($code, $merged_instances[$field_name])) {
                                    $merged_instances[$field_name][$code] = "";
                                }
                            }
                        }
                    }
                }

                $return_values[$field_name] = $merged_instances[$field_name];
            } else {
                $return_values[$field_name] = "";
            }
        }

        return $return_values;
    }

    /**
     * Recursively merges an array up to 2 level deep.
     * The php default "array_merge_recursive" renumbers numeric keys which are bad for us, thus we need our own method for this
     *
     * @param $array1 array Contains all languages
     * @param $array2 array Contains newly modified values in their respective language
     * @param $field_options
     * @return array
     */
    public final function recursive_array_merge(array $array1, array $array2, $field_options)
    {
        $merged_array = $array2;

        foreach ($merged_array as $key => &$data) {
            $keep_numeric_keys_level_1 = false;
            $default_override = $this->get_field_default($field_options, $key);

            if (array_key_exists($key, $array1) && !is_array($array1[$key]) && empty($array1[$key]) && is_array($data)) {
                $array1[$key] = $data;
            }

            if (is_array($data) && array_key_exists($key, $array1)) {
                foreach ($data as $inner_key => $inner_data) {
                    if (is_int($inner_key)) {
                        $keep_numeric_keys_level_1 = true;
                    }

                    if (is_array($inner_data) && is_array($array1[$key][$inner_key]) && array_key_exists($inner_key, $array1[$key])) {
                        $data[$inner_key] = $inner_data + $array1[$key][$inner_key];
                    }
                }

                if ($keep_numeric_keys_level_1 === false) {
                    if (empty($default_override)) {
                        $data = array_merge($array1[$key], $data);
                    } else if (in_array($default_override, $data)) {
                        $data = ['default' => $default_override];
                    }
                } else {
                    if (empty($default_override)) {
                        $data = $data + $array1[$key];
                    } else if (in_array($default_override, $data)) {
                        $data = [0 => $default_override];
                    }
                }
            } else {
                $merged_array[$key] = $data;
            }
        }

        return $merged_array;
    }

    /**
     * Returns field default value if exists
     *
     * @param $field_options
     * @param $field_name
     * @return mixed|string
     */
    public final function get_field_default($field_options, $field_name)
    {
        $default = "";

        if (is_array($field_options) && !empty($field_options)) {
            foreach ($field_options as $field_option) {
                if (array_key_exists('name', $field_option) && $field_option['name'] === $field_name && array_key_exists('default', $field_option)) {
                    $default = $field_option['default'];
                }
            }
        }

        return $default;
    }

    /**
     * Returns data from a specified widget name and number (basically by a widget ID)
     * If the data returned is a "json" format type, then it is considered a success in the ajax call
     */
    public final function get_widget_data()
    {
        check_ajax_referer('widgets_nonce', 'security_nonce');

        if (empty($widget_name = $_POST['widget_name'])) {
            wp_die('Unknown widget name!');
        }

        if (empty($widget_number = $_POST['widget_number'])) {
            wp_die('Unknown widget id!');
        }

        $widget_data = get_option("widget_" . $widget_name);

        if (array_key_exists($widget_number, $widget_data)) {
            wp_die(json_encode($widget_data[$widget_number]));
        }

        wp_die('Something went wrong!');
    }


    /**
     * Returns a single image URL to an AJAX request by given media ID
     */
    public final function get_media_src()
    {
        check_ajax_referer('widgets_nonce', 'security_nonce');

        if (empty($media_id = $_POST['media_field_id'])) {
            wp_die('Unknown media field id!');
        }

        $media_src = wp_get_attachment_image_src($media_id, 'thumbnail');

        if (!empty($media_src[0])) {
            wp_die(json_encode($media_src[0]));
        }

        wp_die('Could not find media!');
    }

    /**
     * Returns the first element or key of an array depending on the $type variable
     *
     * @param array $data
     * @param string $type
     * @return array|int|string Defaults to the original array $data
     */
    public final function get_array_first_element(array $data, string $type = "key")
    {
        foreach ($data as $key => $value) {
            if ($type === "key") {
                return $key;
            } else if ($type === "value") {
                return $value;
            }
        }

        return $data;
    }

    /**
     * Generates markup for the media fields for widgets
     *
     * @param array $args
     * @return bool|string
     */
    private final function generate_widget_media(array $args)
    {
        if (empty($args['name'])) {
            return false;
        }

        $result = "";

        if (!empty($args['title'])) {
            $result .= sprintf('<label class="custom-image-label" for="%s">%s</label>', $args['id'], $args['title']);
        }

        if (!empty($args['description'])) {
            $result .= sprintf('<span>%s</span>', $args['description']);
        }

        if (empty($args['button_text'])) {
            $button_text = 'Select media';
        } else {
            $button_text = $args['button_text'];
        }

        $value = $this->get_value_or_default($args);

        if (!empty($value)) {
            $img_src = wp_get_attachment_image_src($value, 'thumbnail');

            if (!empty($img_src[0])) {
                $result .= sprintf('<img class="widget-image" src="%s" />', $img_src[0]);
            }
        }

        $result .= sprintf('<input type="hidden" name="%s" class="image-id-input" value="%s" /><button id="%s" class="select-custom-image button">%s</button>',
            $this->build_field_name($args),
            $value,
            $this->get_field_id($args),
            $button_text);
        return $result;
    }

    /**
     * Generates markup for any type of input field for widgets
     *
     * @param array $args
     * @return bool|string
     */
    private final function generate_widget_input(array $args)
    {
        if (empty($args['type'])) {
            return false;
        }

        $result = "";
        $extra_attributes = "";

        if (!empty($args['title'])) {
            $result .= sprintf('<label for="%s">%s</label>', $args['id'], $args['title']);
        }

        if (!empty($args['description'])) {
            $result .= sprintf('<span>%s</span>', $args['description']);
        }

        if ($args['type'] === 'number') {
            if (!empty($args['min'])) {
                $extra_attributes .= 'min="' . $args['min'] . '" ';
            }

            if (!empty($args['max'])) {
                $extra_attributes .= 'max="' . $args['max'] . '" ';
            }
        }

        $extra_class = (!empty($args['class']) ? $args['class'] : "");
        $extra_class .= ((!empty($args['hidden']) && $args['hidden'] === true) ? " hidden-widget-input" : "");
        $result .= sprintf('<input class="widefat %s" id="%s" name="%s" type="%s" placeholder="%s" value="%s" %s />',
            $extra_class,
            $this->get_field_id($args),
            $this->build_field_name($args),
            $args['type'],
            (!empty($args['placeholder']) ? $args['placeholder'] : ""),
            $this->get_value_or_default($args),
            $extra_attributes);

        return $result;
    }

    /**
     * Generates markup for select fields for widgets
     *
     * @param array $args
     * @return bool|string
     */
    private final function generate_widget_dropdown(array $args)
    {
        if (empty($args['options']) || empty($args['name'])) {
            return false;
        }

        $dropdown = "";

        if (!empty($args['title'])) {
            $dropdown .= sprintf('<label for="%s">%s</label>', $args['id'], $args['title']);
        }

        if (!empty($args['description'])) {
            $dropdown .= sprintf('<span>%s</span>', $args['description']);
        }

        $multiselect = ((!empty($args['multiselect']) && $args['multiselect'] === true) ? true : false);
        $extra_class = (!empty($args['class']) ? $args['class'] : "");
        $extra_class .= ((!empty($args['hidden']) && $args['hidden'] === true) ? " hidden-widget-input" : "");
        $properties = ($multiselect ? 'multiple' : '');
        $dropdown .= sprintf('<select class="widefat %s" id="%s" name="%s" %s>',
            $extra_class,
            $this->get_field_id($args),
            $this->build_field_name($args),
            $properties
        );
        $selected_option = $this->get_value_or_default($args);
        $options = "";
        $i = 0;

        foreach ($args['options'] as $value => $option) {
            $selected = false;
            $name = $option;

            if ($multiselect && is_array($selected_option)) {
                foreach ($selected_option as $input) {
                    $selected = $input == $value;

                    if ($selected === true) {
                        break;
                    }
                }
            } else if ($selected_option === $value) {
                $selected = true;
            } else if (empty($selected_option)) {
                $selected = $selected_option = true;
            }

            if (is_array($name) && array_key_exists('name', $option)) {
                $name = $option['name'];
            }

            if (!empty($args['placeholder']) && $i <= 0) {
                $options .= sprintf('<option value="%s" %s %s>%s</option>',
                    (!empty($args['default']) ? $args['default'] : ''),
                    (!empty($args['placeholder_enabled']) && $args['placeholder_enabled'] === true ? '' : 'disabled'),
                    (strpos($options, 'selected') === false ? 'selected="selected"' : ''),
                    $args['placeholder']
                );
            }

            $options .= sprintf('<option value="%s" %s>%s</option>', $value, ($selected === true ? 'selected="selected"' : ''), $name);
            $i++;
        }

        return $dropdown . $options . "</select>";
    }

    /**
     * Initializes widget fields with an empty variable, if widget is used for the first time, or in a new instance so there won't be any undefined variables
     *
     * @param array $args
     * @param array $instance
     * @return array|bool
     */
    public final function initialize_instance_values(array $args, array $instance)
    {
        if (empty($args)) {
            return false;
        }

        $missing_values = $instance;
        $default_lang = "en"; // Make this element first in the widget settings if want to overwrite english as default language

        foreach ($args as $arg) {
            // Initialize value if the field does not exist in the instance yet
            if (!array_key_exists($arg['name'], $instance)) {
                if ($arg['name'] === 'language_switcher') {
                    if (array_key_exists('default', $arg) && array_key_exists($arg['default'], $instance)) {
                        $default_lang = $arg['default'];
                    }

                    $missing_values[$arg['name']] = $default_lang;
                    continue;
                }

                // Add language code if field should be multilang
                if (array_key_exists('multilang', $arg) && $arg['multilang'] === true) {
                    // Add a default 1 => "" if field is an associative field (1 more level deep)
                    if (array_key_exists('associative', $arg) && $arg['associative'] === true) {
                        $missing_values[$arg['name']][$default_lang][1] = "";
                    } else {
                        $missing_values[$arg['name']][$default_lang] = "";
                    }
                } else {
                    // Add a default 1 => "" if field is an associative field (1 more level deep)
                    if (array_key_exists('associative', $arg) && $arg['associative'] === true) {
                        $missing_values[$arg['name']][1] = "";
                    } else {
                        $missing_values[$arg['name']] = "";
                    }
                }
            }
        }

        return array_merge($missing_values, $instance);
    }

    /**
     * @param $attachment_id
     * @param $size
     * @return string
     */
    public final function get_the_post_image(int $attachment_id, string $size)
    {
        $image_url = wp_get_attachment_image_url($attachment_id, $size);
        $image_alt = "Missing image";
        $image_srcset = "";
        $image_sizes = "";

        if ($image_url === false) {
            $image_url = get_static_image('missing_img_' . $size . '.jpg');
        } else {
            $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            $image_srcset = wp_get_attachment_image_srcset($attachment_id, $size);
            $image_sizes = wp_get_attachment_image_sizes($attachment_id, $size);
        }

        return sprintf('<img class="size-article_normal wp-image-%s" src="%s" alt="%s" srcset="%s" sizes="%s" />', $attachment_id, $image_url, $image_alt, $image_srcset, $image_sizes);
    }

    /**
     * @param $post_id
     * @return string
     */
    public final function get_the_post_categories(int $post_id, $appearance)
    {
        if (!$this->is_appearance_eligible($appearance, 'category')) {
            return '';
        }

        $categories = get_the_category($post_id);
        $result = '';

        foreach ($categories as $key => $category) {
            if ($appearance === 'article_sidebar' && $key > 0) {
                break;
            }

            $comma = ($key < count($categories) - 1) ? "," : "";
            $result .= sprintf('<a class="custom-widget__categories" href="%s">%s%s</a>', esc_url(get_category_link($category->term_id)), $category->name, $comma);
        }

        return sprintf('<div class="custom-widget__categories__wrapper">%s</div>', $result);
    }

    public final function get_the_category($category_name, $category_url)
    {
        return sprintf('<div class="custom-widget__categories__wrapper">%s</div>', sprintf('<a class="custom-widget__categories" href="%s">%s</a>', $category_url, $category_name));
    }

    /**
     * @param $max_page
     * @param string $type
     * @param string $prev_text
     * @param string $next_text
     * @return array|string|void
     */
    public final function widget_pagination($max_page, $type = 'array', $prev_text = '<i class="fas fa-caret-left"></i>', $next_text = '<i class="fas fa-caret-right"></i>')
    {
        $high_num = 999999999;

        return paginate_links([
            'base' => str_replace($high_num, '%#%', esc_url(get_pagenum_link($high_num))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $max_page,
            'mid_size' => 1,
            'prev_text' => $prev_text,
            'next_text' => $next_text,
            'type' => $type
        ]);
    }

    /**
     * Used mostly in widget templates
     *
     * @param $post
     * @param $appearance
     * @param string $size
     * @return mixed
     */
    public final function get_widget_thumbnail($post, $appearance, $size = "")
    {
        if (empty($size) && !$this->is_appearance_eligible($appearance, 'thumbnail')) {
            return '';
        }

        global $url_handler;
        $thumbnail_id = get_post_thumbnail_id($post->ID);
        $post_url = $url_handler->get_link_parameters(get_permalink($post->ID));

        return sprintf('<a class="custom-widget__thumbnail" %s>%s</a>',
            $post_url,
            $this->get_the_post_image(absint($thumbnail_id), empty($size) ? $this->get_featured_image_size($appearance) : $size)
        );
    }

    /**
     * @param $appearance
     * @return mixed
     */
    public final function get_featured_image_size($appearance)
    {
        if (!empty($appearance) && array_key_exists($appearance, WIDGET_APPEARANCES)) {
            return $appearance;
        }

        return WIDGET_APPEARANCES[array_keys(WIDGET_APPEARANCES)[0]];
    }

    /**
     * @param $appearance
     * @param $element_type
     * @return bool
     */
    public final function is_appearance_eligible($appearance, $element_type)
    {
        if (empty($appearance)) {
            return false;
        }

        $result = false;

        foreach (WIDGET_APPEARANCES as $type => $data) {
            if ($appearance === $type && array_key_exists('appearance', WIDGET_APPEARANCES[$type])) {
                foreach (WIDGET_APPEARANCES[$type]['appearance'] as $exception) {
                    if ($element_type === $exception) {
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public final function get_current_language_code($locale = false)
    {
        if (defined('ICL_LANGUAGE_CODE')) {
            if ($locale === true) {
                return ICL_LANGUAGE_CODE . "_" . mb_strtoupper(ICL_LANGUAGE_CODE);
            }

            return ICL_LANGUAGE_CODE;
        }

        if ($locale === true) {
            return 'fr_FR';
        }

        return 'fr';
    }

    /**
     * @return array
     */
    public final function get_all_available_languages($codes_only = false)
    {
        $languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');

        if (empty($languages)) {
            return ['en' => 'English'];
        }

        $result = [];

        foreach ($languages as $code => $parameter) {
            $result[$code] = [
                'name' => $parameter['translated_name']
            ];
        }

        if ($codes_only) {
            return array_keys($result);
        }

        return $result;
    }

    /**
     * Working as a sort of cache, deletes old feed files that are 'x' second old, if not that old yet, it returns the filename
     *
     * @param string $feed_file_name
     * @return bool|string
     */
    public final function manage_feed_file(string $feed_file_name)
    {
        $feeds = new FilesystemIterator(SLS_WIDGETS_POST_FEEDS_PATH);
        $new_file_time = 300; // Don't return existing file, but get a new version of it, if file is older than this (in seconds)

        foreach ($feeds as $fileinfo) {
            $file_name = $fileinfo->getFilename();

            if (strpos($file_name, $feed_file_name) !== false) {
                preg_match('/[0-9]{1,}/', $file_name, $matches);

                if (!empty($matches) && is_array($matches)) {
                    $file_time = $matches[0];

                    if ((time() - $file_time) <= $new_file_time) {
                        return $file_name;
                    }

                    unlink(SLS_WIDGETS_POST_FEEDS_PATH . $file_name);
                }
            }
        }

        return true;
    }

    /**
     * Trims given content by to a number of words using wp_trim_words() by the appearance given in the widget
     *
     * @param string $content
     * @param string|int $length
     * @param string $type
     * @return string
     */
    public final function trim_by_appearance(string $content, $length = '', string $type = 'title')
    {
        if (empty($content)) {
            return $content;
        }

        if (is_string($length)) {
            if ($type === 'content') {
                $number_of_words = 20;

                if ($length === 'article_sidebar') {
                    $number_of_words = 8;
                }
            } else {
                $number_of_words = 15;

                if ($length === 'article_sidebar') {
                    $number_of_words = 4;
                }
            }
        } else if (is_int($length)) {
            $number_of_words = $length;
        }

        return wp_trim_words($content, $number_of_words);
    }

    /**
     * Queries posts and then adds the post ID-s to the ArticleStorage which is a common storage for storing post ID-s in case of avoiding duplicate posts on the same page
     *
     * @param $query_args
     * @return WP_Query
     */
    public final function store_get_posts($query_args)
    {
        $posts = new WP_Query($query_args);
        ArticleStorage::get_instance()->add_articles(wp_list_pluck($posts->posts, 'ID'));
        return $posts;
    }

    /**
     * Safely returns a multilingual or non-multilingual value from a widget instance
     * Uses $default if field exists in instance, but empty
     *
     * @param string $field_name
     * @param array $instance
     * @param string $default
     * @return mixed|array|string
     */
    public final function get_instance_data(string $field_name, array $instance, string $default = "")
    {
        $return_value = "";

        if (array_key_exists($field_name, $instance)) {
            $return_value = $instance[$field_name];

            if (is_array($return_value) && array_key_exists($this->get_current_language_code(), $return_value)) {
                $return_value = $return_value[$this->get_current_language_code()];
            }
        }

        if (empty($return_value) && !empty($default)) {
            $return_value = $default;
        }

        return $return_value;
    }

    public final function safe_set_current_site_locale() {
        setlocale(LC_TIME, $this->get_current_language_code(true) . ".UTF8");
    }
}
