<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
	<div class="row-fluid">
		<?php $this->load->view('includes/sidenav_5'); ?> 
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
			<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
		<div class="span10">
			<!-- Contents -->
			<div id="loading" class="ui-widget-overlay ui-front">
					<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
				</div>
			<section id="contents" >
				<div class="bs-docs-example">
					<input type="hidden" name="get_base_url" id="get_base_url" value="<?php echo base_url(); ?>"/>
					<!--content goes here-->	
					<div class="navbar">
						<div class="navbar-inner-custom">
							Upload Student Marks
						</div>
					</div>
					<input type="hidden" name="files_count" id="files_count" value=<?php echo $files_count; ?> />
				<!--	<div class="row-fluid">
						<div class="span2">
							<a href="<?php echo base_url('scheduler/student_marks_upload/view'); ?>"class="btn btn-primary">View Status</a>
						</div>
						<div class="span2">
							<?php if($files_count>0){?>
								<a href="<?php echo base_url('scheduler/student_marks_upload/dir'); ?>"class="btn btn-primary" target="_blank">Process Pending Files</a>
							<?php } ?>
						</div>
						<div class="span4"></div>
					</div>-->
					
					<div class="row-fluid">
						<div class="span12">
							<div class="span6 well">
							<div id="status"></div>
								<!--upload form-->
								<form enctype="multipart/form-data" class="form-vertical" method="post" name="upload_form" id="upload_form">
									<input type="file" name="upload_files[]" id="upload_files" class="input-large form-control required" multiple="multiple"/>
									<br/>
									<div class="pull-right" style="margin-right:25%;">
										<button type="button" name="upload_btn" id="upload_btn" class="btn btn-primary"><i class="icon icon-file icon-white"></i>Upload</button>
										<button type="reset" name="reset" id="reset" class="btn btn-info"><i class="icon icon-refresh icon-white"></i> Reset</button>
									</div>
								</form><br/>
								<!--end of form-->
								<div class="well">
									File name should be in the format given below:
									<br><b>Example: 2014_2015_CSE_UG_1_CSC319.csv</b>
									<br><small>Description: StartYear_EndYear_DepartmentAcronym_ProgramType_Sem_Subcode</small>
								</div>
							</div>
							<div class="span6">
								<div id="uploaded_files_list"> </div>
							</div>
						</div>
					</div>
				<!--	<div class="row-fluid">
					<div class="well span6">
						File name should be in the format given below:
						<br><b>Example: 2014_2015_CSE_UG_1_CSC319.csv</b>
						<br><small>Description: StartYear_EndYear_DepartmentAcronym_ProgramType_Sem_Subcode</small>
					</div>
					</div>-->
					<br/><br/>
					<div id ="uploaded_data"></div>
				</section>
			</div>
		</div>
	</div>
	<!---place footer.php here -->

	<?php $this->load->view('includes/footer'); ?>
	<?php $this->load->view('includes/js_v3'); ?>
	<script>
 var post_data  = {'a' : 1}
	    $.ajax({
        type: "POST",
        url: base_url + 'scheduler/student_marks_upload/view',
        data: post_data,
        success: function (data) {
			$('#uploaded_data').html(data);
        }
    }); 

</script>
	<script src="<?php echo base_url(); ?>twitterbootstrap/js/custom/student_marks_upload.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

