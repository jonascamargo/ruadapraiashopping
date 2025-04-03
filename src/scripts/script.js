jQuery(document).ready(function($) {


    // Módulo de Busca
    const search = {
        timeout: null,
        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            $('.search-field').on('input', this.handleInput.bind(this));
            $('.filter-select').on('change', this.handleFilterChange);
            $(document).on('click', this.handleClickOutside);
            $('.search-results').on('click', this.handleResultsClick);
        },
        handleInput: function(e) {
            const input = $(e.target);
            const container = input.closest('.search-container');
            const filter = container.find('.filter-select');
            const resultsContainer = container.find('.search-results');
            
            clearTimeout(this.timeout);
            
            if (input.val().length < 3) {
                resultsContainer.empty().hide();
                return;
            }
            
            this.timeout = setTimeout(() => {
                this.perform(input.val(), input.data('post-type'), filter.val(), resultsContainer);
            }, 500);
        },
        handleFilterChange: function(e) {
            const select = $(e.target);
            const container = select.closest('.search-container');
            const input = container.find('.search-field');
            
            if (input.val().length >= 3) {
                const resultsContainer = container.find('.search-results');
                search.perform(input.val(), input.data('post-type'), select.val(), resultsContainer);
            }
        },
        handleClickOutside: function(e) {
            if (!$(e.target).closest('.search-container').length) {
                $('.search-results').empty().hide();
            }
        },
        handleResultsClick: function(e) {
            e.stopPropagation();
        },
        perform: function(term, postType, filter, container) {
            $.ajax({
                url: wp_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'buscar_posts',
                    nonce: wp_ajax.nonce,
                    search: term,
                    post_type: postType,
                    filter: filter
                },
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        this.renderResults(response.data, container);
                    } else {
                        container.html('<div class="no-results">Nenhum resultado encontrado</div>').show();
                    }
                }.bind(this)
            });
        },
        renderResults: function(data, container) {
            container.empty();
            data.forEach(item => {
                container.append(`
                    <div class="search-item">
                        <a href="${item.url}" class="read-more" data-post-id="${item.id}">
                            ${item.title}
                        </a>
                    </div>
                `);
            });
            container.show();
            
            container.find('.read-more').on('click', function(e) {
                e.preventDefault();
                const postId = $(this).data('post-id');
                const postUrl = $(this).attr('href');
                postLoader.loadById(postId, postUrl);
                container.empty().hide();
            });
        }
    };

    // Módulo Modal
    const modal = {
        element: document.getElementById('customModal'),
        content: document.querySelector('.modal-content'),
        inner: document.querySelector('.modal-wrap .target-ajax'),
        overlap: document.querySelector('.overlap'),
        
        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            $(document).on('click', 'a.modal-img', this.handleImageClick.bind(this));
            $(document).on('keyup', this.handleKeyPress.bind(this));
        },
        handleImageClick: function(e) {
            e.preventDefault();
            const target = $(e.currentTarget);
            this.show({
                type: 'image',
                url: target.attr('href'),
                title: target.data('titulo')
            });
        },
        handleKeyPress: function(e) {
            if (e.key === 'Escape' && this.element.style.display === 'block') {
                this.hide();
            }
        },
        show: function(data) {
            this.overlap.classList.add('stp1');
            
            if (data.type === 'image') {
                this.loadImage(data);
            } else {
                this.loadContent(data);
            }
            
            this.element.style.display = "block";
            document.body.style.overflow = 'hidden';
            
            this.bindModalEvents();
        },
        loadImage: function(data) {
            const img = new Image();
            img.onload = () => {
                this.inner.innerHTML = `
                    <div class="modal-container-img">
                        <img src="${data.url}" alt="${data.title || ''}" class="w-full h-auto">
                    </div>`;
                setTimeout(() => this.overlap.classList.remove('stp1'), 100);
            };
            img.src = data.url;
        },
        loadContent: function(data) {
            // console.log('🚀 Iniciando carregamento do conteúdo');
            
            // Pre-process content to add loading attributes
            const processedContent = data.content.replace(/<img/g, '<img loading="lazy"');
            this.inner.innerHTML = processedContent;
            
            // Cache DOM queries
            const images = Array.from(this.inner.getElementsByTagName('img'));
            const iframes = Array.from(this.inner.getElementsByTagName('iframe'));
            const videos = Array.from(this.inner.getElementsByTagName('video'));

            if (!images.length && !iframes.length && !videos.length) {
                // console.log('✨ Nenhum elemento para carregar');
                this.overlap.classList.remove('stp1');
                return;
            }

            // Preload images
            const imagePromises = images.map(img => {
                // Add sizes and srcset attributes if missing
                if (!img.getAttribute('sizes')) {
                    img.sizes = '(max-width: 768px) 100vw, 50vw';
                }
                
                return new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => {
                        // console.log('⚠️ Timeout para imagem:', img.src);
                        resolve(); // Resolve instead of reject to continue loading
                    }, 122);

                    if (img.complete && img.naturalHeight !== 0) {
                        clearTimeout(timeout);
                        resolve();
                    } else {
                        img.onload = () => {
                            clearTimeout(timeout);
                            resolve();
                        };
                        img.onerror = () => {
                            clearTimeout(timeout);
                            // console.log('❌ Erro ao carregar imagem:', img.src);
                            resolve(); // Resolve instead of reject to continue loading
                        };
                    }
                });
            });

            // Handle other media with shorter timeouts
            const mediaPromises = [
                ...iframes.map(iframe => new Promise(resolve => {
                    const timeout = setTimeout(resolve, 3000);
                    iframe.onload = () => {
                        clearTimeout(timeout);
                        resolve();
                    };
                })),
                ...videos.map(video => new Promise(resolve => {
                    const timeout = setTimeout(resolve, 3000);
                    if (video.readyState >= 3) {
                        clearTimeout(timeout);
                        resolve();
                    } else {
                        video.oncanplay = () => {
                            clearTimeout(timeout);
                            resolve();
                        };
                    }
                }))
            ];

            // Load all media with individual timeouts
            Promise.all([...imagePromises, ...mediaPromises])
                .then(() => {
                    // console.log('✨ Todo conteúdo carregado com sucesso!');
                    this.overlap.classList.remove('stp1');
                })
                .catch(() => {
                    // console.warn('⚠️ Alguns elementos não carregaram completamente');
                    this.overlap.classList.remove('stp1');
                });
        },

        // Add method to cleanup resources
        cleanup: function() {
            const videos = this.inner.getElementsByTagName('video');
            Array.from(videos).forEach(video => {
                video.pause();
                video.src = '';
                video.load();
            });
            
            const iframes = this.inner.getElementsByTagName('iframe');
            Array.from(iframes).forEach(iframe => {
                iframe.src = '';
            });
        },

        bindModalEvents: function() {
            $(this.element).on('click', '.close-modal', () => this.hide());
            $(this.element).on('click', e => {
                if (!$(e.target).closest('.modal-content').length) {
                    this.hide();
                }
            });
        },

        hide: function() {
            this.cleanup();
            this.overlap.classList.add('stp1');
            setTimeout(() => {
                this.element.style.display = "none";
                document.body.style.overflow = '';
                window.history.pushState({}, '', '/');
                $(this.element).off('click');
            }, 100);
        }
    };

    // Módulo Post Loader
    const postLoader = {
        loadBySlug: function(slug) {
            $.ajax({
                url: wp_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'carregar_post_modal',
                    nonce: wp_ajax.nonce,
                    slug: slug,
                    full_content: true
                },
                success: function(response) {
                    if (response.success) {
                        modal.show(response.data);
                    }
                }
            });
        },
        loadById: function(postId, postUrl) {
            $.ajax({
                url: wp_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'carregar_post_modal',
                    nonce: wp_ajax.nonce,
                    post_id: postId,
                    full_content: true
                },
                success: function(response) {
                    if (response.success) {
                        window.history.pushState({}, '', postUrl);
                        modal.show(response.data);
                    }
                }
            });
        }
    };

    // Módulo Load More
    const loadMore = {
        pages: {},
        loading: false,
        
        init: function() {
            $('.carregar-mais').each(function() {
                const postType = $(this).data('post-type');
                loadMore.pages[postType] = 1;
                $(this).on('click', function(e) {
                    e.preventDefault();
                    loadMore.load($(this));
                });
            });
        },
        load: function(button) {
            if (this.loading) return;
            this.loading = true;
            
            const postType = button.data('post-type');
            const postsPerPage = button.data('posts-per-page');
            const container = `#${postType.replace('rua_', '')}-container`;
            
            this.performLoad(button, postType, postsPerPage, container);
        },
        performLoad: function(button, postType, postsPerPage, container) {
            button.text('Carregando...');
            
            $.ajax({
                url: wp_ajax.ajax_url,
                type: 'post',
                data: {
                    action: 'load_more_posts',
                    nonce: wp_ajax.nonce,
                    page: this.pages[postType] + 1,
                    post_type: postType,
                    posts_per_page: postsPerPage
                },
                success: (response) => this.handleLoadSuccess(response, button, postType, container),
                error: () => button.text('Erro ao carregar'),
                complete: () => this.loading = false
            });
        },
        handleLoadSuccess: function(response, button, postType, container) {
            if (response.success) {
                $(container).append(response.data.html);
                this.pages[postType]++;
                button.text(response.data.hasMore ? 'Carregar Mais' : 'Não há mais posts')
                      .prop('disabled', !response.data.hasMore);
            }
        }
    };

    // Módulo Menu
    const menuHandler = {
        init: function() {
            this.bindEvents();
        },
        bindEvents: function() {
            const menuButton = $('.mobile-menu-button');
            const menuWrapper = $('.wrapper-menu-nav');
            
            menuButton.on('click', this.toggleMenu.bind(this, menuWrapper));
            $('.menu-topo a[href^="#"]').on('click', this.handleMenuItemClick.bind(this, menuWrapper));
            $(window).on('scroll resize', () => menuWrapper.removeClass('show'));
            $(document).on('click', this.handleClickOutside.bind(this, menuWrapper));
        },
        toggleMenu: function(menuWrapper, e) {
            e.stopPropagation();
            menuWrapper.toggleClass('show');
        },
        handleMenuItemClick: function(menuWrapper, e) {
            e.preventDefault();
            const target = $($(e.currentTarget).attr('href'));
            
            if (target.length) {
                menuWrapper.removeClass('show');
                $('html, body').animate({
                    scrollTop: target.offset().top + 50
                }, 800);
            }
        },
        handleClickOutside: function(menuWrapper, e) {
            if (!$(e.target).closest('.wrapper-menu-nav').length) {
                menuWrapper.removeClass('show');
            }
        }
    };

    // Função de verificação de acesso direto
    const checkDirectAccess = function() {
        const currentPath = window.location.pathname;
        const validTypes = ['rua_servicos', 'rua_lojas', 'rua_blog'];
        
        for (const type of validTypes) {
            if (currentPath.includes(`${type}/`)) {
                const pathParts = currentPath.split('/').filter(Boolean);
                const slug = pathParts[pathParts.length - 1];
                if (slug) {
                    postLoader.loadBySlug(slug);
                    break;
                }
            }
        }
    };

    // Inicializações
    const init = function() {
        if ($('.carregar-mais').length) loadMore.init();
        if ($('.search-container').length) search.init();
        if ($('body').hasClass('home')) search.init();
        
        modal.init();
        menuHandler.init();
        checkDirectAccess();
        
        // Eventos globais
        $(document).on('click', '.read-more', function(e) {
            e.preventDefault();
            const postId = $(this).data('post-id');
            const postUrl = $(this).attr('href');
            postLoader.loadById(postId, postUrl);
        });
        
        window.onpopstate = function() {
            if (modal.element) modal.hide();
        };
    };

    // Iniciar aplicação
    init();


});