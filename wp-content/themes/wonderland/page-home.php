<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>

<div class="container text-center p-0">


    <div class="slides">


        <?php
if( have_rows('banners') )
{
    while ( have_rows('banners') ) 
    { the_row();
  ?>


        <div class="slide">
            <a href="<?php echo get_sub_field('banner_link') ?>">
                <img src="<?php echo wp_get_attachment_image_src(get_sub_field('banner_image'),'Full')[0]; ?>" alt="">
            </a>
        </div>
        <?php
    }

}


?>





    </div>


    <div class="home-news-div">
        <h2>最新消息</h2>

        <div class="home-news-div-slides-container mt-4">

            <div class="news-slides">

                <?php
				$query = new WP_Query( array( 'post_type' => 'post' ,'posts_per_page' => 4) );
			
				if ( $query->have_posts() ) {

					while ( $query->have_posts() ) {
		
						$query->the_post();
						?>
                <div class="news-slide">
                    <?php echo get_the_content();?>
                </div>

                <?php
		
        
					}
		
				}
				
				?>


            </div>


        </div>
    </div>

    <div class="home-category-div">
        <h2>貨品種類</h2>

        <div class="container">
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

                    if($category->slug !='uncategorized' && $category->parent ===0 )
                    {
                        ?>

                <div class="col-lg-4 col-md-4 col-sm-6 col-6 mb-4">
                    <!-- /product-category/sexy-lingerie/ -->
                    <a href="<?php echo get_site_url()?>/product-category/<?php echo $category->slug; ?>/"
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


    </div>


    <div class="home-new-product-div">
        <h2>最新貨品</h2>


        <div class="home-new-product-container container mt-4">

            <div class="row">


                <?php
            
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'orderby'    => 'menu_order',

                // 'product_cat'    => 'category slug here',
            );
            $query = new WP_Query( $args );


            while ( $query->have_posts() ) { 
                $query->the_post();
                ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-6  mb-4">
                    <a href="<?php echo get_permalink() ;?>" class="product-a"> <img
                            src="<?php  echo get_the_post_thumbnail_url();?>" alt="">
                    </a>
                    <?php
                    
                    $product = wc_get_product( get_the_ID() );
                    
                    ?>

                    <div class="product-name mt-3"><?php echo get_the_title();?></div>
                    <div class="product-price mt-2">

                        <?php
				if ( $product->is_type( 'variable' ) ) {
						?>

                        <?php
				}
				else
				{
					?>
                        $<?php
				
					echo wc_format_decimal( 	$product->get_regular_price(),2);
				?>
                        <?php
				}

				
				?>


                        <?php 
				//  $product->get_regular_price();
?></div>


                    <?php
                if($product->is_type('simple'))
                {
                    ?>
                    <form class="cart"
                        action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
                        method="post" enctype='multipart/form-data'>

                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
                            class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
                    </form>
                    <?php
                }

                if($product->is_type('variable'))
                {
                    ?>
                    <a class="look-option-btn-a btn" href="<?php echo get_permalink();?>">查看款式</a>
                    <?php
                }




                ?>
                </div>




                <?php
            }

            ?>


            </div>

        </div>
    </div>






    <!-- <a href="<?php echo get_site_url()?>/shop">
        <img class="uc-img" src="https://www.wonderlandshops.com/wp-content/uploads/2021/08/under-construction-1.jpg"
            alt=""></a> -->
</div>


<script type="text/javascript">
$(function() {




    $('.slides').slick({
        dots: true,
        arrows: false,
        autoplay: true,
        pauseOnFocus: false,
        infinite: true,
        speed: 800,

        autoplaySpeed: 5000,
        cssEase: 'ease-out',
        pauseOnHover: false





    });

    $('.news-slides').slick({
        dots: true,
        // arrows: false,
    });


    // news-slide


})
</script>
<?php

get_footer();