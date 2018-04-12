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
	<body data-spy="scroll" data-target=".bs-docs-sidebar">
		<!-- Subhead -->
		<header class="jumbotron subhead" id="overview" style="background: rgb(35, 47, 62);">
			<div class="container-fluid">
				<img src="<?php echo base_url('twitterbootstrap/img/IonCUDOS_V5.png'); ?>" style="width: 200px; -webkit-border-radius: 20px; float:left; background-color: white; margin-top: 1px;">
				<center><b style="text-shadow: 2px 2px black; color: white; font-size: 15px; margin-top: 10px;"><?php echo $organisation_detail['0']['org_name'];?></b>
				<img src="<?php echo base_url('twitterbootstrap/img/your_logo.png'); ?>" class="img-circle" style="float:right;" /></center>
			</div>
		</header>
		
		<div class="container-fliud">
			<!-- Example row of columns -->
			<div class="row-fluid">
				<div class="span8" style="margin-left:50px; margin-bottom:78px; height:320px;">
					<a class="pull-left">
						<img class="img-rounded" src="<?php echo base_url('twitterbootstrap/img/IonCUDOS-TagCloud.png');?>" height="1000" width="800">
					</a>
					
				</div>
				<div class="span3" style="height:320px; margin-top: 40px;"><br>
					<form method="POST" action="<?php echo base_url('login/login');?>" class="form-signin">
						<div class="navbar">
							<div class="navbar-inner-custom">
								Sign in
								
							</div>
						</div>
						<?php if(!empty($message)) echo "<div class='alert alert-error'>$message</div>"; ?>
						<label>Username<?php echo form_input($identity);?></label>
						<label>Password<?php echo form_input($password);?></label>
						<!-- This functionality to be Addressed in the upcoming release.
							<label class="checkbox">
							<input type="checkbox" value="remember-me"> Remember me
							</label>-->
						<button class="btn btn-primary" type="submit" disabled="disabled"><i class="icon-lock icon-white"></i> Sign in</button>
						
						<a class="pull-right" href = "#" style="text-decoration:underline; color:blue; font-size:12px;">Forgot Password</a>
						<br />
						
						<!--Contact Support link-->							
					<a href="#"  class="pull-right" rel="tooltip" data-toggle="modal" style="text-decoration:underline; color:blue; font-size:12px;">Contact Support </a></center>
					
					<br>
				</form>
			</div><br>
			<div class="span11 media bs-docs-example" style="background:none;margin-left: 50px; margin-top: 10px; font-family: Arial; font-size: 12px;">
				<?php
					if(!empty($organisation_detail['0']['org_desc'])) {
						echo $organisation_detail['0']['org_desc'];
						} else {
						echo '<a href="http://www.ioncudos.com" target="_blank"><label><h5>www.ioncudos.com</h5></label></a>';
					}
				?>
			</div>
		</div>
	</div>
	<?php  $this->load->view('includes/js'); ?>
	<?php  $this->load->view('includes/footer'); ?> 	
</body>
</html>

<!-- End of file login_vw.php.php 
	Location: ./application/views/login/login_vw.php 
	-->	