<div class="modal-content modal-blog">
    <div class="grid-modal">

        <div class="col-a">

            <div class="mobile-header-modal">
                <h3 class="modal-title">
                    <?php echo get_the_title($post->ID); ?>
                </h3>
                
                <div class="post-data"><?php echo get_the_date('d/m/Y', $post->ID); ?></div>
            </div>

            <div class="modal-img">
                <?php the_post_thumbnail( 'full' ); ?>
            </div>

        </div>

        <div class="col-b">

            <div class="desk-header-modal">
                <h3 class="modal-title">
                    <?php echo get_the_title($post->ID); ?>
                </h3>
                
                <div class="post-data"><?php echo get_the_date('d/m/Y', $post->ID); ?></div>
            </div>

            <div class="text-wrapper">
                <?php echo apply_filters('the_content', $post->post_content); ?>
            </div>

        </div>

    </div>
</div>