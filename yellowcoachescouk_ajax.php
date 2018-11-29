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

    $saveObj = $_REQUEST[ 's' ];

    $wcSave = (object) [
        'wcpid' => $saveObj[ 'wcpid' ],
        'content' => $saveObj[ 'content' ],
        'excerpt' => $saveObj[ 'excerpt' ],
        'name' => $saveObj[ 'name' ],
        'title' => $saveObj[ 'title' ]
    ];

    $YCWPDB = new YellowcoachescoukWPDB;
    $YCWPDB->editLocation( $saveObj[ 'location' ], $saveObj[ 'lid' ] );
    $YCWPDB->editWCProductPost( $wcSave );

    //needs error handling
    
    // echo json_encode( $result );
    wp_die();    
}

function yellowcoachescouk_admin_get_location_posts_content_html()
{
    yellowcoachescouk_check_ajax_token();
    
    $lid = $_REQUEST[ 'lid' ];
    $YCWPDB = new YellowcoachescoukWPDB;
    $wcpids = $YCWPDB->getWCPIDsLinkedToLocation( $lid );
    $n = 0;
    $firstWCPIDContent;

    // print_r( $wcpids );
    // exit;
    
    $html = '
        <div class="row">
            <div class="col">
                <label for="Yellowcoachescouk-admin-location-edit">Edit Location Name</label>
                <input id="Yellowcoachescouk-admin-location-edit" />
            </div>
        </div>
    ';
    
    if ( count( $wcpids ) < 1 )
    {
        $html .= '
            <div>No WooCommerce Products found for this location.</div>
        ';
    }
    else
    {
        $firstWcpid = array_shift( array_slice( $wcpids, 0, 1 ) )->wcpid;

        $html .= '
            <div class="row">
                <div id="Yellowcoachescouk-admin-location-select-wcproduct" class="col">
                    <button id="Yellowcoachescouk-admin-location-wcproduct-btn" class="yellowcoachescouk-dropbtn btn btn-primary" type="button" value="' . $firstWcpid . '">WC Product(s)</button>
                    <div id="Yellowcoachescouk-admin-dropdown-location-wcproduct" class="yellowcoachescouk-dropdown-content">
                        <input class="yellowcoachescouk-quote-search" type="text" placeholder="Search here" />
        ';

        foreach ( $wcpids as $w )
        {
            $post = '';

            if ( $n === 0 )
            {
                $firstWCPIDContent = $YCWPDB->getPostContentByWCPID( $w->wcpid )[0];
                $n++;

                $html .= '
                    <button type="button" class="yellowcoachescouk-admin-location-edit-anchor" value="' . $firstWCPIDContent->ID . '">' . $firstWCPIDContent->post_title . '</button>
                ';
            }
            else
            {
                $post = $YCWPDB->getPostContentByWCPID( $w->wcpid )[0];
                
                $html .= '
                    <button type="button" class="yellowcoachescouk-admin-location-edit-anchor" value="' . $post->ID . '">' . $post->post_title . '</button>
                ';
            }
        }

        $html .= '
                    </div>
                </div>
            </div>
        ';

        $html .= '
            <div id="Yellowcoachescouk-admin-location-hidden-edits" class="row">
                <div class="col">
                    <label for="Yellowcoachescouk-admin-location-hidden-edits-post-content">Post Content</label>
                    <input id="Yellowcoachescouk-admin-location-hidden-edits-post-content" placeholder="Post Content" value="' . $firstWCPIDContent->post_content . '" />
                    <label for="Yellowcoachescouk-admin-location-hidden-edits-post-title">Post Title</label>
                    <input id="Yellowcoachescouk-admin-location-hidden-edits-post-title" placeholder="Post Title" value="' . $firstWCPIDContent->post_title . '" />
                    <label for="Yellowcoachescouk-admin-location-hidden-edits-post-excerpt">Post Excerpt</label>
                    <input id="Yellowcoachescouk-admin-location-hidden-edits-post-excerpt" placeholder="Post Excerpt" value="' . $firstWCPIDContent->post_excerpt . '" />
                    <label for="Yellowcoachescouk-admin-location-hidden-edits-post-name">Post Name</label>
                    <input id="Yellowcoachescouk-admin-location-hidden-edits-post-name"  placeholder="Post Name" value="' . $firstWCPIDContent->post_name . '" />
                </div>
            </div>
        ';
    }
    
    echo json_encode( $html );
    wp_die();
}

function yellowcoachescouk_admin_get_wcproduct_content( $wcpid )
{
    yellowcoachescouk_check_ajax_token();
    
    $wcpid = $_REQUEST[ 'wcpid' ];
    $YCWPDB = new YellowcoachescoukWPDB;
    $wcPostContent = $YCWPDB->getPostContentByWCPID( $wcpid )[0];

    $html .= '
        <div class="col">
            <label for="Yellowcoachescouk-admin-location-hidden-edits-post-content">Post Content</label>
            <input id="Yellowcoachescouk-admin-location-hidden-edits-post-content" placeholder="Post Content" value="' . $wcPostContent->post_content . '" />
            <label for="Yellowcoachescouk-admin-location-hidden-edits-post-title">Post Title</label>
            <input id="Yellowcoachescouk-admin-location-hidden-edits-post-title" placeholder="Post Title" value="' . $wcPostContent->post_title . '" />
            <label for="Yellowcoachescouk-admin-location-hidden-edits-post-excerpt">Post Excerpt</label>
            <input id="Yellowcoachescouk-admin-location-hidden-edits-post-excerpt" placeholder="Post Excerpt" value="' . $wcPostContent->post_excerpt . '" />
            <label for="Yellowcoachescouk-admin-location-hidden-edits-post-name">Post Name</label>
            <input id="Yellowcoachescouk-admin-location-hidden-edits-post-name"  placeholder="Post Name" value="' . $wcPostContent->post_name . '" />
        </div>
    ';

    echo json_encode( $html );
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