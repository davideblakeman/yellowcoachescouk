<?php

if ( !defined( 'ABSPATH' ) ) die();

function yellowcoachescouk_ajax_quote_get()
{
    yellowcoachescouk_check_ajax_token();

    $origin = $_REQUEST[ 'o' ];
    $destination = $_REQUEST[ 'd' ];

    $YCWPDB = new YellowcoachescoukWPDB;
    $result = $YCWPDB->getQuote( $origin, $destination );

    echo json_encode( $result );
    wp_die();
}

function yellowcoachescouk_ajax_quote_purchase()
{
    yellowcoachescouk_check_ajax_token();

    $id = $_REQUEST[ 'id' ];
    $quantity = $_REQUEST[ 'qty' ];

    $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST[ 'product_id' ] ) );
    // $quantity = empty( $_POST[ 'quantity' ] ) ? 1 : wc_stock_amount($_POST['quantity']);
    $quantity = 1;
    // $variation_id = absint( $_POST[ 'variation_id' ] );
    $variation_id = 0;
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
    $product_status = get_post_status( $product_id );

    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status )
    {
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );

        if ( 'yes' === get_option('woocommerce_cart_redirect_after_add' ) )
        {
            wc_add_to_cart_message( array( $product_id => $quantity ), true );
        }

        WC_AJAX :: get_refreshed_fragments();
    }
    else
    {
        $data = array(
            'error' => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
        );

        echo wp_send_json( $data );
    }

    wp_die();
}

function yellowcoachescouk_admin_add_location()
{
    yellowcoachescouk_check_ajax_token();

    $location = strtolower( $_REQUEST[ 'l' ] );

    // print_r($location);
    // exit;

    $YCWPDB = new YellowcoachescoukWPDB;
    $result = $YCWPDB->addLocation( $location );

    echo json_encode( $result );
    wp_die();    
}

function yellowcoachescouk_admin_edit_location()
{
    yellowcoachescouk_check_ajax_token();

    $locationText = strtolower( $_REQUEST[ 'l' ] );
    $lid = $_REQUEST[ 'lid' ];

    // print_r($lid);
    // exit;

    $YCWPDB = new YellowcoachescoukWPDB;
    // $result = $YCWPDB->editLocation( $locationText, $lid );
    // $wcpidLinks = $YCWPDB->getWCPIDsLinkedToLocation( $lid );
    
    // if ( count( $wcpidLinks ) > 0 )
    // {
    //     foreach( $wcpidLinks as $w )
    //     {
    //         print_r($w->wcpid);
            
    //     }
    // }

    $YCWPDB->updatePostsLinkedToLocation( $lid );

    // echo json_encode( $result );
    wp_die();    
}

function yellowcoachescouk_check_ajax_token()
{
    if ( !check_ajax_referer( 'yellowcoachescouk-security-token', 'security' ) )
    {
        wp_send_json_error( 'Invalid security token sent.' );
        wp_die();
    }
}

?>