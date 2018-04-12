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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
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

                    <form  class="form-horizontal" method="POST" id="add_form_id" name="add_form_id">
                        <br>
                        <div class="control-group" >   
                            <label class="control-label" for="program_types">Program Type : <font color='red'>*</font></label>
                            <div class="controls">

                                <select id="program_type" name="program_type" class="required input-large">
                                    <option value="" selected>Select Program Type</option>
				    <?php foreach ($program_types as $method) { ?>
    				    <option value="<?php echo $method['pgmtype_id']; ?> " ><?php echo $method['pgmtype_name']; ?> </option>
				    <?php } ?>
                            </div>							
                            </select>
                        </div><br>
                        <div class="control-group">
                            <p class="control-label" for="ga_reference"> Sl No. : <font color="red">*</font></p>
                            <div class="controls">
				<?php echo form_input($ga_reference); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="ga_reference"> Graduate Attribute : <font color="red">*</font></p>
                            <div class="controls">
				<?php echo form_textarea($ga_statement); ?>
				<br/> <span id='char_span_support_1' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="ga_description">Graduate Attribute Description:</p>
                            <div class="controls">
				<?php echo form_textarea($ga_description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
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

                        <br><br><br><br><br><br><br>
                    </form>  <div class="pull-right">       
                        <button class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
                        <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                        <a href= "<?php echo base_url('configuration/graduate_attributes'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>	