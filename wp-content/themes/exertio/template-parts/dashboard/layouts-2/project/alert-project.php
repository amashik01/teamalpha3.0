<?php global $exertio_theme_options;
$current_user_id = get_current_user_id();
$alt_id = '';
if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} else if ( get_query_var( 'page' ) ) {

	$paged = get_query_var( 'page' );
} else {
	$paged = 1;
}
	if( is_user_logged_in() )
	 {
         /* Display Jobs Alert Subscribers List */
         $jobs_alert = isset($exertio_theme_options['exertio_admin_job_alerts']) ? $exertio_theme_options['exertio_admin_job_alerts'] : false;
         $noti_html_alert = '';
         if ($jobs_alert) {
             if (current_user_can('editor') || current_user_can('administrator')) {

                 /* Getting Candidate Jobs Alert subscribers */
                 $cand_ids = exertio_get_candidate_alerts_list();
                 if (!empty($cand_ids)) {
                     foreach ($cand_ids as $key => $candidate_id) {
                         $alert_cand_id = $candidate_id->ID;
                         $user_exist = get_userdata($alert_cand_id);
                         $job_alert = exertio_get_candidates_job_alerts($alert_cand_id);
                         foreach ($job_alert as $key => $candidate_alert) {
                             if ($user_exist) {
                                 $user_display_name = $user_exist->display_name;
                             } else {
                                 $user_display_name = '';
                             }
                             // $user_name = isset($candidate_alert['alert_name']) ? $candidate_alert['alert_name'] : '';
                             $user_email = isset($candidate_alert['alert_email']) ? $candidate_alert['alert_email'] : '';
                             $user_alert_date = isset($candidate_alert['alert_start']) ? $candidate_alert['alert_start'] : '';
                             $user_alert_cat = isset($candidate_alert['alert_category']) ? $candidate_alert['alert_category'] : '';
                             $apply_datesss = date_i18n(get_option('date_format'), strtotime($user_alert_date));
                             $term_name = get_term($user_alert_cat)->name;
                             $noti_html_alert .= ' <li>
				<div class="notif-single">
				<a href="' . get_author_posts_url($current_user_id) . '">' . $user_display_name . '</a>' . " " . esc_html__('have activate Job Alerts on', 'exertio_theme') . '<a href="' . get_the_permalink($user_alert_cat) . '" class="notif-job-title">' . " " . $term_name . '</a>
				</div>
				<span class="notif-timing"><i class="icon-clock"></i> ' . ($apply_datesss) . '</span>
			</li>';
                         }
                     }
                 }
                 $noti_html_alert = $noti_html_alert;
             }
         } else {
             $noti_html_alert = '';
         }
		?>

<div class="content-wrapper">
  <div class="notch"></div>
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="d-flex justify-content-between flex-wrap">
        <div class="d-flex align-items-end flex-wrap">
          <div class="mr-md-3 mr-xl-5">
            <h2><?php echo esc_html__('Alert Projects','exertio_theme');?></h2>
			<div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
				<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Dashboard', 'exertio_theme' ); ?>&nbsp;</p>
				<?php echo exertio_dashboard_extention_return(); ?>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card mb-4">
        <div class="card-body">
          <div class="pro-section">
              <div class="pro-box heading-row">
                <div class="pro-coulmn pro-title">
                </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Category', 'exertio_theme' ) ?> </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Frequency', 'exertio_theme' ) ?> </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Action', 'exertio_theme' ) ?> </div>
              </div>
				<?php
					 $job_alert = exertio_get_candidates_job_alerts($current_user_id);
            if (isset($job_alert) && !empty($job_alert)) {
                $count = 1;
                $is_paid = isset($exertio_theme_options['job_alert_paid_switch']) ? $exertio_theme_options['job_alert_paid_switch'] : false;
                $expire_class = "";
                foreach ($job_alert as $key => $val) {

                    if ($is_paid) {
                        $current_date = strtotime(date('Y/m/d'));
                        $end_date = strtotime(isset($val['alert_end']) ? $val['alert_end'] : "");
                        $expire_class = $current_date > $end_date ? 'expire_alert' : "";
                    }
                    $terms = get_term_by('id', $val['alert_category'], 'project-categories');
                    $term_name = $terms->name;
                    $freq = isset($val['alert_frequency']) ? $val['alert_frequency'] : "";
				  
							?>
							  <div class="pro-box">
								<div class="pro-coulmn pro-title">
									<h4 class="pro-name">
                                        <?php echo esc_html($val['alert_name']); ?>
									</h4>
								</div>
								<div class="pro-coulmn">

											<?php echo esc_html($term_name); ?>

								</div>
								<div class="pro-coulmn">
                                    <p><?php
                                        if ($freq != "") {
                                            echo esc_html(exertio_get_candidates_job_alerts_freq($freq));
                                        } ?></p>
								</div>
								<div class="pro-coulmn"><a  data-value="<?php echo esc_attr($key); ?>" class="btn btn-custom btn-theme-secondary del_save_alert" ><?php echo esc_html__('Delete', 'exertio_theme'); ?></a></div>
							  </div>
						  
							<?php
						}
						
						fl_pagination($job_alert);
						wp_reset_postdata();
					}
					else
					{
						?>
                        <div class="nothing-found">
                            <h3><?php echo esc_html__( 'Sorry!!! No Record Found', 'exertio_theme' ) ?></h3>
                            <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>">
                        </div>
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
	}
	else
	{
		echo exertio_redirect(home_url('/'));
	?>
<?php
	}
	?>
