<section id="sec-blog">
    <div class="wrapper">

        <div class="wrap">
            <header>

                <h2 class="sec-ttl tt-2"><?php echo esc_html(get_theme_mod("set_rua_blog_title")); ?></h2>

                <div class="sec-bio text-wrapper">
                    <p><?php echo nl2br(get_theme_mod("set_rua_blog_bio")); ?></p>
                </div>

            </header>
        </div>
        <!-- END wrap -->

        <div class="blog-wrap">
            <?php
            $blog_args = array(
                'post_type' => 'rua_blog',
                'posts_per_page' => 5,
                'paged' => 1
            );

            $blog_query = new WP_Query( $blog_args );

            if( $blog_query->have_posts() ) :
            ?>

            <!-- ///// BUSCA ////// -->
            <div class="search-container">
                <div class="wrapper">
                    
                    <div class="search-grid search-filters">
                        <select>
                            <option value="">Todos os anos</option>

                            <?php
                            // Usando WP_Query em vez de SQL direto
                            $args = array(
                                'post_type' => 'rua_blog',
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'orderby' => 'date',
                                'order' => 'DESC',
                                'fields' => 'ids'
                            );

                            $posts = get_posts($args);
                            $anos = array();

                            foreach($posts as $post_id) {
                                $ano = get_the_date('Y', $post_id);
                                if (!in_array($ano, $anos)) {
                                    $anos[] = $ano;
                                }
                            }

                            foreach($anos as $ano) {
                                printf('<option value="%s">%s</option>', esc_attr($ano), esc_html($ano));
                            }
                            ?>
                        </select>
                        
                        <input type="text" id="search-blog" class="search-field" placeholder="Buscar no blog..." data-post-type="rua_blog">
                    </div>

                    <div id="search-results-blog" class="search-results" style="display:none;"></div>
                </div>
            </div>

            <!-- ///// GRID ////// -->
            <ul id="blog-container" class="posts-container grid-posts">

                <? while ($blog_query->have_posts()) : $blog_query->the_post(); ?>

                <li class="card-bubble">
                    <a href="<?php the_permalink(); ?>" data-post-id="<?php echo get_the_ID(); ?>" class="post read-more">

                        <div class="img-post">
                            <?php the_post_thumbnail( 'medium' ); ?>
                        </div>

                        <div class="info-post">
                            <h3><?php the_title(); ?></h3>

                            <p><?php echo get_the_excerpt(); ?></p>
                        </div>

                    </a>
                </li>

                <?php endwhile; wp_reset_postdata(); ?>

            </ul>
            
            <div class="action text-center pt-10">
                <button id="carregar-mais-blog" class="btn-gold carregar-mais" data-post-type="rua_blog" data-posts-per-page="5"><?php echo esc_html(get_theme_mod("set_rua_blog_btn")); ?></button>
            </div>

            <?php endif; ?>
        </div>
        <!-- END grid-content -->

    </div>
</section>

<hr>