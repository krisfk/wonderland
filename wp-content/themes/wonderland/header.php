<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"
        integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"
        integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script type="text/javascript">
    $(function() {


        $('.enough-18').click(function() {
            $('.warn-lightbox').fadeOut(0);
            $.ajax("<?php echo get_site_url();?>/enter_session.php")
                .done(function() {
                    console.log('successs');
                    // alert("success");
                })

        })

        // $('.younger-than-18').click(function() {
        //     window.location = 'https://www.google.com.hk';

        // });
        $(window).resize(function() {
            $('.menu-close-btn').fadeOut(0);

            if ($(window).width() > 991) {
                $('.top-menu-ul').fadeIn(0);
            } else {
                $('.top-menu-ul').fadeOut(0);

            }
        })


        $('.menu-close-btn').click(function() {
            $('.top-menu-ul,.menu-close-btn').fadeOut(0);

        })

        $('.mobile-menu-btn').click(function() {
            $('.top-menu-ul,.menu-close-btn').fadeIn(500)

        })

        $('.search-icon-a').click(function() {
            $('.lightbox-ele').fadeOut(0);
            $('.search-div').fadeIn(0);
            // var src = $(this).find('img').attr('src');
            // $('.enlarge-foto').attr('src', src);
            $('.lightbox-bg').fadeIn(500);
        })


        $('input[type="text"],input[type="submit"],input[type="email"],input[type="tel"],input[type="password"],input[type="number"],button,textarea,select')
            .addClass('form-control');
        $('input[type="checkbox"]').addClass('form-check-input');


        $('.lightbox-click-area,.lightbox-close-btn').click(function() {
            $('.lightbox-bg').fadeOut(500);
        });


    })
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <?php
    session_start();
    if($_SESSION['enter'] != 'wonderland' )
    {
        ?>
    <div class="warn-lightbox">
        <div class="container">
            <!-- <a href="<?php echo get_site_url();?>" class="logo-a col-2"> -->
            <img style="height:100px" class="mt-5"
                src="<?php echo get_template_directory_uri()?>/assets/images/logo-clean.png" alt="">


            <div style="font-size:30px" class="mt-3"> 警告:
            </div>
            <div class="mt-3">
                本網頁內容及相關物品可能令人反感<br>
                不可將其內容及物品派發、傳閱、出售、出租、交給或出借予年齡未滿18歲的人士<br>
                或將其內容及物品向該等人士出示、<br>
                播凡年齡未滿十八或當地政府規定的合法年齡 !<br>
                請即按"離開"。
            </div>

            <div class="mt-3">

                <a href="javascript:void(0);" class="enough-18 warn-btn">18歲以上按此進入</a>
                <a href="http://www.google.com.hk" class="younger-than-18 warn-btn">18歲未滿離開</a>

            </div>

        </div>
    </div>
    <?php
    }
    
    ?>


    <?php


$args = array(  
    'post_type' => 'config',
    'post_status' => 'publish',
    'posts_per_page' => -1, 
);

$query = new WP_Query( $args ); 
 
$query->the_post(); 
$wts=get_field('whatsapp_no');
?>


    <a target="_blank" href="https://wa.me/852<?php echo str_replace(' ','',get_field('whatsapp_no'))?>"
        class="fixed-whatsapp-icon">


    </a>
    <?php
    wp_reset_postdata(); 

    ?>
    <a href="javascript:void(0);" class="menu-close-btn"></a>


    <div class="lightbox-bg">


        <div class="lightbox-click-area"></div>


        <a href="javascript:void(0);" class="lightbox-close-btn"></a>
        <img src="http://localhost:8888/wonderland/wp-content/uploads/2021/08/test-product-3-e1630187757431.jpg" alt=""
            class="enlarge-foto lightbox-ele">

        <div class="search-div lightbox-ele">
            <?php aws_get_search_form( true ); ?>
        </div>
    </div>
    <div id="page" class="site">

        <img class="wonderland-bg" src="<?php echo get_template_directory_uri()?>/assets/images/wonderland-bg.jpg"
            alt="">

        <a class="skip-link screen-reader-text"
            href="#content"><?php esc_html_e( 'Skip to content', 'wonderland' ); ?></a>



        <div class=" top-menu-div pt-4 container">
            <div class="row align-items-center">

                <a href="<?php echo get_site_url();?>" class="logo-a col-2">
                    <img src="<?php echo get_template_directory_uri()?>/assets/images/logo-clean.png" alt="">
                </a>


                <ul class=" top-menu-ul col-10">

                    <li class="mobile-logo-li"> <a href="<?php echo get_site_url();?>" class="logo-a col-2">
                            <img src="<?php echo get_template_directory_uri()?>/assets/images/logo-clean.png" alt="">
                        </a></li>
                    <?php
                                    $main_menu = wp_get_menu_array('main menu');
foreach ($main_menu as $menu_item) {

$url = $menu_item['url'];
$title = $menu_item['title'];
$class = $menu_item['class'];

$temp_arr=explode(get_site_url(),$url);
$slug=str_replace('/en/','',$temp_arr[1]);
$slug=str_replace('/cn/','',$slug);
$slug=str_replace('/','',$slug);


if(count($menu_item['children']))
{
  
    echo '<li><a class="level-1 parent '.$class.'" href="'.$url.'">'.$title.'</a>';

 
    echo '<ul class="mobile-menu-submenu">';
?>

                    <?php
    
    foreach ($menu_item['children'] as $sub_menu_item) 
    {
        $sub_url = $sub_menu_item['url'];
        $sub_title = $sub_menu_item['title'];
        
        $sub_temp_arr=explode(get_site_url(),$sub_url);
        $sub_slug=str_replace('/en/','',$sub_temp_arr[1]);
        $sub_slug=str_replace('/cn/','',$sub_slug);
        $sub_slug=str_replace('/','',$sub_slug);
        echo'<li><a class="'.$sub_slug.'" href="'.$sub_url.'">'.$sub_title.'</a></li>';
    }
    echo '</ul>';

}
else
{
echo '<li><a class="level-1 '.$slug.' '.$class.'" href="'.$url.'">'.$title.'</a>';

}
echo'</li>';


}



?>

                    <li>

                        <a href="<?php echo get_site_url()?>/my-account">
                            <?php
                        
                        if(is_user_logged_in())
                        {
                            ?>
                            會員帳號
                            <?php
                        }
                        else
                        {
                            ?>
                            會員登入
                            <?php
                        }
                        ?>




                        </a>
                    </li>
                    <?php
                        if(is_user_logged_in())
                        {
                            ?>
                    <li><a href="<?php echo wp_logout_url(get_site_url());?>">登出</a></li>
                    <?php
                        }
                    ?>

                    <li class="icon-li first">

                        <a href="#" class="icon-a search-icon-a">
                            <img class="top-menu-icon"
                                src="<?php echo get_template_directory_uri();?>/assets/images/search-icon.png" alt="">

                        </a>

                    </li>


                    <li class="icon-li">
                        <a href="<?php echo get_site_url()?>/cart" class="icon-a position-relative">

                            <?php
                        global $woocommerce;
                        // echo ;
                        
                        if($woocommerce->cart->cart_contents_count >0)
                        {
                            ?>
                            <div class="cart-num"><?php echo $woocommerce->cart->cart_contents_count;?></div>

                            <?php
                        }
                        
                        ?>


                            <img class="top-menu-icon"
                                src="<?php echo get_template_directory_uri();?>/assets/images/cart-icon.png" alt="">

                        </a>

                    </li>

                    <?php
                     $args = array(  
                        'post_type' => 'config',
                        'post_status' => 'publish',
                        'posts_per_page' => -1, 
                    );
                
                    $query = new WP_Query( $args ); 
                     
                    $query->the_post(); 
                       
                        
                        

                        
                    
                    ?>
                    <li class="icon-li">
                        <a href="https://www.instagram.com/<?php echo get_field('instagram_account');?>" class="icon-a">
                            <img class="top-menu-icon"
                                src="<?php echo get_template_directory_uri();?>/assets/images/ig-icon.png" alt=""></a>

                    </li>
                    <li class="icon-li">
                        <a href="https://www.facebook.com/<?php echo get_field('facebook_account');?>" class="icon-a">
                            <img class="top-menu-icon"
                                src="<?php echo get_template_directory_uri();?>/assets/images/fb-icon.png" alt=""></a>

                    </li>

                    <li class="ms-2 wts-num icon-li">Wts：<?php echo get_field('whatsapp_no');?></li>

                    <!-- 94444712 -->
                    <?php

wp_reset_postdata(); 
?>


                </ul>





                </ul>

                <div class="col-10 text-end mobile-menu-btn-div">
                    <a href="#" class="mobile-menu-btn"> <i class="fa fa-bars"></i>
                    </a>
                </div>
            </div>

        </div>

        <div id="content" class="site-content">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">