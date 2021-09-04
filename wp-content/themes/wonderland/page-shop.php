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

/* Start the Loop */

?>
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
        <a
            href="<?php echo get_site_url()?>/product-category/<?php echo $category->slug;?>"><?php echo $category->name;?></a>
    </li>
    <?php
		}
	}
}


?>
</ul>
<div class="product-list-div">
    <?php
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content/content-page' );

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile; // End of the loop.
?>
</div>
<script type="text/javascript">
$(function() {


    // $('.product-cate-side-menu li.child').fadeOut(0)
})
</script>
<?php
get_footer();