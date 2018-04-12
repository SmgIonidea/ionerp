<?php
/**
* Description	:	Organization allows the admin to add or edit the content of the
login page. Organization makes use of tinymce for editing the content
of the login page.
* 					
* Created		:28-04-2016
*
* Author		:	Mritunjay B S				
* Description	:   Home page -> menus will be in grid view
* Date				Modified By				Description
 * 02-06-2017 		Jyoti 				Dashboard for student login
  ---------------------------------------------------------------------------------------------------------------------------------
 */
	
$val =  $this->ion_auth->user()->row();
$org_name = $val->org_name->org_name;
$org_type = $val->org_name->org_type;
$oe_pi_flag = $val->org_name->oe_pi_flag;
$theory_iso_code = $val->org_name->theory_iso_code; 
$vall = $this->input->get();
	if(!isset($set)){
		if(isset($vall)){
			$set = $vall['set']; $attempts = $vall['attempt'];
		}
	}
if(!empty($val->last_login_time)){
$dat_log_in=date_create($val->last_login_time);
$login_date_formated =  date_format($dat_log_in,"dS - F - Y h:i:sA");
}else{
$login_date_formated  = "";
}

if(!empty($val->last_login_time)){
$date_log_out=date_create($val->logout_time);
$logout_date_formated =  date_format($date_log_out,"dS - F - Y h:i:sA");
}else{
$logout_date_formated ="";
}

if(!empty($val->login_time)){
$date_current=date_create($val->logout_time);
$date_current_formatted =  date_format($date_current,"dS - F - Y h:i:sA");
}else{
$date_current_formatted ="";
}
$date_log_failed=date_create($val->login_failed);
$logfailed_date_formated =  date_format($date_log_failed,"dS - F - Y h:i:sA");
?>



<!--head here -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />

    
<!-- Navbar here -->
 <?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<?php $this->load->view('includes/navbar'); ?>

<div class="container-fluid">
	<div class="row-fluid">
		<!--sidenav.php-->
		<?php //$this->load->view('includes/sidenav_1'); ?>
		<div class="span12">
			<!-- Contents -->
			<section id="contents">
				<div class="bs-docs-example new_fixed-height">
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

<?php if(isset($attempts)){ ?>
<input type="hidden" id="login_failed_count" name="login_failed_count" value="<?php echo $attempts; ?>"/><?php  } ?>
					<!-- Login Landing Page Icons Starts From here -->
<div class="row">
    <div class="col-lg-8 col-lg-offset-2 col-lg-offset-2 span12">
        <div class="shortcut-bar" style="padding-bottom: 20px;">
                <ul class="shortcut-items">
                    <li>
                        <a href="<?php echo base_url('dashboard/dashboard_student'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;"> 
                            <span><span class="badge badge-notify  my_action_count"  id="my_action_count1" title="My Actions">0</span>
                            <img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/dashboard_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive" style="margin-left:28px;margin-bottom:20px;" >
                            </span>
                            <span class="shortcut-label">Dashboard</span>
                        </a>
                    </li>
                    <li>
                    <a href="<?php echo base_url('report/course_delivery_report');?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/report_v1.png');?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                    <span class="shortcut-label">Reports</span>
                    </a>
                </li>			   
                <li>
                    <a href="<?php echo base_url('login/student_user_edit/'); ?>" class="btn btn-primary" style="height: 150px;width: 150px; background-color:#ccc;">
                    <span><img src="<?php echo base_url('twitterbootstrap/img/landing_page_icons/profile_v1.png'); ?>" width="90" height="80" class="img-circle img-responsive icon_glip" ></span>
                    <span class="shortcut-label">View Profile</span>
                    </a>
                </li>
			</ul>
         </div>
	</div>
</div>


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
<?php 
if(isset($set)){ ?>
<div> <input type="hidden" id="login_dat" name="login_dat" value="<?php echo $set; ?>"/> </div>
<?php }?>
<script src="<?php //echo base_url('twitterbootstrap/js/custom/configuration/organization.js'); ?>" type="text/javascript"> </script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty_log_history.js'); ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/login/login_date_time.js'); ?>"> </script>
<!-- End of file edit_organisation_vw.php 
Location: .configuration/organisation/edit_organisation_vw.php -->

<script>
var base_url = $('#get_base_url').val();
$(document).ready(function(){
$.ajax({type: "POST",
        url: base_url + 'login/get_myaction_count',
        //data: post_data,
        //dataType: 'json',
        success: function(data){
		console.log(data);
            if($.trim(parseInt(data)) != 0 ){
				$('.my_action_count').each(function(){
				$(this).empty();
				$(this).html(data);
				});
            }else{
				$('.my_action_count').each(function(){
				$(this).empty();
				$(this).html('0');
				});
			}
        }
    });
});
</script>
