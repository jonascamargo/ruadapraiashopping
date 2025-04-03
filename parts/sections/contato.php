<section id="sec-contato">
    <div class="wrapper">
        
        <div class="wrap">
            <header>

                <h2 class="sec-ttl tt-2"><?php echo esc_html(get_theme_mod("set_rua_contato_title")); ?></h2>

            </header>
        </div>
        <!-- END wrap -->

        <div class="grid-content">

            <div class="col-a">

                <?php
                $contact_info = [
                    'email' => [
                        'value' => get_theme_mod('set_rua_contato_email'),
                        'href' => 'mailto:',
                        'icon' => '<i class="fa-regular fa-envelope"></i>'
                    ],
                    'fone' => [
                        'value' => get_theme_mod('set_rua_contato_fone'),
                        'href' => 'tel:',
                        'icon' => '<i class="fa-solid fa-phone"></i>'
                    ],
                    'whats' => [
                        'value' => get_theme_mod('set_rua_contato_whats'),
                        'href' => 'https://wa.me/',
                        'icon' => '<i class="fa-brands fa-whatsapp"></i>'
                    ],
                    'endereco' => [
                        'value' => get_theme_mod('set_rua_contato_endereco'),
                        'href' => get_theme_mod('set_rua_contato_endereco_link'),
                        'icon' => '<i class="fa-solid fa-location-dot"></i>'
                    ]
                ];
                ?>

                <ul class="contact-list">
                    <?php foreach($contact_info as $key => $info): 
                        if(!empty($info['value'])): 
                            $href = ($key === 'whats') ? $info['href'] . preg_replace('/[^0-9]/', '', $info['value']) : $info['href'] . $info['value'];
                    ?>
                        <li>
                            <a href="<?php echo esc_url($href); ?>" target="_blank" class="contato-to-<?php echo $key; ?>">
                                <?php echo $info['icon']; ?>
                                <span><?php echo esc_html($info['value']); ?></span>
                            </a>
                        </li>
                    <?php 
                        endif;
                    endforeach; ?>
                </ul>

                
                <ul class="social-list">
                    <?php 
                    $social_keys = ['instagram', 'facebook', 'x', 'youtube'];
                    foreach($social_keys as $key):
                        $social_url = get_theme_mod("set_rua_social_{$key}");
                        if(!empty($social_url)):
                    ?>
                        <li>
                            <a href="<?php echo esc_url($social_url); ?>" target="_blank" class="<?php echo $key; ?>">
                                <i class="fa-brands fa-<?php echo $key === 'x' ? 'x-twitter' : $key; ?>"></i>
                                <!-- <span class="sr-only"><?php echo ucfirst($key); ?></span> -->
                            </a>
                        </li>
                    <?php 
                        endif;
                    endforeach; ?>
                </ul>

            </div>

            <div class="col-b">
                <?php
                $links_args = array(
                    'post_type' => 'rua_contato',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                );

                $links_query = new WP_Query($links_args);

                if ($links_query->have_posts()) :
                ?>

                <ul class="grid-contato">

                    <?php
                        while ($links_query->have_posts()) : $links_query->the_post();
                            $link_url = get_field('rua__contato_link');
                            $link_icone = get_field('rua__contato_icone');
                    ?>
                    <li class="card-bubble">

                        <a href="<?php echo esc_url($link_url); ?>" target="_blank">
                            <span><?php echo $link_icone; ?></span>
                            <span><?php the_title(); ?></span>
                        </a>

                    </li>
                    <?php  endwhile; ?>

                </ul>

                <?php
                    wp_reset_postdata();
                endif;
                ?>
            </div>

        </div>
        <!-- END grid-content -->

    </div>
</section>
<hr>