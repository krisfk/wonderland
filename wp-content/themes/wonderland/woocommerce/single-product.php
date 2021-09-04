<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

<?php while ( have_posts() ) : ?>
<?php the_post(); ?>

<div class="row">
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
                <a href="<?php echo get_site_url()?>/product-category/<?php echo $category->slug;?>">
                    <?php
                         if( $category->parent!==0)
                         { echo '- ';}
                    ?>
                    <?php echo $category->name;?></a>
            </li>
            <?php
		}
	}
}


?>
        </ul>

    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-12 product-info-div">
        <?php wc_get_template_part( 'content', 'single-product' ); ?>

        <?php endwhile; // end of the loop. ?>
        <!-- <div>photos</div> -->
        <!-- <div class="relate-product-div">

            <h2>相關商品</h2>
        </div> -->

        <?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

        <?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

    </div>



</div>

<script type="text/javascript">
$(function() {

    $('.woocommerce-product-gallery__image a').click(function() {
        $('.lightbox-ele').fadeOut(0);
        $('.enlarge-foto').fadeIn(0);
        var src = $(this).find('img').attr('src');
        $('.enlarge-foto').attr('src', src);
        $('.lightbox-bg').fadeIn(500);
    })

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
            if ($('.product-cate-side-menu-div select option').eq(i).html() == $('.product-list-div h1').html()) {
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
get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */