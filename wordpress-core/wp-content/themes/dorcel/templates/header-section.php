<?php $widget_helper = new WidgetHelper(); ?>

<header class="header-section hidden" role="banner">
    <div class="header-section__wrap">
        <nav class="header-section__elems">
            <div class="header-section__elem__close">
                <span><?php _e('Fermer', THEME_LANGUAGE_DOMAIN); ?></span>
                <span class="header-section__elem__close__icon"></span>
            </div>

            <div class="header-section__elem__wrapper">
                <?php echo get_named_menu_hierarchically('header', 'header-section'); ?>
            </div>

            <div class="header-section__elem__languageselector">
                <?php do_action('wpml_add_language_selector'); ?>
            </div>
        </nav>

        <div class="header-section__mobile">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <a class="header-section__logo__wrapper" href="/<?php echo $widget_helper->get_current_language_code(); ?>">
            <?php echo get_logo('header-section', 'dorcel_com_logo.png'); ?>
        </a>

        <?php echo get_social_elems('header', [
            ['icon' => 'facebook-f', 'type' => 'facebook', 'param' => 'dorcel'],
            ['icon' => 'twitter', 'type' => 'twitter', 'param' => 'dorcel'],
            ['icon' => 'youtube', 'type' => 'youtube', 'param' => 'BlogDorcel'],
            ['icon' => 'instagram', 'type' => 'instagram', 'param' => 'dorcel'],
        ]); ?>
    </div>
</header>
