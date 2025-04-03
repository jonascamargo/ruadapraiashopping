<?php

function rua_customizer($wp_rua_customizer) {
    // Adiciona painel principal
    $wp_rua_customizer->add_panel('rua__theme_custom', [
        'title' => 'Personalização do site',
        'description' => 'Todas as configurações do tema',
        'priority' => 10
    ]);

    // Array com todas as seções do site
    $sections = [
        'hero' => 'Hero',
        'banner' => 'Banner',
        'lojas' => 'Lojas',
        'blog' => 'Novidades',
        'sobre' => 'Sobre',
        'contato' => 'Contato'
    ];

    // Cria as seções e campos
    foreach($sections as $key => $title) {
        // Adiciona seção
        $wp_rua_customizer->add_section("sec_rua_{$key}_info", [
            'title' => "{$title}",
            'panel' => 'rua__theme_custom',
            'priority' => 10
        ]);

        // Título da seção
        $wp_rua_customizer->add_setting("set_rua_{$key}_title", [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field'
        ]);
        
        $wp_rua_customizer->add_control("set_rua_{$key}_title", [
            'section' => "sec_rua_{$key}_info",
            'label' => 'Título da seção',
            'type' => 'text',
            'input_attrs' => [
                'placeholder' => 'Titulo'
            ]
        ]);
        

        $wp_rua_customizer->add_setting("set_rua_{$key}_separador", array(
            'sanitize_callback' => '__return_false',
        ));
        $wp_rua_customizer->add_control(new WP_Customize_Control($wp_rua_customizer, "set_rua_{$key}_separador", [
            'label'       => '',
            'description' => '<hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">',
            'section' => "sec_rua_{$key}_info",
            'type'        => 'hidden',
        ]));


        // Adiciona imagem apenas para seções específicas
        if(!in_array($key, [ 'contato', 'hero', 'banner' ])) {
            // Texto de reforço (bio)
            $wp_rua_customizer->add_setting("set_rua_{$key}_bio", [
                'type' => 'theme_mod',
                'default' => '',
                'sanitize_callback' => 'wp_kses_post'
            ]);
            
            $wp_rua_customizer->add_control("set_rua_{$key}_bio", [
                'section' => "sec_rua_{$key}_info",
                'label' => 'Texto de Reforço',
                'type' => 'textarea',
                'input_attrs' => [
                    'placeholder' => 'Texto de reforço ...'
                ]
            ]);
        }
        
        // Adiciona imagem apenas para seções específicas
        if(!in_array($key, ['contato', 'hero', 'banner', 'lojas', 'blog'])) {
            // Imagem da seção
            $wp_rua_customizer->add_setting("set_rua_{$key}_img", [
                'type' => 'theme_mod',
                'sanitize_callback' => 'absint'
            ]);
            
            $wp_rua_customizer->add_control(new WP_Customize_Media_Control($wp_rua_customizer,
                "set_rua_{$key}_img",
                array(
                    'label' => 'Imagem da Seção',
                    'section' => "sec_rua_{$key}_info",
                    'mime_type' => 'image'
                )
            ));
        }

        // Refresh parcial
        if (isset($wp_rua_customizer->selective_refresh)) {
            // Título
            $wp_rua_customizer->selective_refresh->add_partial("set_rua_{$key}_title", [
                'selector' => "#sec-{$key} header .sec-ttl",
                'container_inclusive' => false,
                'render_callback' => function() use ($key) {
                    return get_theme_mod("set_rua_{$key}_title");
                }
            ]);

            // Bio
            $wp_rua_customizer->selective_refresh->add_partial("set_rua_{$key}_bio", [
                'selector' => "#sec-{$key} header .sec-bio",
                'container_inclusive' => false,
                'render_callback' => function() use ($key) {
                    return get_theme_mod("set_rua_{$key}_bio");
                }
            ]);

            // Imagem
            $wp_rua_customizer->selective_refresh->add_partial("set_rua_{$key}_img", [
                'selector' => "#sec-{$key} .sec-img",
                'container_inclusive' => false,
                'render_callback' => function() use ($key) {
                    $img_id = get_theme_mod("set_rua_{$key}_img");
                    return $img_id ? wp_get_attachment_image($img_id, 'full') : '';
                }
            ]);
        }
    }


    $wp_rua_customizer->add_setting("set_rua_lojas_separador", array(
        'sanitize_callback' => '__return_false',
    ));
    $wp_rua_customizer->add_control(new WP_Customize_Control($wp_rua_customizer, "set_rua_lojas_separador", [
        'label'       => '',
        'description' => '<hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">',
        'section' => "sec_rua_lojas_info",
        'type'        => 'hidden',
    ]));


    // BOTOES CARREGAR MAIS
    // LOJAS
    $wp_rua_customizer->add_setting("set_rua_lojas_btn", [
        'default' => 'Carregar mais lojas',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_lojas_btn", [
        'section' => "sec_rua_lojas_info",
        'label' => 'Texto do botão (Carregar Mais)',
        'type' => 'text',
        'input_attrs' => [
            'placeholder' => 'Carregar mais'
        ]
    ]);
    // selective refresh
    if( isset($wp_rua_customizer->selective_refresh) ){
        $wp_rua_customizer->selective_refresh->add_partial('set_rua_lojas_btn', [
            'selector'        => '.lojas-wrapper .action',
            'render_callback' => function() {
                return get_theme_mod('set_rua_lojas_btn');
            },
        ]);
    }


    // BLOG
    $wp_rua_customizer->add_setting("set_rua_blog_btn", [
        'default' => 'Carregar mais novidades',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_blog_btn", [
        'section' => "sec_rua_blog_info",
        'label' => 'Texto do botão (Carregar Mais)',
        'type' => 'text',
        'input_attrs' => [
            'placeholder' => 'Carregar mais'
        ]
    ]);
    // selective refresh
    if( isset($wp_rua_customizer->selective_refresh) ){
        $wp_rua_customizer->selective_refresh->add_partial('set_rua_blog_btn', [
            'selector'        => '.blog-wrap .action',
            'render_callback' => function() {
                return get_theme_mod('set_rua_blog_btn');
            },
        ]);
    }






    $meios_contato = [
        'email' => [
            'label' => 'Email',
            'sanitize' => 'sanitize_email',
            'type' => 'email',
            'placeholder' => 'contato@site.com',
        ],
        'fone' => [
            'label' => 'Telefone',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text',
            'placeholder' => '(XX) XXXX-XXXX',
        ],
        'whats' => [
            'label' => 'Whatsapp',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text',
            'placeholder' => '(XX) XXXX-XXXX',
        ],
        'endereco' => [
            'label' => 'Endereço',
            'sanitize' => 'sanitize_text_field',
            'type' => 'text',
            'placeholder' => 'Rua A ....',
        ],
        'endereco_link' => [
            'label' => 'Goole Maps',
            'sanitize' => 'esc_url_raw',
            'type' => 'url',
            'placeholder' => 'https://www.google.com/maps/...',
        ],
    ];

    foreach($meios_contato as $key => $conf) {


        $wp_rua_customizer->add_setting("set_rua_contato_{$key}", [
            'default' => '',
            'sanitize_callback' => $conf['sanitize'],
        ]);
    
        $wp_rua_customizer->add_control("set_rua_contato_{$key}", [
            'section' => "sec_rua_contato_info",
            'label' => $conf['label'],
            'type' => $conf['type'],
            'input_attrs' => [
                'placeholder' => $conf['placeholder']
            ]
        ]);
        
        $wp_rua_customizer->selective_refresh->add_partial("set_rua_contato_{$key}", [
            'selector' => "ul.contact-list a.contato-to-{$key}",
            'render_callback' => function() use ($key) {
                return get_theme_mod("set_rua_contato_{$key}");
            }
        ]);
    }
    


    // SEC SOBRE / titulo da grade de serviços
    $wp_rua_customizer->add_setting("set_rua_sobre_ttl_servicos", [
        'default' => 'Nossos serviços',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_sobre_ttl_servicos", [
        'section' => "sec_rua_sobre_info",
        'label' => 'Titulo da lista de serviços',
        'type' => 'text',
        'input_attrs' => [
            'placeholder' => 'Titulo'
        ]
    ]);

    $wp_rua_customizer->selective_refresh->add_partial("set_rua_sobre_ttl_servicos", [
        'selector' => "#sec-sobre .grid-ttl",
        'container_inclusive' => false,
        'render_callback' => function() use ($key) {
            return get_theme_mod("set_rua_sobre_ttl_servicos");
        }
    ]);
    

    // FOOTER / Copyright
    $wp_rua_customizer->add_section("sec_rua_footer_info", [
        'title' => "Footer",
        'panel' => 'rua__theme_custom',
        'priority' => 10
    ]);

    $wp_rua_customizer->add_setting("set_rua_footer_copyright", [
        'default' => '© 2024 Rua da Praia Shopping. Todos os direitos reservados.',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_footer_copyright", [
        'section' => "sec_rua_footer_info",
        'label' => 'Texto do Copyright',
        'type' => 'text',
        'input_attrs' => [
            'placeholder' => '© 2024 Rua da Praia Shopping'
        ]
    ]);

    $wp_rua_customizer->selective_refresh->add_partial("set_rua_footer_copyright", [
        'selector' => "#main-footer p",
        'container_inclusive' => false,
        'render_callback' => function() {
            return get_theme_mod("set_rua_footer_copyright");
        }
    ]);


    // SEC CONTATO / Redes Sociais
    $wp_rua_customizer->add_section("sec_rua_social_info", [
        'title' => "Redes Sociais",
        'panel' => 'rua__theme_custom',
        'section' => 'sec_rua_contato_info'
    ]);

    $social_networks = [
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'x' => 'X (Twitter)',
        'youtube' => 'YouTube'
    ];

    foreach($social_networks as $key => $label) {
        $wp_rua_customizer->add_setting("set_rua_social_{$key}", [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw'
        ]);
        
        $wp_rua_customizer->add_control("set_rua_social_{$key}", [
            'section' => "sec_rua_social_info",
            'label' => $label,
            'type' => 'url',
            'input_attrs' => [
                'placeholder' => "https://{$key}.com/..."
            ]
        ]);

        if (isset($wp_rua_customizer->selective_refresh)) {
            $wp_rua_customizer->selective_refresh->add_partial("set_rua_social_{$key}", [
                'selector' => ".social-list li a",
                'container_inclusive' => false,
                'render_callback' => function() use ($key) {
                    return get_theme_mod("set_rua_social_{$key}");
                }
            ]);
        }
    }


    // SEPARADOR
    $wp_rua_customizer->add_setting('set_rua_hero_separador', array(
        'sanitize_callback' => '__return_false',
    ));
    $wp_rua_customizer->add_control(new WP_Customize_Control($wp_rua_customizer, 'set_rua_hero_separador', [
        'label'       => '',
        'description' => '<hr style="border: none; border-top: 1px solid #ccc; margin: 20px 0;">',
        'section'     => 'sec_rua_hero_info',
        'type'        => 'hidden',
    ]));

    // Botão / HERO
    // texto do botao
    $wp_rua_customizer->add_setting("set_rua_hero_btn_title", [
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_hero_btn_title", [
        'section' => "sec_rua_hero_info",
        'label' => 'Texto do botão',
        'type' => 'text',
        'input_attrs' => [
            'placeholder' => 'Titulo ...'
        ]
    ]);

    // link
    $wp_rua_customizer->add_setting("set_rua_hero_btn_url", [
        'sanitize_callback' => 'esc_url_raw'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_hero_btn_url", [
        'section' => "sec_rua_hero_info",
        'label' => 'Link externo',
        'type' => 'url',
        'input_attrs' => [
            'placeholder' => 'https:// ...'
        ]
    ]);

    // icone
    $wp_rua_customizer->add_setting("set_rua_hero_btn_icone", [
        'default' => 'fa-brands fa-instagram',
        'sanitize_callback' => 'sanitize_text_field'
    ]);
    
    $wp_rua_customizer->add_control("set_rua_hero_btn_icone", [
        'section' => "sec_rua_hero_info",
        'label' => 'Icone Fontawesome (cadastre somente as classes do icone)',
        'type' => 'text'
    ]);

    if (isset($wp_rua_customizer->selective_refresh)) {
        $wp_rua_customizer->selective_refresh->add_partial("set_rua_hero_btn_icone", [
            'selector' => "#sec-hero .action a",
            'container_inclusive' => false,
            'render_callback' => function() use ($key) {
                return get_theme_mod("set_rua_hero_btn_icone");
            },
            'settings' => array('set_rua_hero_btn_title'),
        ]);
    }

}
add_action('customize_register', 'rua_customizer');