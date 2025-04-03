<?php get_header(); ?>

<main class="loja-post">
    <div class="grid-modal">

        <div class="col-a">
            <div class="mobile-header-modal">
                <h3 class="modal-title"><?php echo get_the_title($post->ID); ?></h3>
                
                <?php
                $loja_termos = get_the_terms(get_the_ID(), 'category');
                if ($loja_termos && !is_wp_error($loja_termos)) : ?>
                <div class="loja-categorias">

                    <?php
                    $total = count($loja_termos);
                    foreach ($loja_termos as $index => $termo) {
                        echo esc_html($termo->name);
                        if ($index < $total - 1) { echo ' • '; }
                    }
                    ?>

                </div>
                <?php endif; ?>
            </div>

            <div class="modal-thumb">
                <?php echo get_the_post_thumbnail($post->ID, 'full'); ?>
            </div>

            <?php
            $email = get_field('rua__loja_email');
            $fone  = get_field('rua__loja_telefone');
            $whats = get_field('rua__loja_whats');
            $site  = get_field('rua__loja_url_site');
            $local = get_field('rua__loja_local');
                $local_titulo = $local['rua__loja_local_titulo'];
                $local_mapa   = $local['rua__loja_local_mapa'];
            ?>
            <ul class="modal-links">
                <?php if( $local_mapa ) : ?>
                <li>
                    <a href="<?php echo esc_url($local_mapa); ?>" data-post-id="<?php echo $post->ID; ?>" class="loja-local modal-img">
                        <i class="fa-solid fa-route"></i>
                        <span><?php echo $local_titulo; ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( $fone ) : ?>
                <li>
                    <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $fone); ?>" class="loja-phone">
                        <i class="fa-solid fa-phone-volume"></i>
                        <span><?php echo $fone; ?></span>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php if( $whats ) : ?>
                <li>
                    <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $whats); ?>" target="_blank" class="loja-whats">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span><?php echo $whats; ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($email) : ?>
                <li>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="loja-email" target="_blank">
                        <i class="fa-solid fa-envelope"></i>
                        <span><?php echo esc_html($email); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if( $site ) : ?>
                <li>
                    <a href="<?php echo esc_url( $site ); ?>" class="loja-site" target="_blank">
                        <i class="fa-solid fa-globe"></i>
                        <span><?php echo esc_html(preg_replace('/(https?:\/\/)?(www\.)?([^\/]+)\/?$/', '$3', $site)); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>


            <?php
            $social_media = [
                'insta' => [
                    'url' => get_field('rua__loja_url_insta'),
                    'icon' => 'fa-brands fa-instagram',
                    'class' => 'loja-insta'
                ],
                'face' => [
                    'url' => get_field('rua__loja_url_face'),
                    'icon' => 'fa-brands fa-facebook',
                    'class' => 'loja-face'
                ],
                'youtube' => [
                    'url' => get_field('rua__loja_url_youtube'),
                    'icon' => 'fa-brands fa-youtube',
                    'class' => 'loja-youtube'
                ],
                'twitter' => [
                    'url' => get_field('rua__loja_url_x'),
                    'icon' => 'fa-brands fa-x-twitter',
                    'class' => 'loja-twitter'
                ]
            ];
            ?>
            <ul class="modal-social">
            <?php foreach ($social_media as $social) : 
            if (!empty($social['url'])) : ?>

                <li>
                    <a href="<?php echo esc_url($social['url']); ?>" class="<?php echo esc_attr($social['class']); ?>" target="_blank">
                        <i class="<?php echo esc_attr($social['icon']); ?>"></i>
                    </a>
                </li>

            <?php endif;
            endforeach; ?>
            </ul>

        </div>

        <div class="col-b">

            <div class="desk-header-modal">
                <h3 class="modal-title"><?php echo get_the_title($post->ID); ?></h3>
                
                <?php
                $loja_termos = get_the_terms(get_the_ID(), 'category');
                if ($loja_termos && !is_wp_error($loja_termos)) : ?>
                <div class="loja-categorias">

                    <?php
                    $total = count($loja_termos);
                    foreach ($loja_termos as $index => $termo) {
                        echo esc_html($termo->name);
                        if ($index < $total - 1) { echo ' • '; }
                    }
                    ?>

                </div>
                <?php endif; ?>
            </div>

            <div class="text-wrapper">
                <?php echo apply_filters('the_content', get_post_field('post_content', $post->ID)); ?>
            </div>

        </div>

    </div>
</main>

<?php get_footer(); ?>