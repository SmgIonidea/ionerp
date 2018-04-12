<?php
/**
* Description		:	Edit View for lab category Module.
* Created		:	07-11-2015. 
* Author		:	Shayista Mulla
* Modification History:
* Date				Modified By				Description
--------------------------------------------------------------------------------
*/
?>
<!--head here -->
<?php 	$this->load->view('includes/head'); ?>
<!--branding here-->
<?php  $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php  $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
	<div class="row-fluid">
		<!--sidenav.php-->
		<?php  $this->load->view('includes/sidenav_1'); ?>
		<div class="span10">
			<!-- Contents-->
		<section id="contents">
		<div class="bs-docs-example fixed-height">
		<!--content goes here-->	
		<div class="navbar">
			<div class="navbar-inner-custom">
				Edit Lab Category
			</div>
			<div>
				<font color= "red" ><?php echo validation_errors(); ?></font>
			</div>
		</div><br><br><br>
		<form  class="form-horizontal" method="POST" id="edit_form_id" name="edit_form_id" action="<?php echo base_url('configuration/lab_category/lab_category_update_record');?>">
			<div class="control-group">
				<p class="control-label" for="pgmtype_name">Lab Category Name :<font color="red">*</font></p>
				<div class="controls">
					<?php echo form_input($lab_category_name); ?>
				</div>
			</div>
			<div class="control-group">
				<p class="control-label" for="pgm_description">Description :</p>
				<div class="controls"> 
					<?php echo form_textarea($lab_category_desc); ?>
					<br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
				</div>
			</div>
			<div class="control-group">
				<div class="controls"> 
					<?php echo form_input($lab_category_id); ?>
				</div>

				<!--Modal for uniqueness warning-->
				<div id="uniqueness_dialog_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="uniqueness_dialogLabel" aria-hidden="true" style="display: none;" data-controls-modal="uniqueness_dialog_edit" data-backdrop="static" data-keyboard="true">
					</br>
					<div class="container-fluid">
						<div class="navbar">
		    					<div class="navbar-inner-custom">
								Warning 
		    					</div>
						</div>
					</div>
					<div class="modal-body">
						<p>Lab Category Name already exists.</p>
						<br>
					</div>						
					<div class="modal-footer">
						<button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
					</div>
				</div>
			</div><br><br><br><br><br>
			<div class="pull-right">
				<button class="edit_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Update</button>
		</form>
		<a class="btn btn-danger" href="<?php echo base_url('configuration/lab_category');?>"><i class="icon-remove icon-white"></i> Cancel</a>
		<br><br><br>
		<div id="loading">
			<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>"></img> 
		</div>
		<!--Do not place contents below this line-->	
		</div>
		</section>
		</div>
	</div>
</div>
<!---place footer.php here -->
<?php  $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php  $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/lab_category.js'); ?>" type="text/javascript"></script>
