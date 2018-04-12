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

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="twitterbootstrap/js/html5shiv.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
	</head>
	<body data-spy="scroll" data-target=".bs-docs-sidebar" onload="noBack();" onpageshow="if (event.persisted) noBack();">
	<input type="hidden" value="<?php echo base_url(); ?>" id="get_base_url" />
		<!-- Subhead
		================================================== -->
		<header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62);">
		<!--<div class="container">-->
		<div class="container-fluid">
			<img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 227px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 5px;">
			<p><?php echo str_repeat('&nbsp;',34); ?>
			<b style="text-shadow: 2px 2px black; color: white; font-size: 18px; margin-top: 10px;"> <?php $organisation_detail['0']['org_name'];?></b>
			<img src="<?php echo base_url('twitterbootstrap/img/your_logo.png'); ?>" class="img-circle" style="float:right;"/>
		</div>
		</header>
		<div id="loading" class="ui-widget-overlay ui-front">
							<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
		</div>
		<div class="container-fliud"><br>
			<div class="navbar">
				<div class="navbar-inner-custom">
					Reset Password
				</div>
			</div>		
<div id="infoMessage"><?php echo $message;?></div>

<?php 	$attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'update_form_id');
		echo form_open("login/update_reset_password", $attributes);
?>
	<table>
		<tbody>
		<tr>
			<td>
				<label class="control-label" for="first_name"> First Name : </label>
			</td>
			<td>
				<b><?php echo $first_name; ?></b>	
			<td>
		</tr>
		<tr>
			<td>
				<label class="control-label" for="last_name"> Last Name : </label>
			</td>
			<td>
				<b><?php echo $last_name; ?></b>	
			<td>
		</tr>
		<tr>
			<td>
				<label class="control-label" for="email"> Email Id : </label>
			</td>
			<td>
				<b><?php echo $email; ?></b>	
			<td>
		</tr>
		<tr>
			<td>
				<label class="control-label" for="password">Reset Password : <font color='red'> * </font></label>
			</td>
			<td>
				<?php echo form_input($password); ?>
			</td>
		</tr>
		<tr>
			<td><br>
				<label class="control-label" for="confirm_password">Confirm Password : <font color='red'> * </font></label>
			</td>
			<td><br>
				<?php echo form_input($confirm_password); ?>
			</td>
		</tr>
		</tbody>
	</table>
	<?php echo form_input($user_id); ?>
	<?php echo form_input($email_id); ?>
	<br><br>
	<div class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
		<button type="button" id="submit_button_id" class="btn btn-primary"><i class="icon-ok icon-white"></i> Save </button>
		<button type="reset" class="btn btn-info"><i class="icon-refresh icon-white"></i><span></span> Reset </button>
		<a href="<?php echo base_url('login'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i><span></span> Cancel </a>
	</div>
<?php echo form_close();?>

<!-- Modal to display Confirmation message before sending for Approval -->
		<div id="reset_password_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="reset_password_dialog_id" data-backdrop="static" data-keyboard="true"></br>
			<div class="container-fluid">
				<div class="navbar">
					<div class="navbar-inner-custom">
						Reset Password Confirmation
					</div>
				</div>
			</div>
			<div class="modal-body">
				<p> Are you sure you want to proceed to a Reset Password for your account? </p>
			</div>
			<div class="modal-footer">
				<button class=" btn btn-primary" id="submit" data-dismiss="modal" onclick="send_request_dialog();"><i class="icon-ok icon-white"></i> Ok </button> 
				<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button> 
			</div>
		</div>
						
	<!-- Modal to display the Request Confirmation message -->
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
	</div>
	<!-- Modal to display the Error in Confirm Password message -->
		<div id="mismatch_password_dialog_id" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="mismatch_password_dialog_id" data-backdrop="static" data-keyboard="true"></br>
			<div class="container-fluid">
				<div class="navbar">
					<div class="navbar-inner-custom">
						Error
					</div>
				</div>
			</div>
			<div class="modal-body">
				<p> Passwords do not match. Retype the new password.</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" type ="reset" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
			</div>
		</div>
	</div>
	<?php  $this->load->view('includes/js'); ?>
	<?php  $this->load->view('includes/footer'); ?> 
	<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/login/reset_forgot_password.js'); ?>"> </script>