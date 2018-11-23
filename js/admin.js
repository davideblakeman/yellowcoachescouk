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
            // console.log(  output );
            // var output = $.parseJSON( output );
            console.log(  output );
        });
    });

})( jQuery );