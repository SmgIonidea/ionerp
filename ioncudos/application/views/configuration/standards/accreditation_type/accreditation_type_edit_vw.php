<?php
/**
 * Description	:	Edit View for Accreditation Type Module.
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
                            Edit Accreditation Type & their Generic <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>)
                        </div>
                    </div>	
                    <form  class="form-horizontal" method="POST" id="edit_form_id" name="edit_form_id" action= "<?php echo base_url('configuration/accreditation_type/accreditation_type_update_record'); ?>">
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
                                        <?php
                                        foreach ($entity_name_data as $entity_type_data) {
                                            $select_options[$entity_type_data['entity_id']] = $entity_type_data['alias_entity_name'];
                                        }
                                        echo form_dropdown('entity_type', $select_options, $result['entity_id'], 'id="entity_type" 
																class="input-large required"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row-fluid form-horizontal">	
                            <!--<div class="span6">-->
                            <div class="control-group">
                                <p class="control-label" for="accreditation_type_description">Description:</p>
                                <div class="controls">
                                    <?php echo form_textarea($accreditation_type_description); ?>
                                    <br/>
                                    <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                </div>
                            </div>
                            <!--</div>-->

                            <div class="control-group">
                                <div class="controls"> 
                                    <?php echo form_input($accreditation_type_id); ?>
                                </div>
                            </div>
                            <!-- Dynamic PO - Statements block starts here--   --->
                            <div class="span12 table-bordered">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Edit Generic <?php echo $this->lang->line('student_outcome_full'); ?> (<?php echo $this->lang->line('so'); ?>) details
                                    </div>
                                </div>
                                <?php
                                $cloneCntr = 0;
                                foreach ($atype_details as $item):
                                    $cloneCntr++;
                                    $atype_details_id['value'] = $item['atype_details_id'];
                                    $po_label['value'] = $item['atype_details_name'];
                                    $po_label['id'] = "po_label_" . $cloneCntr;
                                    $po_label['name'] = "po_label_" . $cloneCntr;
                                    $po_statement['value'] = $item['atype_details_description'];
                                    $po_statement['id'] = "po_statement_" . $cloneCntr;
                                    $po_statement['name'] = "po_statement_" . $cloneCntr;
                                    $po_statement['data'] = "char_span_support_" . $cloneCntr;
                                    ?>
                                    <div class="row-fluid " ><br>
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
                                                    <?php echo form_dropdown('po_types_' . $cloneCntr, $po_types, $item['po_type_id'], 'id =po_types_' . $cloneCntr . '  class="input-large required"'); ?>
                                                </div>
                                            </div>											
                                        </div>
                                        <div class='control-group'>
                                            <div class='controls pull-right'>
                                                <button id = "delete_po_<?php echo $cloneCntr; ?> " abbr="<?php echo $cloneCntr; ?>" class = "btn btn-danger edit_delete_po_grid" type="button"><i class="icon-minus-sign icon-white"></i> Delete <?php echo $this->lang->line('so'); ?></button>
                                            </div>
                                        </div>
                                        <!--<div class="span6">-->
                                        <div class="control-group">
                                            <p class="control-label" for="po_statement_1"><?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?> Statement: <font color='red'> * </font></p>
                                            <div class="controls clone" id="atype_details_id_<?php echo $cloneCntr; ?>">
                                                <input type="hidden" value="<?php echo $cloneCntr; ?>" name="clone_counter" id="clone_counter" class="clone_counter">
                                                <?php echo form_textarea($po_statement); ?>
                                                <br/>
                                                <span id="char_span_support_<?php echo $cloneCntr ?>" class='margin-left5'>0 of 2000. </span>

                                                <?php echo form_input($atype_details_id); ?>
                                            </div>
                                        </div>
                                        <!--</div>-->
                                    </div><hr>
                                <?php endforeach; ?>
                            </div>
                            <div id="po_grid_insert">
                            </div>
                            <!-- Dynamic PO - Statements block ends here--   --->
                            <div class="pull-right">
                                <input type="hidden" id="po_counter" name="po_counter" value="<?php echo $cloneCntr; ?>" />	
                                <input type="hidden" name="po_stack_counter" id="po_stack_counter" value="1"/>
                                <br>
                                <button id="add_more_po_grid" class="btn btn-primary" type="button"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('so'); ?></button>
                                <button type="button" id="po_update" class="edit_form_submit btn btn-primary" onclick="search_accreditation_type();"><i class="icon-file icon-white"></i> Update</button>
                                <a href="<?php echo base_url('configuration/accreditation_type'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                            </div>
                        </div>
                    </form>


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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/edit_accreditation_type.js'); ?>" type="text/javascript"></script>   
