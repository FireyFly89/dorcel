<?php $image_id = get_query_var('image_id'); ?>
<div class="gallery-image-holder <?php echo empty($image_id) ? "no-image" : ""; ?>">
    <img alt="" src="<?php echo (!empty($image_id)) ? wp_get_attachment_image_url($image_id) : ""; ?>"/>
    <input type="hidden" name="_gallery_images[]" value="<?php echo (!empty($image_id)) ? $image_id : ""; ?>">
    <div class="gallery-image-delete" title="<?php _e('Delete this image', THEME_LANGUAGE_DOMAIN); ?>">
        <i class="fas fa-times"></i>
    </div>
</div>
