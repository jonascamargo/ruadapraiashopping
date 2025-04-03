<section id="sec-hero">
    <div class="wrapper">
        
        <h1>HERO</h1>

        <?php if( get_theme_mod("set_rua_hero_btn_url") ): ?>
        <div class="action">
            <a href="<?php echo esc_html(get_theme_mod("set_rua_hero_btn_url")); ?>" class="btn-pink">
                <span><?php echo esc_html(get_theme_mod("set_rua_hero_btn_title")); ?></span>
                
                <?php if( get_theme_mod("set_rua_hero_btn_icone") ): ?>
                <i class="<?php echo esc_html(get_theme_mod("set_rua_hero_btn_icone")); ?>"></i>
                <?php endif; ?>
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>

<hr>