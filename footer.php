<!-- modal -->
<div id="customModal" class="overlap stp1" style="display:none;">
    <div class="modal-overlap">
        <div class="modal-container">

            <span class="modal-close geral"><i class="fa-solid fa-xmark"></i></span>
            
            <div class="modal-wrap">
                <span class="modal-loading"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
                
                <div class="target-ajax"></div>
            </div>

        </div>
    </div>
</div>

<hr>
<footer id="main-footer">
    <p><?php echo esc_html(get_theme_mod("set_rua_footer_copyright")); ?></p>
</footer>
<!-- END #main-footer -->

</body>

<?php wp_footer(); ?>

</html>