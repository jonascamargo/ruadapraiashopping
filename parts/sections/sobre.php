<section id="sec-sobre">
    <div class="wrapper">

        <div class="wrap grid-content">

            <div class="col-a">
                <div class="sec-img">
                    <?php 
                        $img_id = get_theme_mod("set_rua_sobre_img");
                    if($img_id) {
                        echo wp_get_attachment_image($img_id, 'full');
                    }
                    ?>
                </div>
            </div>

            <div class="col-b">
                <header>

                    <h2 class="sec-ttl tt-3"><?php echo esc_html(get_theme_mod("set_rua_sobre_title")); ?></h2>

                    <div class="sec-bio text-wrapper">
                        <p><?php echo nl2br(get_theme_mod("set_rua_sobre_bio")); ?></p>
                    </div>

                </header>
            </div>

        </div>
        <!-- END wrap -->

        <div class="wrap-servicos">
            <header>
                <h3 class="grid-ttl tt-3"><?php echo esc_html(get_theme_mod("set_rua_sobre_ttl_servicos")); ?></h3>
            </header>

            <?php
            $servico_args = array(
                'post_type' => 'rua_servicos',
            );
            $servico_query = new WP_Query( $servico_args );

            if( $servico_query->have_posts() ) :
            ?>

            <ul class="grid-servicos">

                <? while ($servico_query->have_posts()) :
                    $servico_query->the_post();
                    // Pegando o grupo de campos
                    $servico_descricao = get_field('rua__servico_descricao');
                    $servico_icone = get_field('rua__servico_icone');
                    $servico_local = get_field('rua__servico_mapa');
                ?>

                <li class="card-bubble">
                    <a href="<?php echo esc_url($servico_local); ?>" data-post-id="<?php echo get_the_ID(); ?>" target="_blank" class="post modal-img">

                        <div class="servico-icon">
                            <?php echo $servico_icone; ?>
                        </div>
                        
                        <div class="servico-info">
                            <h3><?php the_title(); ?></h3>
    
                            <p><?php echo esc_html($servico_descricao); ?></p>
                        </div>

                    </a>
                </li>

                <?php endwhile; wp_reset_postdata(); ?>

            </ul>

            <?php endif; ?>
        </div>
        <!-- END grid-content -->

    </div>
</section>

<hr>