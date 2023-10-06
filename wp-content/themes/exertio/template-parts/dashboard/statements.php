<?php global $exertio_theme_options;
$current_user_id = get_current_user_id();
$limit = get_option( 'posts_per_page' );
if (isset($_GET["pageno"])) 
{  
  $pageno  = $_GET["pageno"];  
}  
else {  
  $pageno=1;  
}
$start_from = ($pageno-1) * $limit;
if( is_user_logged_in() )
{
	?>
	<div class="content-wrapper">
		<div class="notch"></div>
		  <div class="row">
			<div class="col-md-12 grid-margin">
			  <div class="d-flex justify-content-between flex-wrap">
				<div class="d-flex align-items-end flex-wrap">
				  <div class="mr-md-3 mr-xl-5">
					<h2><?php echo esc_html__('Statements','exertio_theme'); ?></h2>
					<div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
						<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Dashboard', 'exertio_theme' ); ?>&nbsp;</p>
						<?php echo exertio_dashboard_extention_return(); ?>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
        <?php if(isset($exertio_theme_options['freelancer_show_earning']) && $exertio_theme_options['freelancer_show_earning'] == 1 )
        {?>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-4">
                    <div class="info-boxes info_boxes">
                        <div class="detail_loader_earning" style="position: relative">
                            <div class="loader-outer" style="display: none;">
                                <div class="loading-inner">
                                    <div class="loading-inner-meta">
                                        <div> </div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="metric">
                                    <span class="icon">
                                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 16 16"><g fill="#626262"><path fill-rule="evenodd" d="M0 12.5A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-6h-1v6a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-6H0v6z"/><path fill-rule="evenodd" d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5v2.384l-7.614 2.03a1.5 1.5 0 0 1-.772 0L0 6.884V4.5zM1.5 4a.5.5 0 0 0-.5.5v1.616l6.871 1.832a.5.5 0 0 0 .258 0L15 6.116V4.5a.5.5 0 0 0-.5-.5h-13zM5 2.5A1.5 1.5 0 0 1 6.5 1h3A1.5 1.5 0 0 1 11 2.5V3h-1v-.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5V3H5v-.5z"/></g></svg>
                                    </span>
                            <p>
                                <span class="title"><?php echo esc_html__('Freelancer Earning', 'exertio_theme' ); ?></span>
                            </p>
                        </div>
                        <p class="matric-bottom"> <a href="<?php echo esc_url(get_the_permalink());?>?ext=statements"><?php echo esc_html__('View Detail', 'exertio_theme' ); ?> <i class="fas fa-arrow-right"></i></a></p>
                    </div>
                </div>
            </div>
            <?php }?>
		  <div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12 grid-margin stretch-card">
				<div class="card mb-4">
					<div class="card-body">
					  <div class="pro-section">
						<div class="pro-box statement_page heading-row">
						  <div class="pro-coulmn"><?php echo esc_html__( 'Date', 'exertio_theme' ) ?> </div>
						  <div class="pro-coulmn"><?php echo esc_html__( 'Type', 'exertio_theme' ) ?> </div>
						  <div class="pro-coulmn pro-title"><?php echo esc_html__( 'Detail', 'exertio_theme' ) ?> </div>
						  <div class="pro-coulmn"><?php echo esc_html__( 'Price', 'exertio_theme' ) ?> </div>
							<div class="pro-coulmn"><?php echo esc_html__( 'Amount', 'exertio_theme' ) ?> </div>
						</div>
							<?php
							$notification = exertio_view_all_statements($start_from, $limit);
							if(!empty($notification))
							{
								echo $notification;
							}
							else
							{
								?>
								<div class="nothing-found">
								  <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo esc_attr__( 'Nothing found icon', 'exertio_theme' ) ?> ">
									<h3><?php echo esc_html__( 'No Statements Available', 'exertio_theme' ) ?></h3>
								</div>
								<?php
							}
							echo statement_pagination($pageno, $limit);
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
}
?>