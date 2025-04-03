<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header id="main-header">
        <div class="wrapper">

            <div class="logo-top">
                <?php if( has_custom_logo() ) :
                    the_custom_logo();
                else:
                ?>
                <a href="<?php echo home_url('/'); ?>"><?php bloginfo( 'name' ); ?></a>
                <?php endif; ?>
            </div>
            
            <div class="wrapper-menu-nav group">
                <button class="mobile-menu-button group"><span></span><span></span><span></span></button>

                <?php wp_nav_menu( array(
                    'theme_location' => 'rua--primary-menu',
                    'depth' => 2,
                    'container_class' => 'menu-topo'
                ) ); ?>
            </div>

        </div>
    </header>
    <!-- END #main-header -->
    <hr>