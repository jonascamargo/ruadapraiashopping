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