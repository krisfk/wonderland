<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

?>




<?php
/* Start the Loop */
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content/content-page' );

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile; // End of the loop.

?>

<div class="inner-container">

    <?php
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 10
		);
		
$query = new WP_Query( $args );
			
if ( $query->have_posts() ) {

	while ( $query->have_posts() ) {

		$query->the_post();
		
		?>

    <div class="news-div mb-4">

        <div class="news-date mb-2"><?php echo get_the_date('Y-m-d');?></div>
        <div class="news-title mb-2"><?php echo get_the_title();?></div>

        <div class="news-content">

            <?php echo get_the_content();?>
        </div>
    </div>
    <?php
	}
}
		?>

</div>


<div class="pagination">
    <?php 
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( '<', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( '>', 'text-domain' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
    ?>
</div>

<?php
get_footer();