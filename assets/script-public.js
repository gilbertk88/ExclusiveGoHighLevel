jQuery(document).ready(function($) {

    function iframe_height_revision(){
        var iFrameID = document.getElementById('hl_iframe_details_style');
        alert(iFrameID.contentWindow.document.body.scrollHeight);
        if(iFrameID){
            iFrameID.height = "" ;
            iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px" ;
        }
    }

    window.addEventListener(
        'message',
        function(e) {
          var eventName = e.data[0];
          var data = e.data[1];

          console.log('details:');
          console.log(data);
          
          if (eventName === 'highlevel.setHeight' && data.height) {
            var height = data.height + 30;
            var el = document.getElementById(data.id);
            el.height = height;
            el.style.minHeight = height+"px";
            if (typeof $ !== 'undefined' && $('.containerModal').find('iframe').length > 0) {
              $('.containerModal').css({
                position: 'absolute'
              });
            }
          }
        },
        false
      );

    $('#msgsndr-calendar').load(function() {
        // var h = $(this).contents().find('body').height();

        // alert('hello');

        // console.log( $(this).contents().find('#__nuxt') ) ;

        // h += $(this).contents().find('body').height()
        // $(this).height( h );
        // $(this)
        // $height_details = this.contentWindow.document.body.scrollHeight; //$('#hl_iframe_details_style').contents().find('html').height();

        // iframe_height_revision();

        // alert( "This is the thing:" + $height_details );

        // $( '#hl_iframe_details_style' ).css( 'height' ,  );
    })

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

        jQuery.ajax( {

            url: ajax_object.ajaxurl,

            type: 'post',

            contentType: false,

            processData: false,

            data: form_data,

            success: function ( response ) {

                response = JSON.parse( response ) ; 

                console.log( response ) ;

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