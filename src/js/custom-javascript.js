( function( $ ) {

    $( document ).ready( function()
    {
        // console.log( yellowcoachescouk_ajax_object.ajax_url );
    });

    $( document ).on( 'click', '.yellowcoachescouk-dropbtn', function()
    {
        $( this )
            .parent()
            .find( '[id^=Yellowcoachescouk-quote-dropdown-options-]' )
            .toggleClass( 'yellowcoachescouk-show' );
    });
    
    $( document ).on( 'keyup', 'input[class="yellowcoachescouk-quote-search"]', function()
    {
        var filter = $( this ).val().toUpperCase();

        $( this ).parent().find( 'button' ).each( function()
        {
            $( this ).html().toUpperCase().indexOf( filter ) > -1 ? 
                $( this ).show():
                $( this ).hide();
        });
    });

    $( document ).on( 'focusout', '.yellowcoachescouk-dropbtn', function( e )
    {
        var needle = e.relatedTarget !== null ? e.relatedTarget.className : 'null';

        var haystack = [
            'yellowcoachescouk-quote-search',
            'yellowcoachescouk-quote-anchor'
        ];
        
        if ( $.inArray( needle, haystack ) === -1 || needle === 'null' )
        {
            $( this )
                .parent()
                .find( '[id^=Yellowcoachescouk-quote-dropdown-options-]' )
                .removeClass( 'yellowcoachescouk-show' );
        }
    });

    $( document ).on( 'focusout', '.yellowcoachescouk-quote-search', function( e )
    {
        var needle = e.relatedTarget !== null ? e.relatedTarget.className : 'null';

        var haystack = [
            'null',
            'yellowcoachescouk-quote-anchor'
        ];
        
        if ( $.inArray( needle, haystack ) === -1 || needle === 'null' )
        {
            $( this )
                .parent()
                .removeClass( 'yellowcoachescouk-show' );
        }
    });

    $( document ).on( 'click', '.yellowcoachescouk-quote-anchor', function()
    {
        if ( $( this ).parent().is( '#Yellowcoachescouk-quote-dropdown-options-origin' ) )
        {
            $( '#Yellowcoachescouk-quote-detail-origin-selection' ).html( $( this ).html() );
            $( '#Yellowcoachescouk-quote-detail-origin-selection' ).attr( 'value', $( this ).val() );
        }
        else
        {
            $( '#Yellowcoachescouk-quote-detail-destination-selection' ).html( $( this ).html() );
            $( '#Yellowcoachescouk-quote-detail-destination-selection' ).attr( 'value', $( this ).val() );
        }

        $( this )
            .parent()
            .removeClass( 'yellowcoachescouk-show' );
        
        var origin = $( '#Yellowcoachescouk-quote-detail-origin-selection' ).attr( 'value' );
        var destination = $( '#Yellowcoachescouk-quote-detail-destination-selection' ).attr( 'value' );
        
        if ( ( origin !== '' & typeof origin !== 'undefined' ) & ( destination !== '' & typeof destination !== 'undefined' ) )
        {
            yellowcoachescouk_quote_show_result( origin, destination );
        }
    });

    $( document ).on( 'click', '#Yellowcoachescouk-quote-purchase', function( e )
    {
        e.preventDefault();
    });

    $( document ).on( 'click', '#Yellowcoachescouk-quote-purchase', function()
    {
        // var url = window.location.origin + '/wp-admin/admin-ajax.php';
        var product_id = $( '#Yellowcoachescouk-quote-result' ).val();

        $.ajax({
            url : yellowcoachescouk_ajax_object.ajax_url,
            type : 'post',
            data : 
            {
                action: 'yellowcoachescouk_quote_purchase',
                security: yellowcoachescouk_ajax_object.token,
                product_id: product_id,
                product_sku: '',
                qty: 1,
                variation_id: 0,
            },
        
            success : function( response )
            {
                console.log( response );
                window.location = '/basket/';
            }
        
        });
    });

    $( document ).on( 'click', '#Yellowcoachescouk-admin-location-btn', function()
    {
        // console.log( $( '#Yellowcoachescouk-admin-location-new-location' ).val() );

        $( '#Yellowcoachescouk-admin-location-input-location' ).val();


    });

    function yellowcoachescouk_quote_show_result( origin, destination )
    {
        var result = '';

        var data = {
            action: 'yellowcoachescouk_quote_get',
            security: yellowcoachescouk_ajax_object.security,
            o: origin,
            d: destination
        };
        
        // var url = window.location.origin + '/wp-admin/admin-ajax.php';

        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            console.log( output );
            if ( output.success !== undefined && output.success === false )
            {
                return;
            }

            var output = $.parseJSON( output );

            if ( $.isNumeric( output.cost ) & $.isNumeric( output.wcpid ) )
            {
                $( '#Yellowcoachescouk-quote-result' ).html( '£' + output.cost );
                $( '#Yellowcoachescouk-quote-result' ).attr( 'value', output.wcpid );
            }
            else
            {
                $( '#Yellowcoachescouk-quote-result' ).html( '<h2 class="yellowcoachescouk-error">ERROR: </h2>Something unexpected happened. Please <a href="/contact/">contact</a> us and let us know of this issue.' );
            }
        });
    }

})( jQuery );