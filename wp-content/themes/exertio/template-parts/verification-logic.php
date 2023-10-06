<?php
// Email verificatioon
if( isset( $_GET['verification_key'] ) && $_GET['verification_key'] != "" && !is_user_logged_in()  )
{
    $token  = $_GET['verification_key'];
    $token_url  =   explode( '-exertio-uid-', $token );
    $key    =   $token_url[0];
    $uid    =   $token_url[1];
    $token_db   =   get_user_meta( $uid, 'sb_email_verification_token', true );
    if( $token_db != $key )
    {
         echo '<script>jQuery(document).ready(function($) { toastr.error("' . __("Invalid security token.", 'exertio') . '", "", {timeOut: 3500,"closeButton": true, "positionClass": "toast-top-right"}); });</script>';
    }
    else
    {

        update_user_meta($uid, 'sb_email_verification_token', '');
        update_user_meta($uid, 'is_email_verified', 1 );

        // Set the user's role (and implicitly remove the previous role).
        $user = new WP_User( $uid );
        $user->set_role( 'subscriber' );

        echo '<script>jQuery(document).ready(function($) { toastr.success("' . __(" Your account has been verified.", 'exertio') . '", "", {timeOut: 3500,"closeButton": true, "positionClass": "toast-top-right"}); });</script>';
    }
}