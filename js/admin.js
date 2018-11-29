( function( $ ) {

    $( document ).on( 'click', '#Yellowcoachescouk-admin-location-btn', function()
    {
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
    
    $( document ).on( 'click', '.yellowcoachescouk-dropbtn', function()
    {
        $( this )
            .parent()
            .find( '[id^=Yellowcoachescouk-admin-dropdown-location]' )
            .toggleClass( 'yellowcoachescouk-show' );
    });
    
    $( document ).on( 'click', '.yellowcoachescouk-quote-anchor-admin', function()
    {
        $( this )
            .parent()
            .removeClass( 'yellowcoachescouk-show' );
        
        var lid = $( this ).attr( 'value' );
        var location = $( this ).html();

        var data = {
            action: 'yellowcoachescouk_admin_get_location_posts_content_html',
            security: yellowcoachescouk_ajax_object.security,
            lid: lid
        };
        
        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            var output = $.parseJSON( output );
            $( '#Yellowcoachescouk-admin-location-save-btn' ).val( lid );
            $( '#Yellowcoachescouk-admin-location-ajax-edit' ).html( output );
            $( '#Yellowcoachescouk-admin-location-edit' ).val( location );
        });
    });

    $( document ).on( 'click', '.yellowcoachescouk-admin-location-edit-anchor', function()
    {
        var wcpid = $( this ).val();

        $( '#Yellowcoachescouk-admin-dropdown-location-wcproduct' ).removeClass( 'yellowcoachescouk-show' );

        var data = {
            action: 'yellowcoachescouk_admin_get_wcproduct_content',
            security: yellowcoachescouk_ajax_object.security,
            wcpid: wcpid
        };
        
        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            var output = $.parseJSON( output );
            $( '#Yellowcoachescouk-admin-location-wcproduct-btn' ).val( wcpid );
            $( '#Yellowcoachescouk-admin-location-hidden-edits' ).html( output );
        });
    });

    $( document ).on( 'focusout', '.yellowcoachescouk-dropbtn', function( e )
    {
        var needle = e.relatedTarget !== null ? e.relatedTarget.className : 'null';

        var haystack = [
            'yellowcoachescouk-quote-anchor-admin',
            'yellowcoachescouk-admin-location-edit-anchor'
        ];
        
        if ( $.inArray( needle, haystack ) === -1 || needle === 'null' )
        {
            $( this )
                .parent()
                .find( '[id^=Yellowcoachescouk-admin-dropdown-location-]' )
                .removeClass( 'yellowcoachescouk-show' );
        }
    });

    $( document ).on( 'click', '#Yellowcoachescouk-admin-location-save-btn', function()
    {
        var saveObj = {
            location: $( '#Yellowcoachescouk-admin-location-edit' ).val(),
            content: $( '#Yellowcoachescouk-admin-location-hidden-edits-post-content' ).val(),
            title: $( '#Yellowcoachescouk-admin-location-hidden-edits-post-title' ).val(),
            excerpt: $( '#Yellowcoachescouk-admin-location-hidden-edits-post-excerpt' ).val(),
            name: $( '#Yellowcoachescouk-admin-location-hidden-edits-post-name' ).val(),
            lid: $( '#Yellowcoachescouk-admin-location-save-btn' ).val(),
            wcpid: $( '#Yellowcoachescouk-admin-location-wcproduct-btn' ).val()
        };

        var data = {
            action: 'yellowcoachescouk_admin_edit_location',
            security: yellowcoachescouk_ajax_object.security,
            s: saveObj
        };
        
        $.post( yellowcoachescouk_ajax_object.ajax_url, data, function( output )
        {
            // var output = $.parseJSON( output );
            console.log(  output );
            // window.location.href = window.location.href;
        });
    });

})( jQuery );