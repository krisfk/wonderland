<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
    <ul>
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
        <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
            <a
                href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>


<script type="text/javascript">
$(function() {

    $('.woocommerce-product-gallery__image a').click(function() {
        $('.lightbox-ele').fadeOut(0);
        $('.enlarge-foto').fadeIn(0);
        var src = $(this).find('img').attr('src');
        $('.enlarge-foto').attr('src', src);
        $('.lightbox-bg').fadeIn(500);
    })

    var copy_list = $('.woocommerce-MyAccount-navigation ul');


    turn_list(copy_list);

    $(window).resize(function() {
        turn_list(copy_list);
    })






});

function turn_list(copy_list) {

    if ($(window).width() < 768) {
        $('.woocommerce-MyAccount-navigation ul').each(function() {
            var $select = $('<select />');

            $(this).find('a').each(function() {
                var $option = $('<option />');
                $option.attr('value', $(this).attr('href')).html($(this).html());
                $select.append($option);
            });

            $(this).replaceWith($select);

        });

        $('.woocommerce-MyAccount-navigation select').addClass('form-select');

        for (i = 0; i < $('.woocommerce-MyAccount-navigation select option').length; i++) {
            if ($('.woocommerce-MyAccount-navigation select option').eq(i).html() == $('h1.entry-title').html()) {
                $('.woocommerce-MyAccount-navigation select option').eq(i).attr('selected', 'selected');
            }
        }

        $('.woocommerce-MyAccount-navigation select').unbind('change').bind('change', function() {

            window.location = $(this).val();
        })

    } else {
        $('.woocommerce-MyAccount-navigation select').replaceWith(copy_list);
    }
}
</script>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>