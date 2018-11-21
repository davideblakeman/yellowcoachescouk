// var obj = new EventEmitter();

module.exports = ( $ ) => {

    // function( $ ) {

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
    
    // }( jQuery );

}