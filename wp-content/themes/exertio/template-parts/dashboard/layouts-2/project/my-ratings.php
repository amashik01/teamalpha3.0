<?php
$current_user_id = get_current_user_id();
$fl_id = get_user_meta($current_user_id,'freelancer_id',true);
$alt_id = '';
global $exertio_theme_options;
$reviews = get_freelancer_rating_detail($fl_id, 'service');
if($reviews != '')
{
        ?>
        <div class="content-wrapper">
            <div class="notch"></div>
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="d-flex align-items-end flex-wrap">
                            <div class="mr-md-3 mr-xl-5">
                                <h2><?php echo esc_html__('My Ratings','exertio_theme'); ?></h2>
                                <div class="d-flex "> <i class="fas fa-home text-muted"></i>
                                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Dashboard', 'exertio_theme' ); ?>&nbsp;</p>
                                    <?php echo exertio_dashboard_extention_return(); ?>
                                    <p><span> &nbsp;/ <?php echo get_rating($fl_id, '' , '','service'); ?></span> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card services">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="main-box">
                                <div class="fr-recent-review-box">
                                    <?php
                                    if(isset($exertio_theme_options['detail_seller_reviews_title']) && $exertio_theme_options['detail_seller_reviews_title'] != '')
                                    {
                                        ?>
                                        <div class="heading-contents">
                                            <h3><?php echo esc_html($exertio_theme_options['detail_seller_reviews_title']); ?></h3>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="fr-recent-container">
                                        <?php
                                        foreach ($reviews as $review)
                                        {
                                            $review_author = $review ->giver_id;
                                            ?>
                                            <div class="show-reviews">
                                                <div class="fr-recent-content">
                                                    <div class="reviews-header">
                                                        <div class="fr-recent-review-profile"> <a href="<?php  echo esc_url(get_permalink($review_author)); ?>"><?php echo get_profile_img($review_author, "employer"); ?></a> </div>
                                                        <div class="fr-recent-location-details">
                                                            <a href="<?php  echo esc_url(get_permalink($review_author)); ?>">
                                                                <h4> <?php echo exertio_get_username('employer',$review_author, 'badge', 'right'); ?></h4>
                                                            </a>
                                                            <ul>
                                                                <li><span><i class="fas fa-clock"></i><?php echo time_ago_function($review->timestamp); ?></span> </li>

                                                            </ul>
                                                        </div>
                                                        <div class="fr-recent-rating">
                                                            <p><?php echo esc_html(number_format($review->star_avg,1)); ?></p>
                                                            <span class="xm">out of 5</span>
                                                        </div>

                                                    </div>
                                                    <p class="feedback"><?php echo stripslashes($review->feedback); ?></p>
                                                    <div class="individual-stars">
                                                        <div class="individual-star-boxs">
                                                            <label> <?php echo esc_html($exertio_theme_options['service_first_title']); ?></label>
                                                            <span>
								<?php
                                $total_stars_1 = $review->star_1;
                                for($i =0; $i<5; $i++)
                                {
                                    if($i<$total_stars_1){
                                        ?>
                                        <i class="fa fa-star colored"></i>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <i class="fa fa-star"></i>
                                        <?php
                                    }
                                }
                                ?>
								</span>
                                                        </div>
                                                        <div class="individual-star-boxs">
                                                            <label>  <?php echo esc_html($exertio_theme_options['service_second_title']); ?></label>
                                                            <span>
									<?php
                                    $total_stars_2 = $review->star_2;
                                    for($i =0; $i<5; $i++)
                                    {
                                        if($i<$total_stars_2){
                                            ?>
                                            <i class="fa fa-star colored"></i>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <i class="fa fa-star"></i>
                                            <?php
                                        }
                                    }
                                    ?>
								</span>
                                                        </div>
                                                        <div class="individual-star-boxs">
                                                            <label>  <?php echo esc_html($exertio_theme_options['service_third_title']); ?></label>
                                                            <span>
									<?php
                                    $total_stars_3 = $review->star_3;
                                    for($i =0; $i<5; $i++)
                                    {
                                        if($i<$total_stars_3){
                                            ?>
                                            <i class="fa fa-star colored"></i>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <i class="fa fa-star"></i>
                                            <?php
                                        }
                                    }
                                    ?>
								</span>
                                                        </div>
                                                    </div>
                                                </div>
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
            </div>
        </div>
        <?php
}

else {?>

<div class="nothing-found">
                            <h3><?php echo esc_html__( 'Sorry!!! No Record Found', 'exertio_theme' ) ?></h3>
                            <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>">
                        </div>


<?php 
}
?>
