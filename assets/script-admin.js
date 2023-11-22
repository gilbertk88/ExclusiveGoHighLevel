jQuery(document).ready(function($) {

    $('#iframe_submit').click(function (e) {

        e.preventDefault();

        $("#iframe_submit").attr( 'value', 'Updating...' ) ;

        if( $( '#hl_iframe_url' ).val().length == 0 ) {

            $('#hl_iframe_url').css('border', '2px red solid');

            $("#hl_required_field").html('Please fill in the above required field.'); 

            $('#hl_iframe_url').focus(function() {

                $('#hl_iframe_url').css('border', '1px solid #8c8f94');
                $("#hl_required_field").html('');

            });

        }
        else{
            add_update_iframe_details();
        }


    } )

    function add_update_iframe_details() {

        var form_data = new FormData() ;
        
        form_data.append( 'action', 'wp_add_update_iframe_details' ) ;

        form_data.append( 'iframe_url_details' , $( '#hl_iframe_url' ).val() ) ;

        form_data.append( 'iframe_post_id' , high_level_calender_page_id ) ;

        jQuery.ajax( {

            url: ajax_object.ajaxurl,

            type: 'post',

            contentType: false,

            processData: false,

            data: form_data,

            success: function ( response ) {

                response = JSON.parse( response ) ; 

                console.log( response ) ;

                // Get the page
                $("#hl_display_page").html( '<a href="'+ response.guid + '" >' + response.post_title + ' </a>' ) ;

                $("#iframe_submit").attr( 'value', 'Save Changes' ) ;

                $("#hl_update_progress").html('Saving successful!');

                $(".wt_dark_background_company" ).hide() ;

            },

            error: function (response) {

                console.log( response ) ;
            }

        } ) ;

    }

} ) ;