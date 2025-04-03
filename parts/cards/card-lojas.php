<?php
$email = get_field('rua__loja_email');
$fone  = get_field('rua__loja_telefone');
$whats = get_field('rua__loja_whats');
$local = get_field('rua__loja_local');
    $local_titulo = $local['rua__loja_local_titulo'];
    $local_mapa   = $local['rua__loja_local_mapa'];
?>

<li class="card-bubble">

    <div class="img-loja">
        <?php the_post_thumbnail( 'medium' ); ?>
    </div>

    <div class="info-loja">

        <div class="loja-header">
            <h3><?php the_title(); ?></h3>

            <!-- ///// ///// lista de categorias -->
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
            <!-- ///// ///// lista de categorias -->
        </div>

        <ul class="loja-links">
            <?php if ( $local_mapa ) : ?>
            <li>
                <a href="<?php echo esc_url($local_mapa); ?>" class="loja-local modal-img" 
                    data-mapa="<?php echo esc_url($local_mapa); ?>"
                    data-titulo="<?php echo $local_titulo; ?>">
                    <i class="fa-solid fa-route"></i>
                    <span><?php echo $local_titulo; ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($fone) : ?>
            <li>
                <a href="tel:<?php echo preg_replace('/[^0-9]/', '', $fone); ?>" class="loja-phone">
                    <i class="fa-solid fa-phone-volume"></i>
                    <span><?php echo $fone; ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($whats) : ?>
            <li>
                <a href="https://wa.me/55<?php echo preg_replace('/[^0-9]/', '', $whats); ?>" target="_blank" class="loja-whats">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span><?php echo $whats; ?></span>
                </a>
            </li>
            <?php endif; ?>
        </ul>

        <div class="loja-more">
            <a href="<?php the_permalink(); ?>" class="read-more" data-post-id="<?php echo get_the_ID(); ?>">Saiba mais <i class="fa-solid fa-circle-info"></i></a>
        </div>

    </div>

</li>