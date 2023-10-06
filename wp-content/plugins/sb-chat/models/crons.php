<?php


function sbchat_filter_cron_interval( $schedules ) {

    $run_cron_after_hours = SB_Chat::get_plugin_options( 'run_cron_after_hours' );
        if ( empty( $run_cron_after_hours ) || $run_cron_after_hours <= 0 || $run_cron_after_hours > 24 )
            $run_cron_after_hours = 6;

    $schedules['sbchat_cron_hours'] = array( 'interval' => HOUR_IN_SECONDS * $run_cron_after_hours, 'display'  => esc_html__( 'SBChat Cron Duration' ), );

    return $schedules;
}
add_filter( 'cron_schedules', 'sbchat_filter_cron_interval' );

function sbchat_cron_notify_unread_messages_via_email() {
    
    $unread_conversations = sbchat_get_all_unread_conversations();
    if ( $unread_conversations === false )
        return false;

    $email_notifications = array();
    foreach( $unread_conversations as $unread_conversation ) {

        if ( $unread_conversation['read_user_1'] === $unread_conversation['read_user_2'] )
            continue;

        $email_notification = array();
        $user_key = ( $unread_conversation['read_user_1'] == 0 ) ? 'user_1' : 'user_2';

        $notification_receiver_id = $unread_conversation[$user_key];
        $message_sender_id = ( $user_key === 'user_1' ) ? $unread_conversation['user_2'] : $unread_conversation['user_1'];
        $conversation_id = $unread_conversation['id'];

        $email_notification = array( 
            'notification_receiver_id' => $notification_receiver_id, 
            'message_sender_id' => $message_sender_id, 
            'conversation_id' => $conversation_id 
        ); $email_notifications[$notification_receiver_id][] = $email_notification;
    }

    if ( is_array( $email_notifications ) && count( $email_notifications ) > 0 )
        $email_notifications = array_values( $email_notifications );

    foreach( $email_notifications as $email_notification ) {

        $receiver_id = $email_notification[0]['notification_receiver_id'];
        $receiver_firstname = get_user_meta( $receiver_id, 'first_name', true );
        $receiver_lastname = get_user_meta( $receiver_id, 'last_name', true );
        
        $receiver_fullname = '';
        if ( ( isset( $receiver_firstname ) && isset( $receiver_lastname ) ) && ( ! empty( $receiver_firstname ) && ! empty( $receiver_lastname ) )  )
            $receiver_fullname = ucwords( esc_html( $receiver_firstname . ' ' . $receiver_lastname ) );
        
        $receiver_exist = get_user_by( 'id', $receiver_id );
        if ( ! $receiver_exist )
            continue;

        $receiver_data = get_userdata( $receiver_id );

        if ( empty( $receiver_fullname ) )
            $receiver_fullname = $receiver_data->user_nicename;

        $total_unread_messages_received = count( $email_notification );
        $email_notification_body = '';
        $senindex = 0;

        $conversation_ids = array();
        $conversation_status_updates = array();

        foreach( $email_notification as $unread_message_notification ) {

            $sender_id = $unread_message_notification['message_sender_id'];
            $sender_firstname = get_user_meta( $sender_id, 'first_name', true );
            $sender_lastname = get_user_meta( $sender_id, 'last_name', true );

            $sender_fullname = '';
            if ( ( isset( $sender_firstname ) && isset( $sender_lastname ) ) && ( ! empty( $sender_firstname ) && ! empty( $sender_lastname ) )  )
                $sender_fullname = ucwords( esc_html( $sender_firstname . ' ' . $sender_lastname ) );
        
            $sender_exist = get_user_by( 'id', $sender_id );
            if ( ! $sender_exist )
                continue;

            $sender_data = get_userdata( $sender_id );
            if ( empty( $sender_fullname ) )
                $sender_fullname = $sender_data->user_nicename;

            $email_notification_body .= <<< EMAIL_NOTIFICATION_BODY
                <span style="font-size: 17px; width: 100%; display: block; white-space: nowrap; line-height: 1.5;"> &#9745; <i>{$sender_fullname}</i> has sent you a <b>new message</b>. </span>
            EMAIL_NOTIFICATION_BODY;

            $conversation_ids[] = $unread_message_notification['conversation_id'];

            $senindex++;
        }

        $email_notification_template = SB_Chat::get_plugin_options( 'unread_messages_template' );
        if ( ( isset( $email_notification_template ) && ! empty( $email_notification_template ) ) ) {

            $email_notification_template = str_replace( array( '%receiver_fullname%', '%total_unread_messages%', '%message_senders%' ), array( $receiver_fullname, $total_unread_messages_received, $email_notification_body ), $email_notification_template );

            if ( ! empty( $receiver_data->user_email ) )
                $receiver_notified = true; //wp_mail( $receiver_data->user_email, __( 'Unread Message Notification' ), $email_notification_template, array( 'Content-Type: text/html; charset=UTF-8' ) );
            
            if ( $receiver_notified ) {

                if ( is_array( $conversation_ids ) && count( $conversation_ids ) > 0 )
                    foreach( $conversation_ids as $conversation_id )
                        $conversation_status_updates[] = sbchat_update_unread_conversation_status( $conversation_id, 1 );
            }
        }
    }
}
add_action( 'unread_conversations_notify_cron', 'sbchat_cron_notify_unread_messages_via_email' );