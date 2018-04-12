<?php
/**
 * Description	:	View file for Login Page of the application.
 * Created by	:	Mritunjay
 * Created on	:	04-09-2015
 * Modification History:
 *   Date				Modified By				Description
 * 25-09-2015		   Arihant Prasad		Addition of new terms and condition view page
  -----------------------------------------------------------------------------------------
 */
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
        <title> <?php echo $title . ' | '; ?> IonCUDOS </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <?php $this->load->view('includes/head'); ?>
        <!-- Le styles -->
        <link href="<?php echo base_url('twitterbootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/css/bootstrap-responsive.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/css/docs.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('twitterbootstrap/css/custom.css'); ?>" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/animate.min.css'); ?>" media="screen" />
        <link href="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.css'); ?>" rel="stylesheet">

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="twitterbootstrap/js/html5shiv.js"></script>
        <![endif]-->

        <!-- Le fav and touch icons -->
    </head>
    <body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack();" onpageshow="if (event.persisted) noBack();">
        <input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
        <!-- Subhead -->
        <header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62);">
            <!--<div class="container">-->
            <div class="container-fluid">
                <img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 227px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 5px;" />
                <p><?php echo str_repeat('&nbsp;', 34); ?></p>
            </div>
        </header>

        <div class="container-fliud fixed-height"><br>
            <!-- Modal to display Confirmation message before sending for Approval -->
            <div id="initial_login_change_psw" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="request_password_dialog_id" data-backdrop="static" data-keyboard="true"><br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Change Password
                        </div>
                    </div>
                </div>
                <div class="modal-body">
					<div class="row-fluid ">
						<!--enter old password-->
						<div class="span8">
							<div class="control-group">
								<div class="controls">
									<label class="control-label" style="margin-left: 5px;"> Enter Old Password: <font color='red'> * </font>
										<input type="password" name="old_password" id="old_password" />
									</label>
								   <span id="error_msg_old" class="error_msg_old"> </span>
								</div>
							</div>
						</div>
					
						<!--enter new password-->
						<div class="span8">
							<div class="control-group">
								<div class="controls">
									<label class="control-label"> Enter New Password: <font color='red'> * </font>
										<input type="password" name="new_password" id="new_password" />
									</label>
								   <span id="error_msg_one" class="error_msg_one"> </span>
								</div>
							</div>
						</div>
						
						<!--Re-enter new password-->
						<div class="span8">
							<div class="control-group">
								<div class="controls">
									<label class="control-label"> Re - Enter Password: <font color='red'> * </font>
									<input type="password" name="re_password" id="re_password" /></label>
									<span id="error_msg" class="error_msg"> </span>
								</div>
							</div>
						</div>
						
						<div class="span8" style="display:none;">
							<div class="control-group">
								<div class="controls">
									<input type="text" name="user_id" id="user_id" value="<?php echo $user_id;  ?>" />
								</div>
							</div>
						</div>
						
						<div class="span8" >
							<div class="control-group">
								<div class="controls">
									<input type="checkbox" name="terms_conditions" id="terms_conditions" value="1" style="margin-top: 0px;" />&nbsp; I agree to the IonCUDOS <a target="_blank" href="<?php echo base_url('login/terms_condition'); ?>">Terms & Conditions</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<div class="pull-right">
						<button type="button" id="submit_button_id" class="btn btn-success" disabled="disabled"><i class="icon-user icon-white"></i> Log-in </button>
						
						<a href="<?php echo base_url('logout'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i>Cancel </a>
					</div>
				</div>
			</div>
                
			<!-- Modal to display the Approval status message -->
			<div id="sent_email_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="sent_email_dialog_id" data-backdrop="static" data-keyboard="true"></br>
				<div class="container-fluid">
					<div class="navbar">
						<div class="navbar-inner-custom">
							Request Confirmation
						</div>
					</div>
				</div>
				<div class="modal-body">
					<p> Request has been sent successfully. An email is sent to your Email Id</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" data-dismiss="modal" onclick="submit_success();"><i class="icon-ok icon-white"></i> Ok </button>
				</div>
			</div>
			
			<!-- Modal to display the Error message for un registered email_id -->
			<div id="error_in_email_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="error_in_email_id" data-backdrop="static" data-keyboard="true"></br>
				<div class="container-fluid">
					<div class="navbar">
						<div class="navbar-inner-custom">
							Request Failed
						</div>
					</div>
				</div>
				<div class="modal-body">
					<p> The Email Id provided is either INCORRECT or NOT REGISTERED with this Application. Kindly verify & provide valid Email Id.</p>
				</div>
				<div class="modal-footer">
					<button type="reset" id="close" class=" btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
				</div>
			</div>
		</div>
		
		<?php $this->load->view('includes/js'); ?>
		<?php $this->load->view('includes/footer'); ?>
		
		<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/login/initial_login_psw_change.js'); ?>"></script>
		<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.complexify.js'); ?>"></script>
		<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>