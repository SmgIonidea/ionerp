<?php
/**
* Description	:	Edit View for Program Mode Module.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 21-08-2013		Abhinay B.Angadi        Added file headers, indentations.
* 28-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
*											Code cleaning.
--------------------------------------------------------------------------------
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
                                    Edit Program Mode
                                </div>
                            </div>	<br><br>
                            <form  class="form-horizontal" method="POST" id="edit_form_id" name="edit_form_id" action= "<?php echo base_url('configuration/program_mode/program_mode_update_record'); ?>">
                                <div class="control-group">
                                    <p class="control-label" for="mode_name">Program Mode Name:<font color="red">*</font></p>
                                    <div class="controls">
                                        <?php echo form_input($mode_name); ?>
                                    </div>
                                </div>
								<div class="control-group">
                                    <p class="control-label" for="mode_description">Description :</p>
                                    <div class="controls">
                                        <?php echo form_textarea($mode_description); ?>
										<br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span></td></tr>
                                    </div>
                                </div>
								<div class="control-group">
                                    <div class="controls"> 
                                        <?php echo form_input($mode_id); ?>
                                    </div>
                                </div>
								<!--####################################################-->
                                <!--Checkbox Modal-->
                                <div id="uniqueness_dialog1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="uniqueness_dialog" data-backdrop="static" data-keyboard="true">
                                    </br>
                                    <div class="container-fluid">
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Warning 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-body" id="comments">
                                        <p >Program Mode with this Name already Exists. </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                                    </div>
                                </div> 
                               
                                <br><br><br><br><br><br><br>
                                <div class="pull-right">
                                    <button class=" submit_edit btn btn-primary"><i class="icon-file icon-white"></i> Update</button>
                            </form>
                            <a href="<?php echo base_url('configuration/program_mode'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program_mode.js'); ?>" type="text/javascript"></script>