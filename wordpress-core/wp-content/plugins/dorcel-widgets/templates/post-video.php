<div class="custom-widget <?php echo $this->widget_id; ?>">
    <?php
    echo $args['before_title'];
    echo sprintf('<%s class="custom-widget__title">%s</%s>', $this->widget_title_type, $this->widget_title, $this->widget_title_type);
    echo $args['after_title'];

    if (!empty($this->widget_sub_url_text) && !empty($this->widget_sub_url)) {
        echo sprintf('<a class="custom-widget__title__sub-url" %s title="%s">%s</a>', $url_handler->get_link_parameters($this->widget_sub_url), $this->widget_sub_url_text, $this->widget_sub_url_text);
    }
    ?>

    <div class="custom-widget__content">
        <?php if ($this->video_type === 'youtube') : ?>
            <iframe src="https://www.youtube.com/embed/<?php echo $this->video_id; ?>" frameborder="0" allow="encrypted-media" allowfullscreen></iframe>
        <?php elseif ($this->video_type === 'vimeo') : ?>
        <iframe src="https://player.vimeo.com/video/<?php echo $this->video_id; ?>?color=ff0179" frameborder="0" allow="fullscreen" allowfullscreen></iframe>
        <script type="text/javascript" src="https://player.vimeo.com/api/player.js"></script>
        <?php endif; ?>
    </div>
</div>
