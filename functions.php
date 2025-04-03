<?php

/******************************************************************************
 * REQUIRED PLUGINS NOTICE
******************************************************************************/

function rua_check_required_plugins() {
    // Lista de plugins necessários
    $required_plugins = array(
        'advanced-custom-fields' => 'Advanced Custom Fields',
        'advanced-custom-fields-font-awesome' => 'ACF Font Awesome',
        'custom-post-type-ui' => 'CPT UI',
        'font-awesome' => 'Font Awesome'
    );

    $missing_plugins = array();

    // Verifica cada plugin
    foreach($required_plugins as $plugin_slug => $plugin_name) {
        if(!is_plugin_active($plugin_slug . '/' . $plugin_slug . '.php')) {
            $missing_plugins[] = $plugin_name;
        }
    }

    // Só mostra a notificação se houver plugins faltando
    if(!empty($missing_plugins)) {
        // Verifica se a notificação já foi fechada
        $dismissed_notices = get_option('rua_dismissed_notices', array());
        
        if (!in_array('required_plugins', $dismissed_notices)) {
            add_action('admin_notices', function() use ($missing_plugins) {
                ?>
                <div class="notice notice-warning is-dismissible" data-notice="required_plugins">
                    <p>
                        <strong>Rua da Praia Shopping:</strong> 
                        Os seguintes plugins são necessários para o funcionamento completo do tema:
                        <ul style="list-style-type: disc; margin-left: 20px;">
                            <?php foreach($missing_plugins as $plugin): ?>
                                <li><?php echo esc_html($plugin); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </p>
                </div>
                <?php
            });

            // Adiciona script para lidar com o dismiss
            add_action('admin_footer', function() {
                ?>
                <script>
                jQuery(document).ready(function($) {
                    $(document).on('click', '.notice-warning .notice-dismiss', function() {
                        var notice = $(this).parent().data('notice');
                        $.post(ajaxurl, {
                            action: 'dismiss_required_plugins_notice',
                            notice: notice,
                            nonce: '<?php echo wp_create_nonce('dismiss_notice'); ?>'
                        });
                    });
                });
                </script>
                <?php
            });
        }
    }
}

// Ajax handler para o dismiss
add_action('wp_ajax_dismiss_required_plugins_notice', function() {
    check_ajax_referer('dismiss_notice', 'nonce');
    
    $dismissed_notices = get_option('rua_dismissed_notices', array());
    $dismissed_notices[] = $_POST['notice'];
    update_option('rua_dismissed_notices', array_unique($dismissed_notices));
    
    wp_die();
});




/******************************************************************************
 * CONFIGURATIONS
******************************************************************************/

// CARREGANDO ARQUIVO DE CONFIGURAÇÂO PARA CUSTOMIZAÇÂO DO TEMA
require get_template_directory() . '/inc/customizer.php';

// evitar erro caso o tema seja instalado em uma versao antiga do WP
if( ! function_exists( 'wp_body_open' ) ){
    function wp_body_open(){
        do_action( 'wp_body_open' );
    }
}

// habilitaando menus
function rua__config() {
    // MENU MANAGER
    register_nav_menus(
        array(
            'rua--primary-menu' => 'Menu Principal',
            'rua--footer-menu' => 'Menu Rodapé'
        )
    );
    // LOGO DO SITE
    add_theme_support( 'custom-logo', array( 'width' => 200, 'height' => 110 ) );
    // TITLE TAG
    add_theme_support( 'title-tag' );
    // LEITURA DE FEED
    add_theme_support( 'automatic-feed-links' );

    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'rua__config', 0 );




/******************************************************************************
 * INCLUINDO ARQUIVOS NECESSARIOS AO TEMA
******************************************************************************/

function rua__load_scripts() {
    // Google Fonts
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap', array(), null );
    // incluindo style CSS padrão
    wp_enqueue_style( 'rua--style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ), 'all' );
    // css output.css tailwind
    wp_enqueue_style( 'rua--themestyle', get_template_directory_uri() . '/src/css/output.css', array(), filemtime( get_template_directory() . '/src/css/output.css' ), 'all' );
}
add_action( 'wp_enqueue_scripts', 'rua__load_scripts' );




/******************************************************************************
 * AJAX FUNCTIONALITY
******************************************************************************/

function rua__ajax_scripts() {
    // Carrega o script principal
    wp_enqueue_script( 'rua-scripts', get_template_directory_uri() . '/src/scripts/script.js',  array('jquery'), filemtime(get_template_directory() . '/src/scripts/script.js'), true );

    // Localiza o script para AJAX - Alterando de ruaAjax para wp_ajax
    wp_localize_script('rua-scripts', 'wp_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'rua__ajax_scripts');

function load_more_posts() {
    check_ajax_referer('ajax-nonce', 'nonce');
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $post_type = sanitize_text_field($_POST['post_type']);
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 5;
    
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            
            // Output based on post type
            switch($post_type) {
                case 'rua_blog':
                    get_template_part('parts/cards/card', 'blog');
                    break;
                    
                case 'rua_lojas':
                    get_template_part('parts/cards/card', 'lojas');
                    break;
                    
                case 'rua_servicos':
                    get_template_part('parts/cards/card', 'servicos');
                    break;
            }
        }
        $output = ob_get_clean();
        wp_reset_postdata();
        
        wp_send_json_success([
            'html' => $output,
            'hasMore' => $query->max_num_pages > $page
        ]);
    } else {
        wp_send_json_error('No posts found');
    }
    
    wp_die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');


function carregar_post_modal() {
    check_ajax_referer('ajax-nonce', 'nonce');
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
    $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : null;
    
    if ($post_id || $slug) {
        global $post;
        
        if ($post_id) {
            $post = get_post($post_id);
        } else {
            $post = get_page_by_path($slug, OBJECT, ['rua_servicos', 'rua_lojas', 'rua_blog']);
        }
        
        if ($post) {
            setup_postdata($post);
            
            ob_start();
            
            switch ($post->post_type) {
                case 'rua_blog':
                    include(get_template_directory() . '/parts/modal/modal-blog.php');
                    break;
                case 'rua_lojas':
                    include(get_template_directory() . '/parts/modal/modal-lojas.php');
                    break;
                case 'rua_servicos':
                    include(get_template_directory() . '/parts/modal/modal-servicos.php');
                    break;
                default:
                    echo 'Template não encontrado';
            }
            
            $content = ob_get_clean();
            wp_reset_postdata();
            
            wp_send_json_success([
                'title' => get_the_title($post),
                'content' => $content
            ]);
        }
    }
    
    wp_send_json_error('Post não encontrado');
}
add_action('wp_ajax_carregar_post_modal', 'carregar_post_modal');
add_action('wp_ajax_nopriv_carregar_post_modal', 'carregar_post_modal');


function buscar_posts() {
    check_ajax_referer('ajax-nonce', 'nonce');
    
    $search_term = sanitize_text_field($_POST['search']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $filter_value = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : '';
    
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        's' => $search_term
    );
    
    if ($filter_value) {
        if ($post_type === 'rua_lojas') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'rua_lojas_segmentos',
                    'field' => 'slug',
                    'terms' => $filter_value
                )
            );
        } elseif ($post_type === 'rua_blog') {
            $args['date_query'] = array(
                array(
                    'year' => $filter_value
                )
            );
        }
    }
    
    $query = new WP_Query($args);
    $results = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'id' => get_the_ID()
            );
        }
    }
    
    wp_reset_postdata();
    wp_send_json_success($results);
}
add_action('wp_ajax_buscar_posts', 'buscar_posts');
add_action('wp_ajax_nopriv_buscar_posts', 'buscar_posts');




/******************************************************************************
 * CUSTOMISATIONS ADMIN
******************************************************************************/

// Disable Gutenberg editor
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// Disable Gutenberg for widgets
add_filter('use_widgets_block_editor', '__return_false');

// mudando o logo da tela de login
function custom_login_logo() {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/src/imgs/logo-ruadapraiashopping.svg);
            width: 300px;
            height: 100px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            padding-bottom: 30px;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'custom_login_logo');

// Hide WordPress admin footer
function hide_wp_admin_footer() {
    echo '<style>#wpfooter { display: none !important; }</style>';
}
add_action('admin_head', 'hide_wp_admin_footer');

// Change login logo URL to site homepage
function custom_login_logo_url() {
    return home_url();
}
add_action('login_headerurl', 'custom_login_logo_url');

// Remove admin bar WordPress logo and comments
function remove_admin_bar_items($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('new-post');
}
add_action('admin_bar_menu', 'remove_admin_bar_items', 999);

function remove_dashboard_widgets() {
    remove_action('welcome_panel', 'wp_welcome_panel');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets', 999);

// Personalizando dashboard
function add_dashboard_widgets() {
    wp_add_dashboard_widget(
        'rua_blog_widget',
        'Blog',
        'display_blog_widget'
    );
    
    wp_add_dashboard_widget(
        'rua_lojas_widget',
        'Lojas',
        'display_lojas_widget'
    );
    
    wp_add_dashboard_widget(
        'rua_servicos_widget',
        'Serviços',
        'display_servicos_widget'
    );
}
add_action('wp_dashboard_setup', 'add_dashboard_widgets');

function display_blog_widget() {
    display_post_type_info('rua_blog', 'Blog');
}

function display_lojas_widget() {
    display_post_type_info('rua_lojas', 'Lojas');
}

function display_servicos_widget() {
    display_post_type_info('rua_servicos', 'Serviços');
}

function display_post_type_info($post_type, $label) {
    $count = wp_count_posts($post_type);
    $published = $count->publish;
    $draft = $count->draft;
    
    echo "<div style='margin-bottom: 10px;'>";
    echo "<p style='margin: 5px 0;'>Publicados: <strong>$published</strong></p>";
    echo "<p style='margin: 5px 0;'>Rascunhos: <strong>$draft</strong></p>";
    echo "<a href='" . admin_url("edit.php?post_type=$post_type") . "' class='button button-primary'>Gerenciar $label</a>";
    
    $recent_posts = get_posts(array(
        'post_type' => $post_type,
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    if ($recent_posts) {
        echo "<div style='margin-top: 15px;'>";
        echo "<h4 style='margin: 0 0 10px;'>Últimos cadastros:</h4>";
        echo "<ul style='margin: 0; padding-left: 20px;'>";
        foreach ($recent_posts as $post) {
            $edit_link = get_edit_post_link($post->ID);
            $status = $post->post_status == 'publish' ? '' : ' (' . $post->post_status . ')';
            echo "<li><a href='{$edit_link}'>{$post->post_title}</a>{$status}</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    
    echo "</div>";
}

