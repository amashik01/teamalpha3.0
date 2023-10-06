<?php
$items_per_page = get_option( 'posts_per_page' );
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;
global $exertio_theme_options;
global $wpdb;
			$table_name = $wpdb->prefix . 'EXERTIO_INVITATION_TBL';
			//if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table)
			//{
$current_user_id = get_current_user_id();
$employer_id = get_user_meta( $current_user_id, 'employer_id' , true );

$query = "SELECT * FROM ".$table_name ." WHERE `employer_id` = " . $current_user_id;


$total_query = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total = $wpdb->get_var( $total_query );

$results = $wpdb->get_results( $query.' ORDER BY `timestamp` DESC LIMIT '. $offset.', '. $items_per_page, OBJECT );

$pagination = paginate_links( array(
                        'base' => add_query_arg( 'cpage', '%#%' ),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil($total / $items_per_page),
                        'current' => $page,
						'type' => 'array',
                    ));

if ( is_user_logged_in() ) {
  ?>
<div class="content-wrapper">
  <div class="notch"></div>
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="d-flex justify-content-between flex-wrap">
        <div class="d-flex align-items-end flex-wrap">
          <div class="mr-md-3 mr-xl-5">
            <h2><?php echo esc_html__('Job Invitations','exertio_theme'); ?>
              ( <?php echo $total ?> ) 
            </h2>
            <div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
              <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Dashboard', 'exertio_theme' ); ?>&nbsp;</p>
              <?php echo exertio_dashboard_extention_return(); ?> </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card services">
      <div class="card mb-4">
        <div class="card-body">
          <div class="pro-section">
            <div class="pro-box heading-row">
              <div class="pro-coulmn pro-title"> </div>
              <div class="pro-coulmn"><?php echo esc_html__( 'Cost / Type', 'exertio_theme' ) ?> </div>
               <div class="pro-coulmn"><?php echo esc_html__( 'Employer', 'exertio_theme' ) ?> </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Status', 'exertio_theme' ) ?> </div>
              <div class="pro-coulmn"><?php echo esc_html__( 'Action', 'exertio_theme' ) ?> </div>
            </div>
            <?php
            if ( !empty($results) )
			{
        
              foreach($results as $result)
			  {
         
                $pid = $result->project_id;
                $status   =   $result->status;
                $posted_date = date_i18n( get_option( 'date_format' ), strtotime( $result->timestamp ) );

                $freelancer_id = $result->freelancer_id

                ?>
				<div class="pro-box">
				  <div class="pro-coulmn pro-title">
					<h4 class="pro-name">
						<a href="<?php  echo esc_url(get_permalink($pid)); ?>"><?php echo esc_html(get_the_title($pid)); ?></a>
							<span class="bidding-badges">
							 
							</span>
					</h4>
					<span class="pro-meta-box">
						<span class="pro-meta"> <i class="far fa-calendar-alt"></i> <?php echo	esc_html($posted_date); ?> </span>
						
					
				</div>
				  <div class="pro-coulmn">
					  <?php 
							 $type = get_post_meta($pid, '_project_type', true);
               $price = get_post_meta($pid, '_project_cost', true); 
							 if($type == 'fixed' || $type == 1)
							{
                
               
							 	echo esc_html(fl_price_separator(  $price)).'/'.esc_html__( 'Fixed ', 'exertio_theme' );
							// 	echo '<small class="estimated-hours">'.esc_html__( 'Days to Complete ', 'exertio_theme' ).$result->day_to_complete.'</small>';
							 }
						else if($type == 'hourly' || $type == 2)
							 {
							 	echo esc_html(fl_price_separator( $price )).' '.esc_html__( 'Hourly ', 'exertio_theme' );
							 	//echo '<small class="estimated-hours">'.esc_html__( 'Estimated Hours ', 'exertio_theme' ).$result->allowed_hours.'</small>';
							}
              ?>
						 
				  </div>
				  <div class="pro-coulmn">
             <?php 
                
                $emp_id = get_user_meta($result->employer_id, 'employer_id', true);
                   $emp_name = get_post_meta($emp_id, '_employer_dispaly_name', true);

                  echo esc_html( $emp_name, 'exertio_theme' );

              //  echo '<small class="estimated-hours">'.esc_html__( 'Days to Complete ', 'exertio_theme' ).$result->day_to_complete.'</small>';
            ?>  
				  	
				</div>

         <div class="pro-coulmn">
               <?php
                        if($status == "1") {
                           $offer_Status   =   esc_html__('Pending','exertio_theme');
                        }
                         else if($status == "2"){
                         $offer_Status   =   esc_html__('Accepted','exertio_theme');
                         }

                         else if($status == "3"){
                          $offer_Status   =   esc_html__('Rejected','exertio_theme');
                         }
                         else if($status == "4"){
                          $offer_Status   =   esc_html__('Cancelled By author','exertio_theme');
                         }
             ?>
                     <p> <?php echo esc_html($offer_Status); ?></p> 
         </div>
				  <div class="pro-coulmn">
					<span class="pro-btns">
              <?php if($result->status == "1" ){ ?>
						  <a href="javascript:void(0)" class="btn btn-inverse-danger btn-sm change-invite-status" data-status ="cancel" data-fid = "<?php echo esc_attr($freelancer_id ); ?>" data-pid="<?php echo esc_attr($result->project_id ); ?>"  data-offer-id ="<?php echo $result->id ?>"> <i class="far fa-times-circle"></i> <?php echo esc_html__( 'Cancel', 'exertio_theme' ); ?></a>
                     <?php  } ?>
					  </span> 
				  </div>
				</div>
            <?php //"<?php  echo esc_url(get_permalink($pid)); 
            }
				if ( ! empty( $pagination ) )
				{
					?>
			  <div class="fl-navigation">
				<ul class="pagination">
					<?php foreach ( $pagination as $key => $page_link ) : ?>
						<li class="<?php if ( strpos( $page_link, 'current' ) !== false ) { echo ' active'; } ?>"><?php echo wp_return_echo($page_link); ?></li>
					<?php endforeach ?>
				</ul>
			</div>
			<?php 
			}
            //wp_reset_postdata();
            }
            else {
              ?>
            <div class="nothing-found">
              <h3><?php echo esc_html__( 'Sorry!!! No Record Found', 'exertio_theme' ) ?></h3>
              <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo esc_attr__( 'Nothing found icon', 'exertio_theme' ) ?> "> </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
} else {
  echo exertio_redirect( home_url( '/' ) );
  ?>
<?php
}
?>
