<?php
	/**
		* Description	:	View file for Login Page of the application.
		* 
		*Created		:	25-03-2013.
		* 
		* Modification History:
		* Date				Modified By				Description
		* 19-08-2013		Abhinay B.Angadi        Added file headers, indentations.
		* 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
		*											Code cleaning.
		--------------------------------------------------------------------------------
	*/
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link href="<?php echo base_url('twitterbootstrap/img/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon" />
		<title> <?php echo $title.' | '; ?> IonCUDOS </title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<?php $this->load->view('includes/head'); ?>
		<!-- Le styles -->
		<link href="<?php echo base_url('twitterbootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('twitterbootstrap/css/bootstrap-responsive.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('twitterbootstrap/css/docs.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('twitterbootstrap/css/custom.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('twitterbootstrap/js/google-code-prettify/prettify.css');?>" rel="stylesheet">
	</head>
	
	<body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack();" onpageshow="if (event.persisted) noBack();">
		<input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
		<!-- Subhead -->
		<header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62);">		
			<div class="container-fluid">
				<img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 200px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 1px;">
				<center><b style="text-shadow: 2px 2px black; color: white; font-size: 15px; margin-top: 10px;"><?php echo $organisation_detail['0']['org_name'];?></b>
				<img src="<?php echo base_url('twitterbootstrap/img/your_logo.png'); ?>" class="img-circle" style="float:right;" /></center>
			</div>
		</header>
		
		<div class="container-fluid"><br>
			<div class="navbar">
				<div class="navbar-inner-custom">
					Request for Forgot Password
				</div>
			</div>	
			
			<div id="infoMessage"><?php echo $message;?></div>
			
			<?php 	$attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'form_id');
				echo form_open("login", $attributes);
			?>
			<div id="loading" class="ui-widget-overlay ui-front">
				<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
			</div>	
			<div class="control-group"><br>
				<label class="control-label" for="email"> Email Id: <font color='red'> * </font></label>
				<div class="controls">
					<?php echo form_input($email); ?>
					
				</div>
			</div><br>
			<div class="pull-left">
				<?php echo str_repeat('&nbsp;',84); ?>
				<button type="button" id="submit_button_id" class="btn btn-success"><i class="icon-user icon-white"></i> Submit </button>
				<button type="reset" class="btn btn-info"><i class="icon-refresh icon-white"></i><span></span> Reset </button>
				<a href="<?php echo base_url('login'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i><span></span> Cancel </a>
			</div>
			<br><br>
			<?php echo form_close();?>
			
			<!-- Modal to display Confirmation message before sending for Approval -->
			<div id="request_password_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="request_password_dialog_id" data-backdrop="static" data-keyboard="true"></br>
				<div class="container-fluid">
					<div class="navbar">
						<div class="navbar-inner-custom">
							Request for new Password Confirmation
						</div>
					</div>
				</div>
				<div class="modal-body">
					<p> Are you sure you want to proceed for a new password generation for your account? </p>
				</div>
				<div class="modal-footer">
					<button class="submit btn btn-primary" data-dismiss="modal" onclick="send_request_dialog();"><i class="icon-ok icon-white"></i> Ok </button> 
					<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
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
		<?php  $this->load->view('includes/js'); ?>
		<?php  $this->load->view('includes/footer'); ?> 	
	<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/login/forgot_password.js'); ?>"> </script>	