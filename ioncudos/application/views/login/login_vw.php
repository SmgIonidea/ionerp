<?php
/**
 * Description	:	View file for Login Page of the application.
 * Created		:	25-03-2013. 
 * Modification History:
 * 	Date				Modified By				Description
 * 19-08-2013		Abhinay B.Angadi        Added file headers, indentations.
 * 26-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
											Code cleaning.
 * 17-08-2016		Arihant Prasad			Indentation, code cleanup
--------------------------------------------------------------------------------*/
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
			<!--<div class="container">-->
			<div class="container-fluid">
				<img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 200px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 1px;">
				<center><b style="text-shadow: 2px 2px black; color: white; font-size: 15px; margin-top: 10px;"><?php echo $organisation_detail['0']['org_name'];?></b>
				<img src="<?php echo base_url('twitterbootstrap/img/your_logo.png'); ?>" class="img-circle" style="float:right;" /></center>
			</div>
		</header>
		
		<div class="container-fliud">
			<div class="row-fluid">
				<div class="span8" style="margin-left:50px; margin-bottom:78px; height:320px;">
					<img class="img-rounded" id="myImage" src="<?php echo base_url('twitterbootstrap/img/IonCUDOS-TagCloud.png');?>" height="1000" width="800" />
				</div>
				
				<div class="span3" style="height:320px; margin-top: 40px;"><br>
					<form method="POST" action="<?php echo base_url('login');?>" class="form-signin">
						<div class="navbar">
							<div class="navbar-inner-custom">
								Sign in			
							</div>
						</div>
						
						<?php if(!empty($message)) echo "<div class='alert alert-error'>$message</div>"; ?>
							<label>Username<?php echo form_input($identity);?></label>
							<label>Password<?php echo form_input($password);?></label>
							
							<button class="btn btn-primary" type="submit"><i class="icon-lock icon-white"></i> Sign in</button>
							
							<a class="pull-right" href = "<?php echo base_url('login/forgot_password'); ?>" style="text-decoration:underline; color:blue; font-size:12px;">Forgot Password</a>
							<br />
							<!-- This functionality to be Addressed in the upcoming release. 
								<label class="checkbox">
									<input type="checkbox" value="remember-me"> Remember me
								</label> -->
							<!--Contact Support link-->							
						<a href="" onclick="generate_modal();" class="pull-right" rel="tooltip" data-toggle="modal" style="text-decoration:underline; color:blue; font-size:12px;">Contact Support </a>
						<br>
					</form>
				</div><br>
				
				<div class="span11 media bs-docs-example" style="background:none;margin-left: 50px; margin-top: 10px; font-family: arial; font-size: 12px;">
					<?php if(!empty($organisation_detail['0']['org_desc'])) {
							echo $organisation_detail['0']['org_desc'];
						} else {
							echo '<a href="http://www.ioncudos.com" target="_blank"><label><h5>www.ioncudos.com</h5></label></a>';
						} ?>
				</div>
			</div>
		</div>
		
		<!--Contact Support Modal -->	
		<div id="myModal" class="modal fade" style="display:none;" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-header">
				<div class="navbar-inner-custom">
					Contact Support
				</div>
			</div>
			
			<form id="loginForm" method="post" class="form-horizontal" onsubmit="return false;">
				<div class="modal-body">
					<table class="table table-bordered">
						<tr>
							<td> Subject:</td>
							<td><input type="text" id="modal_subject" name="modal_subject" class="required"></td>
						</tr>
						
						<tr>
							<td>Your Mobile No</td><td><input type="text" id="modal_number" name="modal_number" class="required"></td>
						</tr>
						
						<tr>
							<td>Email Id</td><td><input type="email" id="modal_mail" name="modal_mail" class="required"></td>
						</tr>
						
						<tr>
							<td>Body:</td>
							<td> 
								<textarea rows="6" cols="80" maxlength="2000" style="width:90%;" placeholder="Enter your issues and details" id="modal_body" name="modal_body" class="required char-counter"></textarea>
								<br />
								<span id='char_span_support' class='margin-left5'>0 of 2000. </span>
							</td>
						</tr>
					</table>
				</div>
				
				<div class="modal-footer">
					<button class="btn btn-primary" id="modal_contact"><i class=  "icon-file icon-white"></i> Send</button> 
					<button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
				</div>
			</form>
		</div>
     	<?php  $this->load->view('includes/js'); ?>
		<?php  $this->load->view('includes/footer'); ?>
		<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/contact_support.js'); ?>"> </script>	
	</body>
</html>

<!-- End of file login_vw.php.php 
	Location: ./application/views/login/login_vw.php 
-->
<script>
	$('#myImage').resizeToParent({parent: '.parentContainer'});
</script>