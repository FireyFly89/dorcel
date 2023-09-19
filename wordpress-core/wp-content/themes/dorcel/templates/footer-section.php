<footer class="footer-section" role="contentinfo">
    <div class="footer-section__wrapper">
        <nav class="footer-section__elems">
            <nav class="footer-section__meta">
                <p><?php echo _e('À PROPOS', THEME_LANGUAGE_DOMAIN); ?></p>
                <ul>
                    <?php echo get_named_menu('footer-other', 'footer-section__meta'); ?>
                </ul>
            </nav>
            <?php echo get_named_menu_hierarchically('footer', 'footer-section'); ?>
        </nav>

        <div class="footer-section__others">
            <div class="footer-section__languageselector">
                <?php do_action('wpml_add_language_selector'); ?>
            </div>

            <div class="footer-section__social">
                <p><?php echo _e('Suivez-nous, suivez vos désirs:', THEME_LANGUAGE_DOMAIN); ?></p>
                <?php echo get_social_elems('footer', [
                    ['icon' => 'facebook-f', 'type' => 'facebook', 'param' => 'dorcel'],
                    ['icon' => 'twitter', 'type' => 'twitter', 'param' => 'dorcel'],
                    ['icon' => 'youtube', 'type' => 'youtube', 'param' => 'BlogDorcel'],
                    ['icon' => 'instagram', 'type' => 'instagram', 'param' => 'dorcel'],
                ]); ?>
            </div>
        </div>
    </div>
    <p class="footer-section__copyright">
        <?php echo date('Y'); ?>
        &copy;
        <?php echo _e('1979 SAS. All Rights Reserved', THEME_LANGUAGE_DOMAIN); ?>
    </p>
</footer>
