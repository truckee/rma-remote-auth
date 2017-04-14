<?php
/* Template Name: Member Content Template */

//Check if rma_member cookie is set
if (isset($_COOKIE['rma_member'])) {
    get_header();
    ?>
    <div class="primary-content col-md-7 col-md-push-2">
        <main id="main" class="main" role="main">
            <?php
            while (have_posts()) : the_post();
                get_template_part('partials/content', 'page');
                comments_template('/comments.php');
            endwhile; // End of the loop.  
            ?>

        </main><!-- #main -->
    </div>
    <?php
    get_sidebar('left');
    get_sidebar();
    get_footer();
}
else {
    wp_redirect(home_url('member-sign-in'));
    exit;
}