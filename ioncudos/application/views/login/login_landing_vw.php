<?php
/**
 * Description	:	Organization allows the admin to add or edit the content of the
  login page. Organization makes use of tinymce for editing the content
  of the login page.
 * 					
 * Created	:       28-04-2016
 *
 * Author	:	Mritunjay B S				
 * Description	:   Home page -> menus will be in grid view
 *   Date 		Modified By                             Description	  
 * 21-10-2016           Neha Kulkarni                     Added the condition for BoS login, to display Dashboard and View Profile.
  ------------------------------------------------------------------------------------------------- */

$val = $this->ion_auth->user()->row();
$org_name = $val->org_name->org_name;
$org_type = $val->org_name->org_type;
$oe_pi_flag = $val->org_name->oe_pi_flag;
$theory_iso_code = $val->org_name->theory_iso_code;
$vall = $this->input->get();
if (!isset($set)) {
    if (isset($vall)) {
        $set = $vall['set'];
        $attempts = $vall['attempt'];
    }
}
if (!empty($val->last_login_time)) {
    $dat_log_in = date_create($val->last_login_time);
    $login_date_formated = date_format($dat_log_in, "dS - F - Y h:i:sA");
} else {
    $login_date_formated = "";
}

if (!empty($val->last_login_time)) {
    $date_log_out = date_create($val->logout_time);
    $logout_date_formated = date_format($date_log_out, "dS - F - Y h:i:sA");
} else {
    $logout_date_formated = "";
}

if (!empty($val->login_time)) {
    $date_current = date_create($val->logout_time);
    $date_current_formatted = date_format($date_current, "dS - F - Y h:i:sA");
} else {
    $date_current_formatted = "";
}
$date_log_failed = date_create($val->login_failed);
$logfailed_date_formated = date_format($date_log_failed, "dS - F - Y h:i:sA");
?>



<!--head here -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<?php $userdata = $this->session->userdata; ?>

<!-- Navbar here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<?php $userdata = $this->session->userdata; ?>
<?php $this->load->view('includes/navbar'); ?>

<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php //$this->load->view('includes/sidenav_1'); ?>
        <div class="span12">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom" style="height:20px;">
                            IonCUDOS - Home Page
                        </div>
                    </div>
                    <input type="hidden" id="login_date" name="login_date" value="<?php echo $login_date_formated ?>"/>
                    <input type="hidden" id="logout_date" name="logout_date" value="<?php echo $logout_date_formated; ?>"/>
                    <input type="hidden" id="login_failed_date" name="login_failed_date" value="<?php echo $logfailed_date_formated; ?>"/>
                    <input type="hidden" id="date_current_formatted" name="date_current_formatted" value="<?php echo $date_current_formatted; ?>"/>
                    <input type="hidden" id="prevent_log_history" name="prevent_log_history" value="<?php echo $val->prevent_log_history; ?>"/>

                    <?php if (isset($attempts)) { ?>
                        <input type="hidden" id="login_failed_count" name="login_failed_count" value="<?php echo $attempts; ?>"/><?php } ?>
                    <!-- Login Landing Page Icons Starts From here -->
                    <?php if ($this->ion_auth->is_admin()) { ?>
                        <div class="row">
                            <div class="col-lg-8 col-md-10 col-sm-12 col-lg-offset-2 col-lg-offset-2 span12">
                                <div class="shortcut-bar" style="padding:0px;">
                                    <ul class="shortcut-items" >
                                        <li>

                                            <a href="<?php echo base_url('dashboard/dashboard'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;"> 
                                                <span>
                                                    <span class="badge badge-notify  my_action_count"  id="my_action_count1" title="My Actions">0</span>
                                                    <img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/dashboard_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive" style="margin-left:28px;margin-bottom:20px;" >
                                                </span>
                                                <span class="shortcut-label">Dashboard</span>

                                            </a>

                                        </li>

                                        <li>
                                            <a href="<?php echo base_url('configuration/organisation'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/config_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Configuration</span>
                                            </a>
                                        </li>

                                        <li>

                                            <a href="<?php echo base_url('curriculum/curriculum'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/design_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Curriculum Design</span>
                                            </a>
                                        </li> 
                                        <li>
                                            <a href="<?php echo base_url('assessment_attainment/cia') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/assessment_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Assessment Planning</span>
                                            </a>
                                        </li>
                                        <!--                                                                                <li>
                                                                                    href="http://localhost:4200/login?uid=1&username=admin@ionidea.com&password=password"
                                                                                    encoded url 
                                            href="http://localhost:4200/login?'<?php // $url = "uid=1&username=admin@ionidea.com&password=password";  $encodedUrl = urlencode($url); echo $encodedUrl;  ?>'"
                                                           
                                                           
                                                                                    <a href="http://localhost:4200/login?id=1&username=<?php echo base64_encode('admin@ionidea.com'); ?>&password=<?php echo base64_encode('password'); ?>" target="_blank" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                                                        <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/report_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                                                        <span class="shortcut-label">Ion Delivery</span>
                                                                                    </a>
                                                                                </li>-->

                                        <li> 
                                            <input type="hidden" value="<?php echo $this->ion_auth->user()->row()->email; ?>" name="userId" id="userId" />
                                            <input type="hidden" value="<?php echo $userdata['user_password']; ?>" name="userPsw" id="userPsw" />
                                            <a  href="http://localhost:4200/login?<?php echo base64_encode('username'); ?>=<?php echo base64_encode($this->ion_auth->user()->row()->email); ?>
                                                &<?php echo base64_encode('password'); ?>=<?php echo base64_encode($userdata['user_password']); ?>"
                                                id="iondelivery_button" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/assessment_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">IonDelivery</span>
                                            </a>

                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2 col-lg-offset-2 span12">
                                <div class="shortcut-bar" style="padding-bottom: 20px;">
                                    <ul class="shortcut-items">
                                        <li>
                                            <a href="<?php echo base_url('report/po_peo_map_report'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/report_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Reports</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo base_url('survey/questions'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/survey_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Survey</span>
                                            </a>
                                        </li>  
                                        <li>
                                            <a href="<?php echo base_url('assessment_attainment/import_cia_data'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/attainment_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Attainment Analysis</span>
                                            </a>
                                        </li>  
                                        <!--<li>
                                            <a href="#" class="btn btn-primary" style="height: 176px;width: 178px;">
                                            <span><img src="<?php //echo base_url('twitterbootstrap/img/landing_page_icons/switch.png');             ?>" width="100" height="70" class="img-circle img-responsive icon_glip" ></span>
                                            <span class="shortcut-label">Switch Department</span>
                        
                                            </a>
                                        </li> -->
                                        <!--    <li>
                                               <a href="<?php // echo base_url('login/user_edit_profile/' . $this->ion_auth->user()->row()->id);            ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                   <span><img src="<?php // echo base_url('twitterbootstrap/img/landing_page_icons/profile_v1.png');  ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                   <span class="shortcut-label">View Profile</span>
                                               </a>
                                           </li>  -->
                                        <li>
                                            <a href="<?php echo base_url('nba_sar/curriculum_student_info'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/nba5.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">NBA-SAR</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-lg-8 col-lg-offset-2 col-lg-offset-2 span12">
                                <div class="shortcut-bar" style="padding-bottom: 20px;">
                                    <ul class="shortcut-items">
                                        <li>
                                            <a href="<?php // echo base_url('nba_sar/curriculum_student_info');  ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php // echo base_url('twitterbootstrap/img/landing_page_icons/report_v1.png');  ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">NBA</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>  -->
                    <?php } else if ($this->ion_auth->in_group('BOS') && !$this->ion_auth->in_group('Program Owner') && !$this->ion_auth->in_group('Course Owner')) { ?>
                        <div class="row">
                            <div class="col-lg-8 col-md-10 col-sm-12 col-lg-offset-2 col-lg-offset-2 span12" style = "height: 200px;">
                                <div class="shortcut-bar" style="padding:0px;">
                                    <ul class="shortcut-items">
                                        <li>
                                            <a href="<?php echo base_url('dashboard/dashboard'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;"> 
                                                <span>
                                                    <span class="badge badge-notify my_action_count" id="my_action_count2" title="My Actions">0</span>
                                                    <img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/dashboard_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive" style="margin-left:28px; margin-bottom:20px;" >
                                                </span>
                                                </span> 
                                                <span class="shortcut-label">Dashboard</span>
                                            </a>

                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('login/user_edit_profile/' . $this->ion_auth->user()->row()->id); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/profile_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">View Profile</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    <?php } else { ?>
                        <div class="row">
                            <div class="col-lg-8 col-md-10 col-sm-12 col-lg-offset-2 col-lg-offset-2 span12">
                                <div class="shortcut-bar" style="padding:0px;">
                                    <ul class="shortcut-items">
                                        <li>
                                            <a href="<?php echo base_url('dashboard/dashboard'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;"> 
                                                <span>
                                                    <span class="badge badge-notify my_action_count" id="my_action_count2" title="My Actions">0</span>
                                                    <img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/dashboard_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive" style="margin-left:28px; margin-bottom:20px;" >
                                                </span>
                                                </span> 
                                                <span class="shortcut-label">Dashboard</span>
                                            </a>

                                        </li>


                                        <?php if ($this->ion_auth->in_group('Chairman')) { ?>
                                            <li>
                                                <a href="<?php echo base_url('curriculum/curriculum'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/design_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">Curriculum Design</span>
                                                </a>
                                            </li> 
                                        <?php } else if (!$this->ion_auth->in_group('Chairman') && $this->ion_auth->in_group('Program Owner')) { ?>
                                            <li>
                                                <?php if ($oe_pi_flag == 1) { ?>
                                                    <a href="<?php echo base_url('curriculum/pi_and_measures'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                        <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/design_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                        <span class="shortcut-label">Curriculum Design</span>
                                                    </a>

                                                <?php } else { ?>
                                                    <a href="<?php echo base_url('curriculum/course'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                        <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/design_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                        <span class="shortcut-label">Curriculum Design</span>
                                                    </a>
                                                <?php } ?>
                                            </li> 
                                        <?php } else { ?>

                                            <li>
                                                <a href="<?php echo base_url('curriculum/clo'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/design_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">Curriculum Design</span>
                                                </a>
                                            </li> 
                                        <?php } ?>

                                        <li>
                                            <a href="<?php echo base_url('assessment_attainment/cia') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/assessment_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Assessment Planning</span>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo base_url('report/po_peo_map_report') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/report_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Reports</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-lg-offset-2 col-lg-offset-2 span12">
                                <div class="shortcut-bar" style="padding-bottom:20px;">
                                    <ul class="shortcut-items">

                                        <?php if ($this->ion_auth->in_group('Chairman')) { ?>
                                            <li>
                                                <a href="<?php echo base_url('survey/import_student_data/student_stakeholder_list') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/survey_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">Survey</span>
                                                </a>
                                            </li>  
                                        <?php } elseif (!$this->ion_auth->in_group('Chairman') && $this->ion_auth->in_group('Program Owner')) { ?>
                                            <li>
                                                <a href="<?php echo base_url('survey/import_student_data/student_stakeholder_list') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/survey_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">Survey</span>
                                                </a>
                                            </li>
                                        <?php } else { ?>
                                            <li>
                                                <a href="<?php echo base_url('survey/templates') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/survey_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">Survey</span>
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <li>
                                            <a href="<?php echo base_url('assessment_attainment/import_cia_data'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/attainment_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Attainment Analysis</span>
                                            </a>
                                        </li>  
                                        <!--<li>
                                            <a href="#" class="btn btn-primary" style="height: 176px;width: 178px;">
                                            <span><img src="<?php //echo base_url('twitterbootstrap/img/landing_page_icons/switch.png');             ?>" width="100" height="70" class="img-circle img-responsive icon_glip" ></span>
                                            <span class="shortcut-label">Switch Department</span>
                        
                                            </a>
                                        </li>-->
                                        <?php if ($this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner')) { ?>
                                            <!-- <li>
                                                     <a href="<?php // echo base_url('nba_sar/curriculum_student_info');  ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                             <span><img src="<?php // echo base_url('twitterbootstrap/img/landing_page_icons/nba5.png');  ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                             <span class="shortcut-label">NBA-SAR</span>
                                                     </a>
                                             </li> -->

                                            <li>
                                                <a href="<?php echo base_url('login/user_edit_profile/' . $this->ion_auth->user()->row()->id); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/profile_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">View Profile</span>
                                                </a>
                                            </li>
                                        <?php } else { ?>
                                            <li>
                                                <a href="<?php echo base_url('login/user_edit_profile/' . $this->ion_auth->user()->row()->id); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/profile_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                    <span class="shortcut-label">View Profile</span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?php echo base_url('help') ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                                                <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/help_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                                                <span class="shortcut-label">Help</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    <div class="clearfix"></div>

                    <div id="error_message" style="color:red">
                    </div>
                    <!--Do not place contents below this line-->
                </div>
            </section>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<?php if (isset($set)) { ?>
    <div> <input type="hidden" id="login_dat" name="login_dat" value="<?php echo $set; ?>"/> </div>
    <?php } ?>
<script src="<?php //echo base_url('twitterbootstrap/js/custom/configuration/organization.js');            ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty_log_history.js'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/login/login_date_time.js'); ?>"></script>
<!-- End of file edit_organisation_vw.php 
Location: .configuration/organisation/edit_organisation_vw.php -->

<script>
    var base_url = $('#get_base_url').val();
    $(document).ready(function () {
        $.ajax({type: "POST",
            url: base_url + 'login/get_myaction_count',
            //data: post_data,
            //dataType: 'json',
            success: function (data) {
                console.log(data);
                if ($.trim(parseInt(data)) != 0) {
                    $('.my_action_count').each(function () {
                        $(this).empty();
                        $(this).html(data);
                    });
                } else {
                    $('.my_action_count').each(function () {
                        $(this).empty();
                        $(this).html('0');
                    });
                }
            }
        });
    });
</script>
