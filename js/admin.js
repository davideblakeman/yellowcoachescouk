( function( $ ) {

    $( document ).on( 'click', '#Yellowcoachescouk-admin-location-btn', function()
    {
        console.log( $( '#Yellowcoachescouk-admin-location-input-location' ).val() );

        var location = $( '#Yellowcoachescouk-admin-location-input-location' ).val();

        var data = {
            action: 'yellowcoachescouk_admin_add_location',
            security: yellowcoachescouk_ajax_object.security,
            l: location
        };
        
        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            // var output = $.parseJSON( output );
            console.log(  output );
        });
    });
    
    $( document ).on( 'click', '.yellowcoachescouk-quote-anchor-admin', function()
    {
        // console.log( $( this ) );
        $( this )
            .parent()
            .removeClass( 'yellowcoachescouk-show' );
        
        var lid = $( this ).attr( 'value' );
        var location = $( this ).html();
        // console.log( location );

        $( '#Yellowcoachescouk-admin-location-edit' ).val( location );
        $( '#Yellowcoachescouk-admin-location-save-btn' ).val( lid );
        // $( '#Yellowcoachescouk-admin-location-btn' ).attr( 'value', lid );
        
        // if ( ( origin !== '' & typeof origin !== 'undefined' ) & ( destination !== '' & typeof destination !== 'undefined' ) )
        // {
        //     yellowcoachescouk_quote_show_result( origin, destination );
        // }
    });

    $( document ).on( 'click', '#Yellowcoachescouk-admin-location-save-btn', function()
    {
        var location = $( '#Yellowcoachescouk-admin-location-edit' ).val();
        var lid = $( '#Yellowcoachescouk-admin-location-save-btn' ).val();

        console.log( lid );

        var data = {
            action: 'yellowcoachescouk_admin_edit_location',
            security: yellowcoachescouk_ajax_object.security,
            l: location,
            lid: lid
        };
        
        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            // var output = $.parseJSON( output );
            console.log(  output );
            // location.reload();
            // location.href = location.href;
            // window.location.href = window.location.href;
        });
    });
    

})( jQuery );