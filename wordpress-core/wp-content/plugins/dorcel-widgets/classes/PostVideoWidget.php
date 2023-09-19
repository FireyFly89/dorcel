<?php
/**
 * Widget: PostVideoWidget class
 *
 * @package SLS
 * @subpackage Widgets
 * @since 5.2.2
 */

class PostVideoWidget extends WP_Widget
{
    /**
     *
     */
    const TITLE = 'title';
    const TITLE_TYPE = 'title_type';
    const VIDEO_URL = 'video_url';
    const LANGUAGE_SWITCHER = 'language_switcher';
    const TEMPLATE_PATH = 'post-video.php';

    /**
     * @var string
     */
    private $widget_title;
    /**
     * @var string
     */
    private $widget_title_type;
    /**
     * @var integer
     */
    private $video_id;
    /**
     * @var WidgetHelper
     */
    private $widget_helper;
    /**
     * @var string
     */
    private $video_type;

    /**
     * @var string
     */
    private $widget_id = 'post_video_widget';
    private $widget_field_settings = [];

    /**
     * PostVideoWidget constructor.
     */
    public function __construct()
    {
        $this->widget_helper = new WidgetHelper();

        // Instantiate the parent object
        parent::__construct(
            $this->widget_id,
            __('Dorcel video', 'dorcel-widgets'),
            ['description' => __('Displays a video widget that embeds videos from a youtube URL', 'dorcel-widgets')]
        );

        $this->widget_field_settings = $this->widget_field_settings();
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     */
    public final function widget($args, $instance)
    {
        $this->widget_title = $this->widget_helper->get_instance_data(self::TITLE, $instance);
        $this->widget_title_type = $this->widget_helper->get_instance_data(self::TITLE_TYPE, $instance, $this->widget_helper->get_array_first_element($this->widget_helper->get_title_types()));
        $this->set_video_id($this->widget_helper->get_instance_data(self::VIDEO_URL, $instance));

        ob_start();
        echo $args['before_widget'];
        require SLS_WIDGETS_POST_TEMPLATES_PATH . self::TEMPLATE_PATH;
        echo $args['after_widget'];
        echo ob_get_clean();
    }

    /**
     * Save widget options
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public final function update($new_instance, $old_instance)
    {
        return $this->widget_helper->update_widget_values($this->widget_field_settings, $new_instance, $old_instance);
    }

    /**
     * Output admin widget options form
     *
     * @param array $instance
     * @return string|void
     */
    public final function form($instance)
    {
        $instance_new = $this->widget_helper->initialize_instance_values($this->widget_field_settings, $instance);

        echo '<p>';

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('Switch Language', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::LANGUAGE_SWITCHER),
            'name' => $this->get_field_name(self::LANGUAGE_SWITCHER),
            'value' => $instance_new[self::LANGUAGE_SWITCHER],
            'options' => $this->widget_helper->get_all_available_languages(),
            'class' => 'language-switcher',
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('The widget title', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::TITLE),
            'name' => $this->get_field_name(self::TITLE),
            'placeholder' => __('The widget title', 'dorcel-widgets'),
            'value' => $instance_new[self::TITLE],
            'type' => 'text',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('input', [
            'title' => __('Video URL (Youtube or Vimeo)', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::VIDEO_URL),
            'name' => $this->get_field_name(self::VIDEO_URL),
            'placeholder' => __('The video URL', 'dorcel-widgets'),
            'value' => $instance_new[self::VIDEO_URL],
            'type' => 'text',
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo $this->widget_helper->get_widget_form_field('dropdown', [
            'title' => __('The title type of the widget', 'dorcel-widgets'),
            'id' => $this->get_field_id(self::TITLE_TYPE),
            'name' => $this->get_field_name(self::TITLE_TYPE),
            'placeholder' => __('Select the type of title', 'dorcel-widgets'),
            'value' => $instance_new[self::TITLE_TYPE],
            'options' => $this->widget_helper->get_title_types(),
            'language' => $instance_new[self::LANGUAGE_SWITCHER],
        ]);

        echo '</p>';
    }

    /**
     * Reads video ID from given URL.
     * Depending on URL it may set the video_id and type as youtube id or a vimeo id
     *
     * @param $url
     */
    private final function set_video_id($url)
    {
        if (strpos($url, 'youtube') !== false || strpos($url, 'youtu.be') !== false) {
            preg_match("/\?v\=([^&]+)/", $url, $video_id);

            if (!empty($video_id) && count($video_id) === 2) {
                $this->video_id = $video_id[1];
                $this->video_type = 'youtube';
            }
        } else if (strpos($url, 'vimeo') !== false) {
            $vimeo_api_response = json_decode(file_get_contents("https://vimeo.com/api/oembed.json?url=" . $url));

            if (!empty($vimeo_api_response->video_id)) {
                $this->video_id = $vimeo_api_response->video_id;
                $this->video_type = 'vimeo';
            }
        }
    }

    /**
     * Returns widget field settings that are needed for field initialization, and update
     *
     * @return array
     */
    private final function widget_field_settings()
    {
        return [
            [
                'name' => self::LANGUAGE_SWITCHER,
                'default' => $this->widget_helper->get_all_available_languages(true)[0],
            ],
            [
                'name' => self::TITLE,
                'multilang' => true,
            ],
            [
                'name' => self::VIDEO_URL,
                'multilang' => true,
            ],
            [
                'name' => self::TITLE_TYPE,
                'multilang' => true,
            ],
        ];
    }
}
