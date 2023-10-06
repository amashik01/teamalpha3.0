<?php
   $page_id = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) ? esc_html( $_GET['page'] ) : '';
   $user_id = ( isset( $_GET['user_id'] ) && ! empty( $_GET['user_id'] ) && $_GET['user_id'] > 0 ) ? $_GET['user_id'] : 0;
   if($user_id!= ""){
     $sender_info   =  get_userdata($user_id);
    
     if($sender_info){
     $sender_fullname = esc_html( $sender_info->first_name ) . ' ' . esc_html( $sender_info->last_name );
    }
   }
?>
<section class="message-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <div class="chatlist">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="chat-header">
                                    <select name="user_id" class="postform sbchat-users"></select>
                                    <div class="msg-search">
                                        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="<?php echo esc_attr('Search','sb_chat')?>" aria-label="search">
                                    </div>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">

                                    <li class="nav-item user_nav" role="presentation">
                                        <?php 
                                            $user_top_id   =  isset($_GET['user_id']) ?   $_GET['user_id']  : "";

                                             if($user_top_id > 0){

                                            $user_name = get_userdata($user_top_id);
                                            $author_name =  $user_name->display_name;
                                        ?>
                                          <div class="main_user_name">
                                           
                                          <p><span>User Name:</span> 
                                            <?php  
                                                echo ucfirst($author_name);
                                            ?>
                                            </p>
                                        </div>    

                                    <?php  }   ?> 
                                        <button class="nav-link active" id="Open-tab" data-bs-toggle="tab" data-bs-target="#Open" type="button" role="tab" aria-controls="Open" aria-selected="true"><?php echo esc_html__('All Conversations','sb_chat')?></button>
                                      
                                    </li>
                                      
                                    </ul>
                                </div>
                                <div class="modal-body">
                                    <div class="messages-inbox chat-list" data-context="sbchat"><?php

                                        $page_id = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) ? esc_html( $_GET['page'] ) : '';
                                        $user_id = ( isset( $_GET['user_id'] ) && ! empty( $_GET['user_id'] ) && $_GET['user_id'] > 0 ) ? $_GET['user_id'] : 0;
                                        
                                        $user_conversations = array();

                                        $display_limit = 7;

                                        if ( $user_id !== 0 )
                                            $user_conversations = sbchat_get_conversations_by_user_id( $user_id, $display_limit );
                                            
                                        if ( $user_conversations !== false ) : ?>
                                            <ul class="chat-list-detail"><?php
                                                
                                                foreach( $user_conversations as $user_conversation ) :

                                                    $recipient_id = ( $user_id == $user_conversation['user_2'] ) ? absint( $user_conversation['user_1'] ) : absint( $user_conversation['user_2'] );
                                                    $recipient = get_userdata( $recipient_id );
                                                    $recipient_output = '';
                                                 //   $last_conversation_message = sbchat_get_last_conversation_message( $user_conversation['id'] );
                                                    $is_conversation_read = sbchat_get_conversation_status_check( $user_conversation, $user_id ); 
                                                    $last_message_sent_ago = (string) human_time_diff( strtotime( $user_conversation['updated'] ) );
                                                    $dashboard_page =  get_option('sb_plugin_options');
                                                    $dashboard_page  =  isset($dashboard_page['sb-dashboard-page']) ? get_the_permalink($dashboard_page['sb-dashboard-page']) : home_url();
                                                    $conversation_url =   $dashboard_page.'?action=view&conversation_id=' . $user_conversation['id']; 
                                                    $user_top_id   =  isset($_GET['user_id']) ?   $_GET['user_id']  : "";
                                                    $conversation_url = menu_page_url( 'sbchat_conversations', false );
                                                    $conversation_url   =    $conversation_url  .'&action=view&user_id='.$user_top_id.'&conversation_id=' . $user_conversation['id'];
                                                    ?>
                                                    
                                
                                                    <li <?php if ( $is_conversation_read === false ) echo 'class=""' ?> data-id="<?php echo esc_attr( $user_conversation['id'] ) ?>">
                                                        <a target="_self" data-conv="<?php echo esc_attr( $user_conversation['id'] ) ?>" href="<?php echo esc_attr( $conversation_url ) ?>" class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 sb-avatar">
                                                                <?php echo get_avatar( $recipient_id , 45 ) ?>
                                                            </div>
                                                            <div class="flex-grow-1 ms-3"><?php
                                                                if ( ! is_wp_error( $recipient ) ) {
                                                                    
                                                                    $recipient_nicename = esc_html( $recipient->display_name );
                                                                    $recipient_fullname = esc_html( $recipient->first_name ) . ' ' . esc_html( $recipient->last_name );
                                                                    
                                                                    $recipient_output = $recipient_nicename;
                                                                    if ( $recipient_nicename == "" )
                                                                        $recipient_output = $recipient_fullname;
                                                                } ?>
                                                                <h3 class="sender-details"><?php 
                                                                    if ( empty( $recipient_output ) )
                                                                        $recipient_output = __( 'User has been removed', 'sbchat_plugin' ); 
                                                                    echo esc_html( $recipient_output ) ?>
                                                                </h3>
                                                                <p>
                                                                    <?php echo esc_html( $last_message_sent_ago ) . ' ago'; ?>
                                                                </p>
                                                            </div>
                                                        </a>
                                                    </li><?php
                                                endforeach; ?>
                                            </ul><?php
                                            
                                            if ( count( $user_conversations ) > $display_limit ) { ?>
                                                
                                                <button type='button' class='btn btn-primary load-conversations' data-context="sbchat" data-limit="<?php echo esc_attr( $display_limit ) ?>" data-offset="<?php echo esc_attr( $display_limit ) ?>">
                                                    Load more conversations
                                                </button><?php
                                            } 
                                            
                                        endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chatbox">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content"><?php

                                $recipient_id = '';
                                $recipient = '';
                                $recipient_output = '';

                                $conversation_id = ( isset( $_GET['conversation_id'] ) && ! empty( $_GET['conversation_id'] ) ) ? esc_html( $_GET['conversation_id'] ) : 0;

                                $current_conversation = "";
                                if ( $conversation_id !== 0 )
                                    $current_conversation = sbchat_get_conversation_by_id( $conversation_id );

                                if ( ! $current_conversation ) { ?>

                                    <div class="msg-head"></div>
                                    <div class="modal-body" id="sbModalBody">
                                        <div class="msg-body"><ul class="messages-list"></ul></div>
                                    </div>
                                    <div class="send-box chat-footer">
                                        <h4 class="not-found"><?php esc_html_e('No Message found.','sb_chat'); ?></h4>
                                    </div><?php
                                }
                                
                                $user_1 = isset( $current_conversation['user_1'] ) ? $current_conversation['user_1'] : 0;
                                $user_2 = isset( $current_conversation['user_2'] ) ? $current_conversation['user_2'] : 0;

                                if ( $user_id == $user_1 || $user_id == $user_2 ) {

                                    $recipient_id = ( $user_1 == $user_id ) ? $user_2 : $user_1;
                                    $recipient = get_userdata( $recipient_id );



                                    if ( ! is_wp_error( $recipient ) && !empty($recipient) ) {
                                                                    
                                        $recipient_nicename = esc_html( $recipient->display_name );
                                        $recipient_fullname = esc_html( $recipient->first_name ) . ' ' . esc_html( $recipient->last_name );
                                        
                                        $recipient_output = $recipient_nicename;
                                        if ( $recipient_nicename  == "" )
                                            $recipient_output = $recipient_fullname;
                                    }

                                    if ( empty( $recipient_output ) )
                                        $recipient_output = __( 'User has been removed', 'sbchat_plugin' ); ?>

                                    <div class="msg-head">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 sb-avatar">
                                                        <?php echo get_avatar( $recipient_id , 45 ) ?>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h3><?php echo esc_html( $recipient_output ); ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <ul class="moreoption">
                                                    <li class="navbar nav-item dropdown dropstart">
                                                     <?php
                                                       $block_status = get_user_meta($recipient_id , 'sb_is_user_blocked' , true);
                                                       if($block_status){
                                                        $block_text  =   __('Unblock','sb_chat');
                                                       }
                                                       else {
                                                        $block_text  =   __('Block','sb_chat');
                                                       }
                                                     ?>
                                                        <a class="dropdown-toggle delete-single-chat e-button" data-delete="<?php echo esc_attr__( 'Are You Sure?', 'sb_chat' ); ?>" href="javascript:void(0)" data-conversation="<?php echo esc_attr($conversation_id); ?>"><span> <?php echo __('Delete','sb_chat');  ?> </span> </a>
                                                        <a class="dropdown-toggle e-button block-user-admin" data-block="<?php echo esc_attr__( 'Are You Sure?', 'sb_chat' ); ?>" href="javascript:void(0)" data-user_id="<?php echo esc_attr($recipient_id); ?>"  block_status  = "<?php echo esc_attr($block_status); ?>"><span> <?php echo  $block_text; ?> </span></a>
                                                        <div class="sb-notification success"><p><?php esc_html_e( 'Conversation is removed', 'sb_chat' ); ?></p></div>
                                                        
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="sbModalBody">
                                        <div class="msg-body">
                                            <ul class="messages-list"><?php
                                                $inbox_conversations = sbchat_get_inbox_conversations( $user_id, $conversation_id ); 
                                                if ( ! empty( $inbox_conversations ) ) echo wp_kses( $inbox_conversations, sbchat_inbox_conversations_allowed_html() ); ?>
                                            </ul>
                                        </div>
                                    </div><?php 
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
