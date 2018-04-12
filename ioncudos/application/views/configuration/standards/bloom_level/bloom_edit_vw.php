<?php
/**
 * Description	:	To edit the existing bloom's level details.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 27-08-2013		   Arihant Prasad			File header, indentation, comments and variable naming.
 * 
  ------------------------------------------------------------------------------------------------- */
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
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Edit Bloom's Level
                        </div>
                    </div>
                    <br>
                    <form class="form-horizontal" method="POST" id="edit_form_id" name="edit_form_id" action= "<?php echo base_url('configuration/bloom_level/bloom_update_record'); ?>">
                        <div class="control-group">
                            <p class="control-label"> Bloom's Domain: <font color="red"> * </font></p>
                            <div class="controls">
                                <select id="bloom_domain" name="bloom_domain" autofocus = "autofocus" class="required">
                                    <option value=""> Select Bloom Domain</option>
                                    <?php
                                    foreach ($bloom_domain_list as $list_item):
                                        if ($list_item['bld_id'] == $bloom_domain_id) {
                                            ?>
                                            <option value="<?php echo $list_item['bld_id']; ?>" selected> <?php echo $list_item['bld_name']; ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $list_item['bld_id']; ?>"> <?php echo $list_item['bld_name']; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span5">
                                <div class="control-group">
                                    <p class="control-label"> Bloom's Level: <font color="red"> * </font></p>
                                    <div class="controls">
                                        <?php echo form_input($level); ?>
                                    </div>
                                </div>
                            </div> 
                            <div class="span5">
                                <div class="control-group">
                                    <p class="control-label"> Level Of Learning: <font color="red"> * </font></p>
                                    <div class="controls"> 
                                        <?php echo form_input($description); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label"> Characteristics Of Learning: <font color="red"> * </font></p>
                            <div class="controls"> 
                                <?php echo form_textarea($learning); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>

                        <div class="control-group">
                            <p class="control-label"> Bloom's Action Verbs: <font color="red"> * </font></p>
                            <div class="controls">
                                <?php echo form_textarea($bloom_actionverbs); ?>
                                <br/> <span id='char_span_support1' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class=""><b>Note:</b> Enter The Action Verbs as comma(,) separated values. <b>Example:</b> List, Identify, Outline.</p>

                        </div>
                        <div class="span12" id="assess_methods">
                            <div class="control-group span5">
                                <?php
                                $cloneCntr = 1;
                                if (!empty($assess_data)) {
                                    foreach ($assess_data as $item):
                                        $assess_method['value'] = $item['assess_name'];
                                        $assess_method_id['value'] = $item['assess_id'];
                                        $assess_method['id'] = "assess_method" . $cloneCntr;
                                        $assess_method['name'] = "assess_method" . $cloneCntr;
                                        ?>
                                        <br>
                                        <?php $cloneCntr++; ?>
                                    <?php endforeach; ?>
                                    <input type="hidden" value="<?php echo $cloneCntr - 1; ?>" name="clone_counter" id="clone_counter" class="clone_counter">
                                <?php }else {
                                    ?>                                 
                                    <input type="hidden" value="<?php echo $cloneCntr; ?>" name="clone_counter" id="clone_counter" class="clone_counter">
                                    <br>
                                <?php }
                                ?>
                                <div id="add_methods" >
                                </div>
                            </div>                         
                        </div>
                        <input type="hidden" value="" name="assessment_val" id="assessment_val" class="assessment_val">
                        <div class="control-group">
                            <div class="controls"> 
                                <?php echo form_input($bloom_id); ?>
                            </div>
                        </div>
                        <br><br>
                        <div class="pull-right">
                            <button class="edit_submit btn btn-primary" type="submit" ><i class="icon-file icon-white"></i> Update </button>
                    </form>
                    <a href="<?php echo base_url('configuration/bloom_level'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                </div>
                <!-- Modal to display the uniqueness message -->
                <div id="myModal_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="false">
                    </br>
                    <div class="container-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Uniqueness Warning 
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" id="comments">
                        <p> Bloom's Level with this Level Name already Exists. </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/blooms_level_edit.js'); ?>" type="text/javascript"></script>

<!-- End of file bloom_edit_vw.php 
                      Location: .configuration/standards/bloom_level/bloom_edit_vw.php -->