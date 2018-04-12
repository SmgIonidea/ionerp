<?php
/**
 * Description	:	Add View for Program Outcomes(POs) Type Module.
 * Created		:	03-04-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 03-09-2013		Abhinay B.Angadi        Added file headers, indentations.
 * 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
  --------------------------------------------------------------------------------------------------------
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
                            Add <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Type
                        </div>
                    </div>	
                    <br>
                    <form  class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "<?php echo base_url('configuration/po_type/po_insert_record'); ?>">
                        <div class="control-group">
                            <p class="control-label" for="po_type_name"> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Type Name:<font color="red">*</font></p>
                            <div class="controls">
                                <?php echo form_input($po_type_name); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <p class="control-label" for="po_type_description">Description:</p>
                            <div class="controls">
                                <?php echo form_textarea($po_type_description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>

                        <!--Checkbox Modal-->
                        <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">                                 
                            <div class="modal-header">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="comments">
                                <p >This <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Type Name already Exists. </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                            </div>
                        </div>
                        <br><br><br><br><br><br><br>
                        <div class="pull-right">       
                            <button class="add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                            <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button></form>
                    <a href= "<?php echo base_url('configuration/po_type'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/po_type.js'); ?>" type="text/javascript"></script>
