<?php
/* Make cats selected on update Job */

global $exertio_theme_options;
$cat_html = exertio_add_taxonomies_on_job_alert('project-categories');
$today = date("Y/m/d");
$paid_alert_check    = isset($exertio_theme_options['job_alert_paid_switch'])  ?   $exertio_theme_options['job_alert_paid_switch']   :  false;
$alert_end   = "";
if($paid_alert_check){
    
    $product_id   = isset($exertio_theme_options['job_alert_package'])   ?   $exertio_theme_options['job_alert_package'] : "";
    $product = wc_get_product( $product_id );
    $days = get_post_meta($product_id, 'package_expiry_days', true);
    $alert_projects = get_post_meta($product_id, 'fl_alert', true);
    $end_date = date("Y/m/d",strtotime("+$days days"));
    $alert_end     =    '<input type="hidden" name="alert_end" value='.$end_date.'>';
}


?>
<div class="modal fade resume-action-modal" tabindex="-1" id="job-alert-subscribtion" aria-labelledby="job-alert-subscribtion" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php if($paid_alert_check && !is_wp_error($product) && !empty($product)){  ?>
                <form method="post" id="alert_job_form" class="alert-job-modal-popup" onsubmit="return false">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo esc_html__('Want to subscribe Project alerts?', 'exertio_theme'); ?></h4>
                        <button type="button" class="close btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>
                                <?php echo __('Alert Name', 'exertio_theme'); ?><span class="color-red">*</span>
                            </label>
                            <input placeholder="<?php echo __('Enter alert name', 'exertio_theme'); ?>" class="form-control" type="text" required data-smk-msg="<?php echo esc_attr__('Please enter alert name','exertio_theme'); ?>" name="alert_name">
                        </div>
                    </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label>
                                <?php echo __('Your Email', 'exertio_theme'); ?><span class="color-red">*</span>
                            </label>
                            <input placeholder="<?php echo __('Enter your email address', 'exertio_theme'); ?>" class="form-control" type="email" required data-smk-msg="<?php echo esc_attr__('Please enter your valid email','exertio_theme'); ?>" name="alert_email">
                        </div>
                    </div>
                            <?php if (exertio_get_terms('project-categories', true)) { ?>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>
                                    <?php echo __('Job Category', 'exertio_theme'); ?><span class="color-red">*</span>
                                </label>
                                <select class="select-generat" data-allow-clear="true" required data-smk-msg="<?php echo esc_attr__('Project Category required','exertio_theme'); ?>"  id="alert_sub_cat">
                                    <option value=""><?php echo __('Select an option', 'exertio_theme'); ?></option>
                                    <?php echo exertio_add_taxonomies_on_job_alert('project-categories', ''); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group" id="get_child_lev1">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div id="get_child_lev2" class="form-group">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div id="get_child_lev5" class="margin-top-10">
                            </div>
                        </div>
                        <input type="hidden" name="alert_category" id="get_cat_val" value="" />
                    <?php }  echo ''.$alert_end ?>
                            <input type="hidden" name="alert_start" value="<?php echo ''.($today); ?>" />
                        </div>
                    </div>
                    <div class="modal-footer">
                       <div class="alerts_limit none"><label> <?php echo esc_html("Alerts Limit : ", 'exertio_theme') . $alert_projects; ?> </label></div>
                        <button type="submit" name="submit" class="btn btn-theme btn-block" id="submit_paid_alerts">
                            <?php echo esc_html__('Submit ', 'exertio_theme');?><span class="none alerts_price">(<?php echo fl_price_separator($product->get_regular_price());?>)</span>
                        </button>
                    </div>
                </form>
            <?php } else  { ?>
                <form method="post" id="alert_job_form" class="alert-job-modal-popup">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo esc_html__('Want to subscribe project alerts?', 'exertio_theme'); ?></h4>
                        <button type="button" class="close btn-close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <?php echo __('Alert Name', 'exertio_theme'); ?><span class="color-red">*</span>
                                    </label>
                                    <input placeholder="<?php echo __('Enter alert name', 'exertio_theme'); ?>" class="form-control" type="text" required data-smk-msg="<?php echo esc_attr__('Please enter alert name','exertio_theme'); ?>" name="alert_name">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <?php echo __('Your Email', 'exertio_teme'); ?><span class="color-red">*</span>
                                    </label>
                                    <input placeholder="<?php echo __('Enter your email address', 'exertio_theme'); ?>" class="form-control" type="email" required data-smk-msg="<?php echo esc_attr__('Please enter your valid email','exertio_theme'); ?>" name="alert_email">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <?php echo __('Select email frequency', 'exertio_theme'); ?><span class="color-red">*</span>
                                    </label>
                                    <select class="select-generat" data-allow-clear="true" data-parsley-required="true" data-parsley-error-message="<?php echo __('Please select an option', 'exertio_theme'); ?>" name="alert_frequency">

                                        <option value="1"><?php echo __('Daily', 'exertio_theme'); ?></option>
                                        <option value="7"><?php echo __('Weekly', 'exertio_theme'); ?></option>
                                        <option value="15"><?php echo __('Fortnightly', 'exertio_theme'); ?></option>
                                        <option value="30"><?php echo __('Monthly', 'exertio_theme'); ?></option>
                                        <option value="12"><?php echo __('Yearly', 'exertio_theme'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <?php
                                $project_categories = exertio_get_terms('project-categories');
                                foreach($project_categories as $project_categorie)
                                {
                                    $project_cats[$project_categorie->term_id] = $project_categorie->name;
                                }
                            if (exertio_get_terms('project-categories', true)) {?>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group">
                                        <label>
                                            <?php echo __('Project Category', 'exertio_theme'); ?><span class="color-red">*</span>
                                        </label>
                                        <select class="select-generat" data-allow-clear="true" required data-smk-msg="<?php echo esc_attr__('Project Category required','exertio_theme'); ?>"  id="alert_sub_cat" >
                                            <option value=""><?php echo __('Select an option', 'exertio_theme'); ?></option>
                                            <?php echo exertio_add_taxonomies_on_job_alert('project-categories', ''); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="form-group" id="get_child_lev1">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div id="get_child_lev2" class="form-group">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div id="get_child_lev5" class="margin-top-10">
                                    </div>
                                </div>
                                <input type="hidden" name="alert_category" id="get_cat_val" value="" />
                            <?php } ?>
                            <input type="hidden" name="alert_start" value="<?php echo ''.($today); ?>" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" class="btn btn-theme btn-block" id="job_alerts">
                            <?php echo esc_html__('Submit', 'exertio_theme'); ?>
                        </button>
                    </div>
                </form>

            <?php } ?>
        </div>
    </div>
</div>
