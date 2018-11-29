( function( $ ) {

    $( document ).ready( function()
    {
        
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
        else if ( $( this ).parent().is( '#Yellowcoachescouk-quote-dropdown-options-destination' ) )
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

    $( document ).on( 'click', '#Yellowcoachescouk-quote-purchase', function()
    {
        var product_id = $( '#Yellowcoachescouk-quote-result' ).val();

        $.ajax({
            url : yellowcoachescouk_ajax_object.ajax_url,
            type : 'post',
            data : 
            {
                action: 'yellowcoachescouk_quote_purchase',
                security: yellowcoachescouk_ajax_object.security,
                product_id: product_id,
                product_sku: '',
                qty: 1,
                variation_id: 0,
            },
        
            success : function( response )
            {
                window.location = '/basket/';
            }
        
        });
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

        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            var output = $.parseJSON( output );
            var error = '<h2 class="yellowcoachescouk-error">ERROR: </h2>Something unexpected happened. Please <a href="/contact/">contact</a> us and let us know of this issue.';

            if ( output === null )
            {
                $( '#Yellowcoachescouk-quote-result' ).html( 'Our apologies, we have not recorded a quote for these locations. Please <a href="/contact/">contact</a> us directly for a quote.' );
            }
            else if ( typeof output.cost == 'undefined' | typeof output.wcpid == 'undefined' )
            {
                $( '#Yellowcoachescouk-quote-result' ).html( error );
            }
            else if ( $.isNumeric( output.cost ) & $.isNumeric( output.wcpid ) )
            {
                $( '#Yellowcoachescouk-quote-result' ).html( '£' + output.cost );
                $( '#Yellowcoachescouk-quote-result' ).attr( 'value', output.wcpid );
            }
            else
            {
                $( '#Yellowcoachescouk-quote-result' ).html( error );
            }
        });
    }

})( jQuery );