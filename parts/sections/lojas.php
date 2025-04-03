<section id="sec-lojas">
    <div class="wrapper">

        <div class="wrap">
            <header>

                <h2 class="sec-ttl tt-2"><?php echo esc_html(get_theme_mod("set_rua_lojas_title")); ?></h2>

                <div class="sec-bio text-wrapper">
                    <p><?php echo nl2br(get_theme_mod("set_rua_lojas_bio")); ?></p>
                </div>

            </header>
        </div>
        <!-- END wrap -->

        <div class="lojas-wrapper">
            <?php
            $lojas_args = array(
                'post_type' => 'rua_lojas',
                'posts_per_page' => 4,
                'paged' => 1
            );

            $lojas_query = new WP_Query( $lojas_args );

            if( $lojas_query->have_posts() ) :
            ?>

            <!-- ///// BUSCA ////// -->
            <div class="search-container">
                <div class="wrapper">

                    <div class="search-grid search-inputs">
                        <select id="segmento-filter" class="filter-select">
                            <option value="">Todos as categorias</option>

                            <?php
                            $segmentos = get_terms(array(
                                'taxonomy' => 'category',
                                'hide_empty' => true
                            ));

                            foreach($segmentos as $segmento) {
                                echo '<option value="' . $segmento->slug . '">' . $segmento->name . '</option>';
                            }
                            ?>
                        </select>
                        
                        <input type="text" id="search-lojas" class="search-field" placeholder="Buscar lojas..." data-post-type="rua_lojas">
                    </div>

                    <div id="search-results-lojas" class="search-results" style="display:none;"></div>

                </div>
            </div>

            <!-- ///// GRID ////// -->
            <ul id="lojas-container" class="posts-container grid-lojas">
                
                <?php 
                while ($lojas_query->have_posts()) :
                    $lojas_query->the_post();
                    get_template_part('parts/cards/card', 'lojas');
                endwhile; 
                wp_reset_postdata(); 
                ?>
                
            </ul>
            
            <div class="action text-center pt-10">
                <button id="carregar-mais-lojas" class="btn-purple carregar-mais" data-post-type="rua_lojas" data-posts-per-page="4"><?php echo esc_html(get_theme_mod("set_rua_lojas_btn")); ?></button>
            </div>

            <?php endif; ?>
        </div>
        <!-- END grid-content -->

    </div>
</section>

<hr>