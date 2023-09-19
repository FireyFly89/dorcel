<?php get_header();
$widget_helper = new WidgetHelper();
?>

<!-- <?php _e('', THEME_LANGUAGE_DOMAIN); ?> -->

<div class="page-body front-page" role="main">
    <?php echo get_the_content(); ?>
    <?php dynamic_sidebar('home_cover_slider'); ?>

    <div class="featured__links" id="featured_links_slider">
        <div class="featured__links__item">
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/?utm_source=dorcel.com&utm_medium=referral&utm_campaign=push"); ?>
                    rel="noopener noreferrer">
                <img src="<?php echo get_static_image('featured-img-01.jpg'); ?>"
                     alt="<?php _e("Des milliers de films X à la demande", THEME_LANGUAGE_DOMAIN); ?>">
                <span class="featured__links__content">
                    <span class="featured__links__img">
                        <img src="<?php echo get_static_image('dorcel_com_logo.png') ?>" alt="dorcel logo">
                    </span>
                    <span class="featured__links__title">
                        <?php _e("Des milliers de films X à la demande", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                    <span class="featured__links__desc">
                        <?php _e("Des milliers de films X à la demande", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                </span>

            </a>
        </div>
        <div class="featured__links__item">
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcelclub.com/?utm_source=dorcel.com&utm_medium=referral&utm_campaign=push"); ?>
                    rel="noopener noreferrer">
                <img src="<?php echo get_static_image('featured-img-02.jpg'); ?>"
                     alt="<?php _e("L'univers Dorcel en avant-première", THEME_LANGUAGE_DOMAIN); ?>">
                <span class="featured__links__content">
                    <span class="featured__links__img">
                        <img src="<?php echo get_static_image('dorcel_com_logo.png') ?>" alt="dorcel logo">
                    </span>
                    <span class="featured__links__title">
                        <?php _e("Des milliers de films X à la demande", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                    <span class="featured__links__desc">
                        <?php _e("L'univers Dorcel en avant-première", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="featured__links__item">
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/?utm_source=dorcel.com&utm_medium=referral&utm_campaign=push"); ?>
                    rel="noopener noreferrer">
                <img src="<?php echo get_static_image('featured-img-03.jpeg'); ?>"
                     alt="<?php _e("Sextoys - DVD - Bien-être - Lingerie Sexy", THEME_LANGUAGE_DOMAIN); ?>">
                <span class="featured__links__content">
                    <span class="featured__links__img">
                        <img src="<?php echo get_static_image('dorcel_com_logo.png') ?>" alt="dorcel logo">
                    </span>
                    <span class="featured__links__title">
                        <?php _e("Des milliers de films X à la demande", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                    <span class="featured__links__desc">
                        <?php _e("Sextoys - DVD - Bien-être - Lingerie Sexy", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                </span>
            </a>
        </div>
        <div class="featured__links__item">
            <a <?php echo $url_handler->get_link_parameters("https://www.xillimite.com/?utm_source=dorcel.com&utm_medium=referral&utm_campaign=push"); ?>
                    rel="noopener noreferrer">
                <img src="<?php echo get_static_image('featured-img-04.jpg'); ?>"
                     alt="<?php _e("Accès immédiat à partir de 9,90€", THEME_LANGUAGE_DOMAIN); ?>">
                <span class="featured__links__content">
                    <span class="featured__links__img">
                        <img src="<?php echo get_static_image('dorcel_com_logo.png') ?>" alt="dorcel logo">
                    </span>
                    <span class="featured__links__title">
                        <?php _e("Des milliers de films X à la demande", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                    <span class="featured__links__desc">
                        <?php _e("Accès immédiat à partir de 9,90€", THEME_LANGUAGE_DOMAIN); ?>
                    </span>
                </span>
            </a>
        </div>
    </div>

    <?php dynamic_sidebar('home_movies_widget'); ?>

    <?php dynamic_sidebar('home_article_slider'); ?>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-1.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.dorcelclub.com/?ae1=dcom&utm_source=dorcel.com&utm_medium=referral&utm_campaign=club"); ?>>
                    <img src="<?php echo get_static_image('logo_club.png'); ?>"
                         alt="<?php _e("L'exclusivité au quotidien.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("L'exclusivité au quotidien.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e('Avec le DORCELCLUB.com rejoignez une communauté de privilégiés vous permettant de vivre notre univers de luxe et de perversion comme si vous en faisiez partie. Regardez des vidéos X exclusives 4K en direct de nos plateaux de tournages. Vivez une expérience unique aux côtés des plus belles actrices au monde.', THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcelclub.com/?ae1=dcom&utm_source=dorcel.com&utm_medium=referral&utm_campaign=club"); ?>
                    class="btn btn--main"><?php _e('Voir nos vidéos X exclusives', THEME_LANGUAGE_DOMAIN); ?></a>
        </div>
    </div>

    <?php dynamic_sidebar('home_video_widget'); ?>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-2.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>>
                    <img src="<?php echo get_static_image('logo_vision.png'); ?>"
                         alt="<?php _e("Une plateforme faite pour vous.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("Une plateforme faite pour vous.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e('Le site DORCELVISION.com vous propose des milliers de films X en qualité HD à regarder en ligne (streaming) ou à télécharger à la demande et sans abonnement.', THEME_LANGUAGE_DOMAIN); ?></p>
            <p><?php _e('Découvrez les plus grands studios français et internationaux, les productions MARC DORCEL en exclusivité et des nouveautés à découvrir chaque jour!', THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>
                    class="btn btn--main"><?php _e('Accédez à votre vidéo-club X', THEME_LANGUAGE_DOMAIN); ?></a>
        </div>
        <div class="presentation-cards">
            <div class="presentation-card alfa">
                <div class="presentation-card-content">
                    <div class="presentation-card-title">
                        <?php _e('DES NOUVEAUX <span class="title">FILMS</span> TOUS LES JOURS', THEME_LANGUAGE_DOMAIN); ?>
                    </div>
                    <div class="presentation-card-title">
                        <?php _e('AVEC PLUS DE <span class="title">1000</span> PORNSTARS', THEME_LANGUAGE_DOMAIN); ?>
                    </div>
                </div>
                <div class="presentation-card-img">
                    <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/films/?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>>
                        <img src="<?php echo get_static_image('jaquettes.jpg'); ?>" alt="jaquettes">
                    </a>
                </div>
            </div>
            <div class="presentation-card beta">
                <div class="presentation-card-content">
                    <div class="presentation-card-title">
                        <h3><?php _e('Nos vidéos sur tous vos écrans.', THEME_LANGUAGE_DOMAIN); ?></h3>
                        <p><?php _e('PC/MAC, Tablettes, Smartphones', THEME_LANGUAGE_DOMAIN); ?></p>
                    </div>
                </div>
                <div class="presentation-card-img">
                    <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>>
                        <img src="<?php echo get_static_image('devicesvision.jpg'); ?>" alt="devicesvision">
                    </a>
                </div>
            </div>
            <div class="presentation-card gamma">
                <div class="presentation-card-content">
                    <div class="presentation-card-title">
                        <h3><?php _e('Les meilleurs studios mondiaux.', THEME_LANGUAGE_DOMAIN); ?></h3>
                    </div>
                    <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/studios?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>><?php _e('Voir tous les studios', THEME_LANGUAGE_DOMAIN); ?></a>
                </div>
                <div class="presentation-card-img">
                    <a <?php echo $url_handler->get_link_parameters("https://www.dorcelvision.com/studios?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>>
                        <img src="<?php echo get_static_image('studios.jpg'); ?>" alt="studios">
                    </a>
                </div>
            </div>
        </div>
        <div class="icon-list">
            <div class="icon-list-item">
                <img src="<?php echo get_static_image('stars.png'); ?>" alt="stars">
                <p><?php _e('Films récompensés', THEME_LANGUAGE_DOMAIN); ?></p>
            </div>
            <div class="icon-list-item">
                <img src="<?php echo get_static_image('4k.png'); ?>" alt="4k">
                <p><?php _e('Des milliers de films en HD, Full HD, 4K', THEME_LANGUAGE_DOMAIN); ?></p>
            </div>
            <div class="icon-list-item">
                <img src="<?php echo get_static_image('flux.png'); ?>" alt="flux">
                <p><?php _e('Mises à jours quotidiennes', THEME_LANGUAGE_DOMAIN); ?></p>
            </div>
            <div class="icon-list-item">
                <img src="<?php echo get_static_image('anonyme.png'); ?>" alt="anonyme">
                <p><?php _e('Paiement sécurisé et discret', THEME_LANGUAGE_DOMAIN); ?></p>
            </div>
        </div>
    </div>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-3.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=btn"); ?>>
                    <img src="<?php echo get_static_image('logo_store.png'); ?>"
                         alt="<?php _e("Des produits érotiques premiums.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("Des produits érotiques premiums.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e('DORCELSTORE.com est une boutique en ligne dédiée au bien-être et à tous les plaisirs. Elle est destinée aux hommes comme aux femmes, pour le plus grand plaisir des couples.', THEME_LANGUAGE_DOMAIN); ?></p>
            <p><?php _e("Proposant l'un des catalogues les plus fournis de la toile elle a pour objectif de permettre à tous de réaliser ses achats coquins sans gêne.", THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=btn"); ?>
                    class="btn btn--main"><?php _e('Commandez vos toys, lingerie...', THEME_LANGUAGE_DOMAIN); ?></a>
        </div>
        <div class="showcase">
            <div id="showcase__slider">
                <a class="showcase-item" <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dvd.html?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=dvd"); ?>>
                    <h4><?php _e('DVD', THEME_LANGUAGE_DOMAIN); ?></h4>
                    <img src="<?php echo get_static_image('dvd.jpg'); ?>"
                         alt="<?php _e('DVD', THEME_LANGUAGE_DOMAIN); ?>"/>
                </a>
                <a class="showcase-item" <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/sextoys.html?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=sextoys"); ?>>
                    <h4><?php _e('SEXTOYS', THEME_LANGUAGE_DOMAIN); ?></h4>
                    <img src="<?php echo get_static_image('sextoys.jpg'); ?>"
                         alt="<?php _e('SEXTOYS', THEME_LANGUAGE_DOMAIN); ?>"/>
                </a>
                <a class="showcase-item" <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/lingerie.html?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=lingerie"); ?>>
                    <h4><?php _e('LINGERIE', THEME_LANGUAGE_DOMAIN); ?></h4>
                    <img src="<?php echo get_static_image('lingerie.jpg'); ?>"
                         alt="<?php _e('LINGERIE', THEME_LANGUAGE_DOMAIN); ?>"/>
                </a>
                <a class="showcase-item" <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/librairie-jeux.html?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=librairie"); ?>>
                    <h4><?php _e('CALENDRIER & JEUX', THEME_LANGUAGE_DOMAIN); ?></h4>
                    <img src="<?php echo get_static_image('jeu-librairie.jpg'); ?>"
                         alt="<?php _e('CALENDRIER & JEUX', THEME_LANGUAGE_DOMAIN); ?>"/>
                </a>
                <a class="showcase-item" <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/bien-etre.html?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=bienetre"); ?>>
                    <h4><?php _e('BIEN-ÊTRE', THEME_LANGUAGE_DOMAIN); ?></h4>
                    <img src="<?php echo get_static_image('bien-etre.jpg'); ?>"
                         alt="<?php _e('BIEN-ÊTRE', THEME_LANGUAGE_DOMAIN); ?>"/>
                </a>
                <a class="showcase-item" <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/sextoys/marque/fleshlight.html?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=fleshlight"); ?>>
                    <h4><?php _e('FLESHLIGHT', THEME_LANGUAGE_DOMAIN); ?></h4>
                    <img src="<?php echo get_static_image('fleshlight.jpg'); ?>"
                         alt="<?php _e('FLESHLIGHT', THEME_LANGUAGE_DOMAIN); ?>"/>
                </a>
            </div>
        </div>
    </div>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-5.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.dorcel.com/experience/?utm_source=dorcel.com&utm_medium=referral&utm_campaign=experience"); ?>>
                    <img src="<?php echo get_static_image('logo_dorcel.png'); ?>"
                         alt="<?php _e("Dorcel Logo", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("L’offre Premium la plus riche du marché.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e("Accédez au meilleur de DORCEL en illimité. De Pornochic, à Dorcel Airlines en passant par Luxure, profitez d'une expérience unique à travers les films cultes du studio. Sans engagement, premium et discret.", THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcel.com/experience/?utm_source=dorcel.com&utm_medium=referral&utm_campaign=experience"); ?>
                    class="btn btn--main"><?php _e('Devenir membre', THEME_LANGUAGE_DOMAIN); ?></a>
        </div>
    </div>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-4.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.dorceltv.com/?utm_source=dorcel.com&utm_medium=referral"); ?>>
                    <img src="<?php echo get_static_image('logo_tv.png'); ?>"
                         alt="<?php _e("L’offre Premium la plus riche du marché.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("4 chaînes TV hard, à emporter partout.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e("DORCEL TV c'est 90 films différents par mois, le meilleur des productions internationales et les plus belles actrices du moment. Nos caméras vous font découvrir les backstages, les stars et les secrets de tournage dans des reportages exclusifs. Quelles que soient vos envies, vous pouvez regarder vos chaînes hard préférées sur tous vos écrans, n'importe où et à tout moment.", THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.dorceltv.com/?utm_source=dorcel.com&utm_medium=referral"); ?>
                    class="btn btn--main"><?php _e('Voir le live', THEME_LANGUAGE_DOMAIN); ?></a>
            <a class="image-link" <?php echo $url_handler->get_link_parameters("https://www.dorceltv.com/?utm_source=dorcel.com&utm_medium=referral"); ?>>
                <img src="<?php echo get_static_image('devices.jpg'); ?>" alt="devices">
            </a>
        </div>
    </div>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-6.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a href="/mag" target="_blank" rel="nofollow noopener">
                    <img src="<?php echo get_static_image('logo_mag.png'); ?>"
                         alt="<?php _e("Tous les mois, le meilleur du X chez votre marchand de journaux.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("Tous les mois, le meilleur du X chez votre marchand de journaux.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e("Chaque mois le Dorcel Mag vous ouvre les coulisses de la galaxie Marc Dorcel : reportages sur tous les derniers tournages, interviews des plus grandes stars du X, news, débutantes, chroniques, le tout abondamment illustré de toutes les plus belles photos. En cadeau, retrouvez également, gratuit dans chaque numéro, un DVD avec deux superbes films Marc Dorcel en version intégrale.", THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.dorcel.com/mag/?utm_source=dorcel.com&utm_medium=referral"); ?>
                    class="btn btn--main"><?php _e('Achetez le DorcelMag', THEME_LANGUAGE_DOMAIN); ?></a>
            <a class="image-link" <?php echo $url_handler->get_link_parameters("https://www.dorcel.com/mag/?utm_source=dorcel.com&utm_medium=referral"); ?>>
                <img src="<?php echo get_static_image('mag2.jpg'); ?>" alt="magazine">
            </a>
        </div>
    </div>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-7.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/?utm_medium=referral&utm_source=dorcel.com&utm_content=image&utm_term=btn"); ?>>
                    <img src="<?php echo get_static_image('logo_store.png'); ?>"
                         alt="<?php _e("Dans nos magasins, tous les plaisirs pour elle et lui.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("Dans nos magasins, tous les plaisirs pour elle et lui.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e("Nos boutiques proposent l'une des plus grandes sélections de produits sexy avec plus de 2500 références (Lingerie, sextoys, soins du corps, jeux, accessoires, DVD...) s'adressant aux couples, aux femmes et aux hommes de tout âge.", THEME_LANGUAGE_DOMAIN); ?></p>
            <p><?php _e("Les équipes du DORCELSTORE sont à votre disposition pour vous conseiller et vous orienter vers une sélection de produits au meilleur rapport qualité/prix pour votre plus grand plaisir.", THEME_LANGUAGE_DOMAIN); ?></p>
        </div>
        <div class="card-list">
            <div class="custom-widget__wrapper">
                <div id="card-list__slider">
                    <div class="card-list-item">
                        <h4><?php _e("ROUEN", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("1389 boulevard de Normandie", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("76360 Barentin", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 77 70 07 90", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_rouen?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("SAINT-BRIEUC", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("Rue Laennec", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("22360 Langueux", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 96 65 87 27", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_saint_brieuc?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("BREST", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("72 route de Gouesnou", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("29900 Brest", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 98 41 52 04", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_brest?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("LE MANS", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("1 rue des Frères Voisin", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("72000 Le Mans", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 53 56 72 40", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_le_mans?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("NANTES", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("10 avenue des lions", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("44800 Saint-Herblain", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 40 94 16 96", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_nantes?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("RENNES", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("4 rue des Petits Champs", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("35760 Saint Grégoire", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 99 38 89 83", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_rennes?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("LILLE", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("1 rue de la Zamin", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("59160 Capinghem", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("03 61 62 49 49", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_lille?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                    <div class="card-list-item">
                        <h4><?php _e("LORIENT", THEME_LANGUAGE_DOMAIN); ?></h4>
                        <p><?php _e("ZI des Manebos, rue Rouget de l'Isle", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("56600 Lanester", THEME_LANGUAGE_DOMAIN); ?></p>
                        <p><?php _e("02 97 89 26 05", THEME_LANGUAGE_DOMAIN); ?></p>
                        <a <?php echo $url_handler->get_link_parameters("https://www.dorcelstore.com/dorcel_store_lorient?utm_source=dorcel.com&utm_medium=referral&utm_campaign=boutiques"); ?>><?php _e("+ d'info", THEME_LANGUAGE_DOMAIN); ?></a>
                    </div>
                </div>
                <?php get_template_part('slider-arrows-store'); ?>
            </div>
        </div>
    </div>

    <div class="presentation">
        <div class="presentation-img-wrap">
            <div class="presentation-img"
                 style="background-image: url('<?php echo get_static_image('presentation-bg-8.jpg'); ?>')"></div>
            <div class="presentation-img-overlay">
                <a <?php echo $url_handler->get_link_parameters("https://www.xillimite.com/?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>>
                    <img src="<?php echo get_static_image('logo_xillimite.png'); ?>"
                         alt="<?php _e("La seule offre légale de films pour adultes.", THEME_LANGUAGE_DOMAIN); ?>">
                </a>
            </div>
        </div>
        <div class="presentation-content">
            <h2><?php _e("La seule offre légale de films pour adultes.", THEME_LANGUAGE_DOMAIN); ?></h2>
            <p><?php _e("Xillimité.com propose un accès sans engagement à un catalogue exceptionnel de films en qualité HD. Des milliers de vidéos compatibles sur tous vos écrans, des nouveautés quotidiennes, pour seulement 9,99€ par mois.", THEME_LANGUAGE_DOMAIN); ?></p>
            <a <?php echo $url_handler->get_link_parameters("https://www.xillimite.com/?track=dcom&utm_source=dorcel.com&utm_medium=referral"); ?>
                    class="btn btn--main"><?php _e("Découvrez le porno HD en illimité", THEME_LANGUAGE_DOMAIN); ?></a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
