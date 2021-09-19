<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>
<!-- <header class="woocommerce-products-header">

    <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
    <?php endif; ?>

    <?php

	do_action( 'woocommerce_archive_description' );
	?>
</header> -->
<div class="text-center row">
    <div class="product-cate-side-menu-div col-lg-3 col-md-3 col-sm-12 col-12 ">
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
?>
        <ul class="product-cate-side-menu">
            <li>
                <a href="<?php echo get_site_url()?>/shop">最新貨品</a>

            </li>
            <?php
if( !empty($product_categories) ){

	foreach ($product_categories as $key => $category) {

		if($category->slug !='uncategorized' )
		{
			?>
            <li
                class="cate_<?php echo $category->term_id?> <?php echo $category->parent!==0 ? 'child child_'.$category->parent:''; ?>">
                <a href="<?php echo get_site_url()?>/product-category/<?php echo $category->slug;?>"><?php 
                    
                    if( $category->parent!==0)
                    { echo '- ';}
                    echo $category->name;?></a>
            </li>
            <?php
		}
	}
}


?>
        </ul>

    </div>


    <div class="product-list-div col-lg-9 col-md-9 col-sm-12 col-12 ">
        <?php


$cate = get_queried_object();
$cateID = $cate->term_id;

?>
        <h1 class="text-start mb-4"><?php 
            
            if($cate->name =='product' && !$_GET['s'])
            {
                echo '最新貨品';
            } 
            else if($_GET['s']){
                echo '搜尋結果';
            }
            else
            {
                echo $cate->name;
            }
		
		?></h1>
        <div class="row">
            <?php

if($_GET['s'])
{
    $args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
        's' => $_GET['s'],
		// 'ignore_sticky_posts'   => 1,
		'posts_per_page'        => 12,
		'tax_query'             => array(
			array(
				'taxonomy'      => 'product_cat',
				'field' => 'term_id', //This is optional, as it defaults to 'term_id'
				'terms'         => $cateID,
				'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			)
		)
	);
	$query = new WP_Query($args);
}
else if($cateID){
	$args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
		// 'ignore_sticky_posts'   => 1,
		'posts_per_page'        => 12,
		'tax_query'             => array(
			array(
				'taxonomy'      => 'product_cat',
				'field' => 'term_id', //This is optional, as it defaults to 'term_id'
				'terms'         => $cateID,
				'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			)
		)
	);
	$query = new WP_Query($args);
}
else
{
	$args = array(
        'post_type'      => 'product',
        'posts_per_page' => 9
        // 'product_cat'    => 'category slug here',
    );
    $query = new WP_Query( $args );
	// echo 11;
}

while ( $query->have_posts() ) { 
	$query->the_post();
	
?>

            <div class="col-lg-3 col-md-3 col-sm-6 col-6  mb-4">
                <a href="<?php echo  get_permalink();?>" class="product-a"> <img
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
                    <a class="look-option-btn-a btn" href="<?php echo get_permalink();?>">查看款式</a>

                    <?php
				}
				else
				{
					?>
                    $<?php
				
					echo wc_format_decimal( 	$product->get_regular_price(),2);
				?>
                    <form class="cart"
                        action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
                        method="post" enctype='multipart/form-data'>

                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
                            class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
                    </form>
                    <?php
				}

				
				?>


                    <?php 
				//  $product->get_regular_price();
?>
                </div>

            </div>

            <?php
}



?>


            <!-- <?php 
for($i=0;$i<8;$i++)
{
  ?>

            <div class="col-3 mb-4">
                <a href="" class="product-a"> <img
                        src="<?php echo get_template_directory_uri();?>/assets/images/dummy-product-foto.png" alt="">
                </a>

                <div class="product-name mt-3">Product Name</div>
                <div class="product-price mt-2">$XXX</div>

            </div>

            <?php
}
?> -->

        </div>
    </div>
</div>

<div class="pagination">
    <?php 
        echo paginate_links( array(
            'base'         => '%_%',
            str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'page' ) ),
            // 'format'       => '?page=%#%',
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

<script type="text/javascript">
$(function() {

    var copy_list = $('ul.product-cate-side-menu');


    turn_list(copy_list);

    $(window).resize(function() {
        turn_list(copy_list);
    })






});

function turn_list(copy_list) {

    if ($(window).width() < 768) {
        $('ul.product-cate-side-menu').each(function() {
            var $select = $('<select />');

            $(this).find('a').each(function() {
                var $option = $('<option />');
                $option.attr('value', $(this).attr('href')).html($(this).html());
                $select.append($option);
            });

            $(this).replaceWith($select);

        });

        $('.product-cate-side-menu-div select').addClass('form-select');

        for (i = 0; i < $('.product-cate-side-menu-div select option').length; i++) {
            console.log($('.product-cate-side-menu-div select option').eq(i).html() + ' ' + $('.product-list-div h1')
                .html());
            if (

                $('.product-cate-side-menu-div select option').eq(i).html() == $('.product-list-div h1').html() ||
                $('.product-cate-side-menu-div select option').eq(i).html() == '- ' + $('.product-list-div h1').html()
            ) {
                $('.product-cate-side-menu-div select option').eq(i).attr('selected', 'selected');
            }
        }

        $('.product-cate-side-menu-div select').unbind('change').bind('change', function() {

            window.location = $(this).val();
        })

    } else {
        $('.product-cate-side-menu-div select').replaceWith(copy_list);
    }
}
</script>


<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );