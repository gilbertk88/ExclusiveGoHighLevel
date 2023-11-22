<?php

/**
 * Plugin Name: Exclusive Go High Level (Premium)
 * Plugin URI: https://exclusivewebmarketing.com/exclusive-gohighlevel/
 * Description: The plugin allows you to pass parameters to the go high level calender(a booking tool).
 * Version: 1.0.29
 * Update URI: https://api.freemius.com
 * Author: Exclusive Web Marketing
 * Author URI: https://exclusivewebmarketing.com/
 * Text Domain: exclusive-gohighlevel
 * Domain Path: /languages/
 * License: GPLv2 or any later version
 *
*/

if ( !function_exists( 'eghlc_fs' ) ) {
    // Create a helper function for easy SDK access.
    function eghlc_fs()
    {
        global  $eghlc_fs ;
        
        if ( !isset( $eghlc_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $eghlc_fs = fs_dynamic_init( array(
                'id'               => '9884',
                'slug'             => 'exclusive-go-high-level-calendar',
                'premium_slug'     => 'ExclusiveGoHighLevel-premium',
                'type'             => 'plugin',
                'public_key'       => 'pk_5142a369f900ae4d09651ab12094f',
                'is_premium'       => true,
                'is_premium_only'  => false,
                'has_paid_plans'   => true,
                'is_org_compliant' => false,
                'trial'            => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'             => array(
                'slug'    => 'high-level-calendar',
                'support' => false,
            ),
                'is_live'          => true,
            ) );
        }
        
        return $eghlc_fs;
    }
    
    // Init Freemius.
    eghlc_fs();
    // Signal that SDK was initiated.
    do_action( 'eghlc_fs_loaded' );
}

// Do not allow direct access to this file.
// Process images
// New company
function ewm_hl_my_edit_admin_menu()
{
    add_menu_page(
        __( 'Exclusive Go High Level', 'exclusive-gohighlevel' ),
        __( 'Exclusive Go High Level', 'exclusive-gohighlevel' ),
        'manage_options',
        'high-level-calender',
        'ewm_hl_my_admin_page_new_contents',
        'dashicons-admin-site-alt3',
        3
    );
}

add_action( 'admin_menu', 'ewm_hl_my_edit_admin_menu' );
function ewm_hl_my_admin_page_new_contents()
{
    $post_link_list = [];
    $high_level_calender_page_id = ewm_hl_create_get_calender_shortcode_page_data();
    $hl_post_detail = get_post( $high_level_calender_page_id );
    
    if ( $high_level_calender_page_id > 0 ) {
        // Get meta data
        $post_list_meta = get_post_meta( $high_level_calender_page_id );
        if ( is_array( $post_list_meta ) && !is_bool( $post_list_meta ) ) {
            if ( array_key_exists( 'hl_link_list', $post_list_meta ) ) {
                $post_link_list = $post_list_meta['hl_link_list'];
            }
        }
    } else {
        // Location list
        $high_level_calender_page_id;
    }
    
    $get_post_list = get_post( $high_level_calender_page_id );
    
    if ( is_object( $get_post_list ) ) {
        $company_name = $get_post_list->post_title;
    } else {
        $company_name = '';
    }
    
    ?>

    <?php 
    include dirname( __FILE__ ) . '/templates/manage_calender.php';
    ?>

        <script type="text/javascript"> 

            high_level_calender_page_id = <?php 
    echo  $high_level_calender_page_id ;
    ?>

        </script>

    <?php 
}

add_action( 'admin_enqueue_scripts', 'ewm_hl_load_admin_resources' );
function ewm_hl_load_admin_resources( $options )
{
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'ewm-hl-main-lib-uploader-js', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/script-admin.js', 'jquery' ) );
    wp_localize_script( 'ewm-hl-main-lib-uploader-js', 'ajax_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_style( 'ewm-hl-style_admin', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/style-admin.css' ) );
}

add_action( 'wp_enqueue_scripts', 'ewm_hl_load_public_resources' );
function ewm_hl_load_public_resources( $options )
{
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'ewm-hl-public-main-lib-uploader-js', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/script-public.js', 'jquery' ) );
    wp_localize_script( 'ewm-hl-public-main-lib-uploader-js', 'ajax_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_style( 'ewm-hl-style_public', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/style-public.css' ) );
}

function ewm_hl_create_get_calender_shortcode_page_data( $args = array() )
{
    $current_user_id = get_current_user_id();
    $rr_rotation_manager_post_id = get_option( 'hl_rotation_manager_post_id' );
    // @todo check if post exists
    $post_exists = get_post( $rr_rotation_manager_post_id, 'ARRAY_A' );
    if ( is_array( $post_exists ) ) {
        return $rr_rotation_manager_post_id;
    }
    // Add mapping to see were a product relate to the other
    // Add new product if the product does not exist => Update the old product of the product already exist // Add Post
    $post_title = 'Calendar display';
    //
    $post_name = 'Calendar_display';
    //
    $post_excerpt = 'Calendar_display';
    //
    $post_content = '[high_level_calendar]';
    //
    $content_slug = preg_replace( '#[ -]+#', '-', $post_title );
    // Create post
    $post_data = [
        "post_author"           => $current_user_id,
        "post_date"             => date( 'Y-m-d H:i:s' ),
        "post_date_gmt"         => date( 'Y-m-d H:i:s' ),
        "post_content"          => $post_content,
        "post_title"            => $post_title,
        "post_excerpt"          => $post_excerpt,
        "post_status"           => "draft",
        "comment_status"        => "open",
        "ping_status"           => "closed",
        "post_password"         => "",
        "post_name"             => $post_name,
        "to_ping"               => "",
        "pinged"                => "",
        "post_modified"         => date( 'Y-m-d H:i:s' ),
        "post_modified_gmt"     => date( 'Y-m-d H:i:s' ),
        "post_content_filtered" => "",
        "post_parent"           => 0,
        "guid"                  => "",
        "menu_order"            => 0,
        "post_type"             => "page",
        "post_mime_type"        => "",
        "comment_count"         => "0",
        "filter"                => "raw",
    ];
    global  $wp_error ;
    $new_post_data = [
        'post_id'     => '',
        'post_is_new' => '',
    ];
    $new_post_id = '';
    if ( array_key_exists( 'company_post_id', $args ) ) {
        
        if ( $args['company_post_id'] == 0 ) {
            $new_post_id = wp_insert_post( $post_data, $wp_error );
            $new_post_data['post_id'] = $new_post_id;
            $new_post_data['post_is_new'] = true;
        } else {
            $new_post_id = $args['company_post_id'];
            $new_post_data['post_id'] = $new_post_id;
            $new_post_data['post_is_new'] = false;
            // do product post update
            $post_data['ID'] = $new_post_id;
            wp_update_post( $post_data );
        }
    
    }
    add_option( 'hl_rotation_manager_post_id', $new_post_id );
    return $new_post_data;
}

add_action( 'wp_ajax_wp_add_update_iframe_details', 'ewm_hl_add_update_iframe_details' );
// add_action( 'wp_ajax_nopriv_wp_add_update_iframe_details', 'wp_add_update_iframe_details');
function ewm_hl_add_update_iframe_details( $args = array() )
{
    $hl_rotation_manager_post_id = get_option( 'hl_calender_iframe_url' );
    
    if ( !$hl_rotation_manager_post_id ) {
        add_option( 'hl_calender_iframe_url', $_POST['iframe_url_details'] );
    } else {
        update_option( 'hl_calender_iframe_url', $_POST['iframe_url_details'] );
    }
    
    $iframe_post_id = get_post( $_POST['iframe_post_id'] );
    echo  json_encode( [
        'calender_id_updated' => true,
        'post_title'          => $iframe_post_id->post_title,
        'guid'                => $iframe_post_id->guid,
    ] ) ;
    wp_die();
}

function ewm_hl_display_high_level_calender( $args = array() )
{
    // add source
    $source_url = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    if ( !is_array( $args ) ) {
        $args = [];
    }
    $args_count = count( $args );
    $get_count = count( $_GET );
    $hl_calender_iframe_url = get_option( 'hl_calender_iframe_url' );
    
    if ( is_string( $hl_calender_iframe_url ) ) {
        $strpos_hl_calender_iframe_url = strpos( $hl_calender_iframe_url, '?' );
    } else {
        $hl_calender_iframe_url = '';
    }
    
    
    if ( !$strpos_hl_calender_iframe_url ) {
        $hl_calender_iframe_url = $hl_calender_iframe_url . '?source=' . $source_url;
    } else {
        $hl_calender_iframe_url = $hl_calender_iframe_url . '&source=' . $source_url;
    }
    
    $strpos_hl_calender_iframe_url = strpos( $hl_calender_iframe_url, '?' );
    // add tag name
    $id = get_the_ID();
    $post_meta = get_post_meta( $id );
    $high_level_calender_tag = [];
    if ( !is_bool( $post_meta ) ) {
        $high_level_calender_tag = ( array_key_exists( 'high_level_calender_tag', $post_meta ) ? $post_meta['high_level_calender_tag'] : [] );
    }
    $high_level_calender_tag = $high_level_calender_tag;
    
    if ( !is_null( $high_level_calender_tag ) && is_array( $high_level_calender_tag ) ) {
        $strpos_hl_calender_iframe_url = false;
        if ( is_string( $hl_calender_iframe_url ) ) {
            if ( !empty($hl_calender_iframe_url) && strlen( $hl_calender_iframe_url ) > 0 ) {
                $strpos_hl_calender_iframe_url = strpos( $hl_calender_iframe_url, '?' );
            }
        }
        
        if ( !$strpos_hl_calender_iframe_url ) {
            if ( array_key_exists( '0', $high_level_calender_tag ) ) {
                $hl_calender_iframe_url = $hl_calender_iframe_url . '?tag=' . $high_level_calender_tag['0'];
            }
        } else {
            if ( array_key_exists( '0', $high_level_calender_tag ) ) {
                $hl_calender_iframe_url = $hl_calender_iframe_url . '&tag=' . $high_level_calender_tag['0'];
            }
        }
    
    }
    
    $strpos_hl_calender_iframe_url = strpos( $hl_calender_iframe_url, '?' );
    
    if ( $args_count > 0 ) {
        
        if ( !$strpos_hl_calender_iframe_url ) {
            $hl_calender_iframe_url = $hl_calender_iframe_url . '?';
        } else {
            $hl_calender_iframe_url = $hl_calender_iframe_url . '&';
        }
        
        $index_id = 1;
        foreach ( $args as $key => $value ) {
            $hl_calender_iframe_url .= $key . '=' . $value;
            if ( $index_id < $args_count ) {
                $hl_calender_iframe_url .= '&';
            }
            $index_id++;
        }
    }
    
    
    if ( $get_count > 0 ) {
        
        if ( !$strpos_hl_calender_iframe_url && $args_count == 0 ) {
            $hl_calender_iframe_url = $hl_calender_iframe_url . '?';
        } else {
            $hl_calender_iframe_url = $hl_calender_iframe_url . '&';
        }
        
        $get_index = 1;
        foreach ( $_GET as $key => $value ) {
            $hl_calender_iframe_url .= $key . '=' . $value;
            if ( $get_index < $get_count ) {
                $hl_calender_iframe_url .= '&';
            }
            $get_index++;
        }
    }
    
    return '<iframe src="' . $hl_calender_iframe_url . '" style="width: 100%;border:none;overflow: hidden;" scrolling="no" id="msgsndr-calendar"></iframe><br><script src="https://msgsndr.com/js/embed.js" type="text/javascript"></script>';
}

add_shortcode( 'high_level_calendar', 'ewm_hl_display_high_level_calender' );
/*
function display_calender_details( ){

    $the_details = file_get_contents( 'https://msgsndr.com/widget/booking/fHMOWhDNipZ5Cl6lmBEt' ) ;

    wp_die();

}
*/
// Add field:
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'ewm_hl_meta_box',
        'Calendar Tag',
        function ( $post ) {
        //var_dump( get_post_meta( $post->ID,'high_level_calender_tag') );
        wp_nonce_field( __FILE__, '_ewm_hl_data_nonce' );
        ?>

    			<p><input type="text" class="large-text" name="ewm_hl_data" value="<?php 
        echo  esc_attr( get_post_meta( $post->ID, 'high_level_calender_tag', true ) ) ;
        ?>"></p>

    			<?php 
    },
        'page',
        'side'
    );
} );
// Save field.
add_action( 'save_post', function ( $post_id ) {
    if ( isset( $_POST['ewm_hl_data'], $_POST['_ewm_hl_data_nonce'] ) && wp_verify_nonce( $_POST['_ewm_hl_data_nonce'], __FILE__ ) ) {
        update_post_meta( $post_id, 'high_level_calender_tag', sanitize_text_field( $_POST['ewm_hl_data'] ) );
    }
} );
// Add meta boxes to Page