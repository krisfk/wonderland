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

<div class="home-new-product-container mt-4">


    <div class="row mt-4">



        <?php
            $orderby = 'name';
            $order = 'asc';
            $hide_empty = false ;
            $cat_args = array(
                'orderby'    => $orderby,
                'order'      => $order,
                'hide_empty' => $hide_empty,
            );

            $product_categories = get_terms( 'product_cat', $cat_args );
            if( !empty($product_categories) ){
 
                foreach ($product_categories as $key => $category) {

                    if($category->slug !='uncategorized' && $category->parent ===0)
                    {
                        // echo 1;
                        ?>

        <div class="col-lg-4 col-md-4 col-sm-6 col-6  mb-4">
            <a href="<?php echo get_site_url()?>/product-category/<?php echo $category->slug; ?>"
                class="home-category-img-a">
                <div class="category-name">
                    <?php
                                    echo $category->name;

                        ?></div>
                <img src="<?php echo z_taxonomy_image_url($category->term_id);?>" alt="">
            </a>
        </div>

        <?php
                                }
                            }
                        }
          ?>



    </div>

</div>

<?php

get_footer();