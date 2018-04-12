<?php
/**
 * Description	:	Add View for Graduate Attributes Module.
 * Created		:	23-03-2015. 
 * Modification History:
 * Date				Author			Description
 * 23-03-2015		Jevi V. G.        Added file headers, function headers, indentations & comments.

  -------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<?php
$this->load->view('includes/branding');
?>
<!-- Navbar here -->
<?php
$this->load->view('includes/navbar');
?> 
<div class="container-fluid">
    <div class="row-fluid">
	<!--sidenav.php-->
	<?php $this->load->view('includes/sidenav_1'); ?>
	<div class="span10">
	    <!-- Contents
================================================== -->
	    <section id="contents">
		<div class="bs-docs-example fixed-height">
		    <!--content goes here-->	
		    <div class="navbar">
			<div class="navbar-inner-custom">
			    Add Graduate Attribute (GA)
			</div>
		    </div>	
		    <br>
		    <form  class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "<?php echo base_url('configuration/graduate_attributes/ga_insert_record'); ?>">
			<div class="control-group">
			    <p class="control-label" for="ga_reference"> Sl No.<font color="red">*</font></p>
			    <div class="controls">
				<?php echo form_input($ga_reference); ?>
			    </div>
			</div>
			<div class="control-group">
			    <p class="control-label" for="ga_reference"> Graduate Attribute <font color="red">*</font></p>
			    <div class="controls">
				<?php echo form_textarea($ga_statement); ?>
			    </div>
			</div>
			<div class="control-group">
			    <p class="control-label" for="ga_description">GA Statement:</p>
			    <div class="controls">
				<?php echo form_textarea($ga_description); ?>
				<br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
			    </div>
			</div>


			<br><br><br><br><br><br><br>
			<div class="pull-right">       
			    <button class="add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span> Save</button>
			    <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i><span></span> Reset</button></form>
		    <a href= "<?php echo base_url('configuration/graduate_attributes'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
		</div>
		<div id="ga_name_exists" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: 	none;">
		    <div class="modal-header">
			<div class="navbar-inner-custom">
			    Warning
			</div>
		    </div>
		    <div class="modal-body">
			<p>The Graduate Attribute Name already exists. </p>
		    </div>
		    <div class="modal-footer">
			<button class="btn btn-danger" data-dismiss="modal"> <i class="icon-remove icon-white"></i> Close</button>
		    </div>
		</div>
		<br><br><br>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/ga.js'); ?>" type="text/javascript"></script>