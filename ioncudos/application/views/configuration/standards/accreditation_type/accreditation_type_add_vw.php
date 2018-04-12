<?php
/**
 * Description	:	Add View for Accreditation Type Module.
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
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Accreditation Type & their Generic <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>)
                        </div>	
                        <form  class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "<?php echo base_url('configuration/accreditation_type/accreditation_type_insert_record'); ?>">
                            <div class="row-fluid">
                                <div class="span5">
                                    <div class="control-group">
                                        <p class="control-label" for="accreditation_type_name">Accreditation Type:<font color="red">*</font></p>
                                        <div class="controls">
                                            <?php echo form_input($accreditation_type_name); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="span4" style="display:none;">
                                    <div class="control-group">
                                        <p class="control-label inline" for="entity_type">Granted To: <font color="red">* </font></p>
                                        <div class="controls">
                                            <select class="required input-large" id="entity_type" name="entity_type" class="required">
                                                <option value="">Select Entity Type</option>
                                                <?php foreach ($entity_name_data as $entity_type): ?>
                                                    <option value="<?php echo $entity_type['entity_id']; ?>" ><?php echo $entity_type['alias_entity_name']; ?></option>
                                                <?php endforeach; ?>						
                                            </select>	
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="control-group">
                                    <!--<div class="span6">-->
                                    <p class="control-label" for="accreditation_type_description">Description:</p>
                                    <div class="controls">
                                        <?php echo form_textarea($accreditation_type_description); ?>
                                        <br/>
                                        <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                    </div>
                                    <!--</div>-->
                                </div>


                                <!-- Dynamic PO - Statements block starts here--   --->
                                <div class="span12 add_me table-bordered">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Add Generic <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>) details
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span5">
                                            <div class="control-group">
                                                <p class="control-label" for="po_label"><?php echo $this->lang->line('so'); ?> Reference: <font color='red'>*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($po_label); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span3">
                                            <div class="control-group">
                                                <p class="control-label"> <?php echo $this->lang->line('so'); ?> Type: <font color="red"> * </font></p>
                                                <div class="controls">
                                                    <select id="po_types_1" name="po_types_1" class="input-large required">
                                                        <?php foreach ($po_types['po_types_id_names'] as $po_type): ?>
                                                            <option value="<?php echo $po_type['po_type_id']; ?>" selected="selected"><?php echo $po_type['po_type_name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>											
                                        </div>

                                    </div>
                                    <div class="row-fluid">
                                        <!--<div class="span6">-->
                                        <div class="control-group">
                                            <p class="control-label" for="po_statement_1"> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Statement: <font color='red'> * </font></p>
                                            <div class="controls">
                                                <?php echo form_textarea($po_statement); ?>
                                                <br/>
                                                <span id="char_span_support_1" class='margin-left5'>0 of 2000. </span>
                                            </div>
                                        </div>
                                        <!--</div>-->
                                    </div>
                                </div>

                                <div id="po_grid_insert">
                                </div>
                                <!-- Dynamic PO - Statements block ends here--   --->

                                <div class="pull-right">
                                    <input type="hidden" id="po_counter" name="po_counter" value="1" />	
                                    <input type="hidden" name="po_stack_counter" id="po_stack_counter" value="1"/>
                                    <br>
                                    <button id="add_more_po1" class="btn btn-primary add_more_po" onclick="add_more_po();" type="button"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('so'); ?></button>
                                    <button class="add_form_submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i> Save</button>
                                    <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
                                    </form>
                                    <a href= "<?php echo base_url('configuration/accreditation_type'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
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
                                        <p >This Accreditation Type Name already exists. </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                    </div>
                                </div>
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
        <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/accreditation_type.js'); ?>" type="text/javascript"></script>
