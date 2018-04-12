<?php
/**
 * Description	:	Edit View for Program Type Module.
 * Created		:	25-03-2013. 
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Abhinay B.Angadi        Added file headers & indentations.
 * 27-08-2013		Abhinay B.Angadi		Variable naming, Function naming & 
 * 											Code cleaning.
  --------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
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
                            Edit Program Type
                        </div>
                        <div>
                            <font color= "red" ><?php echo validation_errors(); ?></font>
                        </div>
                    </div><br><br><br>
                    <form  class="form-horizontal" method="POST" id="edit_form_id" name="edit_form_id" action="<?php echo base_url('configuration/programtype/program_type_update_record'); ?>">
                        <div class="control-group">
                            <p class="control-label" for="pgmtype_name">Program Type Name :<font color="red">*</font></p>
                            <div class="controls">
                                <?php echo form_input($pgmtype_name); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="pgmtype">Program Type :<font color="red">*</font></p>
                            <div class="controls">
                                <select name="pgm_type" id="pgm_type" class="required">
                                    <option value="">Select Program Type</option>
                                    <?php foreach ($pgm_type_list as $pgm_type) { ?>
                                        <option value="<?php echo $pgm_type['master_type_id'] ?>"
                                        <?php
                                        if ($pgm_type_id == $pgm_type['master_type_id']) {
                                            echo "selected";
                                        }
                                        ?>><?php echo $pgm_type['master_type_name'] ?></option>
                                            <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="pgm_description">Description :</p>
                            <div class="controls"> 
                                <?php echo form_textarea($pgm_description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls"> 
                                <?php echo form_input($pgmtype_id); ?>
                            </div>
                            <!--Checkbox Modal-->
                            <div id="uniqueness_dialog_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="uniqueness_dialogLabel" aria-hidden="true" style="display: none;" data-controls-modal="uniqueness_dialog_edit" data-backdrop="static" data-keyboard="true">
                                </br>
                                <div class="container-fluid">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Uniqueness Warning 
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>This Program Type Name already Exists.</p>
                                    <br>
                                </div>						
                                <div class="modal-footer">
                                    <button type="reset" class="cancel btn btn-primary" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                                </div>
                            </div>
                        </div><br><br><br><br><br>
                        <div class="pull-right">
                            <button class="edit_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Update</button>
                    </form>
                    <a class="btn btn-danger" href="<?php echo base_url('configuration/programtype'); ?>"><i class="icon-remove icon-white"></i><span></span> Cancel</a>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/program_type.js'); ?>" type="text/javascript"></script>