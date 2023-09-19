<?php
$widget_helper = new WidgetHelper();
get_header(); ?>
    <div class="page-body">
        <div class="page-body__content page-404__content" role="main">
            <div>
                <div class="page-404__text">
                    <?php echo sprintf(
                        __("%sErreur 404%s
                            %sOups, la page que vous recherchez n’existe pas.%s
                            %sLa page que vous essayez d'attein-dre n'existe plus ou a été déplacée.%s", THEME_LANGUAGE_DOMAIN),
                        "<div class='page-404__text--bold'>", "</div>",
                        "<div class='page-404__text--bold'>", "</div>",
                        "<div class='page-404__text--normal'>", "</div>"); ?>
                </div>
                <div class="page-404__btn--wrapper">
                    <a class="btn btn--main" href="/<?php echo $widget_helper->get_current_language_code(); ?>"><?php _e("Retour à l’accueil"); ?></a>
                </div>
            </div>
            <div class="page-404__img">
                <img src="<?php echo get_static_image('404_page_picture.png'); ?>"
                     alt="<?php _e('404', THEME_LANGUAGE_DOMAIN); ?>"/>
            </div>
        </div>
    </div>
<?php
get_footer();
