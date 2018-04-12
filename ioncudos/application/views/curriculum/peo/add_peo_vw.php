<?php
/**
 * Description          :	PEO grid displays the PEO statements and PEO type.
  PEO statements can be deleted individually and can be edited in bulk.
  Notes can be added, edited or viewed.
  PEO statements will be published for final approval.
 * 					
 * Created		:	02-04-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                   Modified By                			Description
 *  05-09-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 *  02-01-2016		   Shayista Mulla 			Added loading image and cokie.
 *  18/01/2016             Bhagyalaxmi S S			Added Edit PEO feature
  ---------------------------------------------------------------------------------------------- */
?>

<!-- head here -->
<?php $this->load->view('includes/head'); ?>
<!-- branding here -->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Content -->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <!-- content goes here -->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Program Educational Objectives (PEOs)
                        </div>
                    </div>
                    
                    <div>
                        <label >
                            <span data-key="lg_crclm">Curriculum</span>:<font color="red"> * </font>
                            <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" method="post" disabled>
                                <option value="" selected data-key="lg_sel_crclm" > Select the Curriculum </option>
                                <?php foreach ($results as $list_item):?>
                                    $select_options1[$list_item['crclm_id']] = $list_item['pgm_title'];
                                    <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                <?php endforeach; ?>
                            </select>

                        </label>
                    </div><br/>
                    
                    <div>
                        <font color="red" ><?php echo validation_errors(); ?></font>
                    </div>
                    <div >
                        <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header headerSortDown" style = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Sl No.</th>
                                    <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Program Educational Objectives(PEO) </th>
                                    <th class="header headerSortDown" style = "width: 40px;" role="columnheader" tabindex="0"  >Edit</th>
                                    <th class="header" style = "width: 50px;" role="columnheader" tabindex="0" aria-controls="example"> Delete </th>
                                </tr>
                            </thead>
                            <tbody id="peolist">
                                <!--refer list_peo_table_vw page-->
                            </tbody>
                        </table>
                    </div>
                    <?php
                    //$attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'peo_add');
                    // echo form_open("curriculum/peo/peo_insert" . '/' . $curriculum_id['value'], $attributes);
                    ?>	



                    <form class="form-horizontal"  method="post" id="peo_add" >

                        <div id="add_peo">
                            <div id="remove" >
                                <div class="span3" style="width:1040px; height:170px;">

                                    <div class="control-group">
                                        <label class="control-label" for="peo_reference"> Program Educational Objective(PEO) Reference: <font color="red"> * </font></label>
                                        <div class="controls">
                                            <?php echo form_input($peo_reference); ?>
                                        </div>
                                    </div>

                                    <p class="control-label" for="peo_statement"> Program Educational Objective(PEO) Statement: <font color="red"> * </font></p>
                                    <div class="controls" >

                                        <?php echo form_textarea($peo_statement); ?> &nbsp; &nbsp;
                                        <span id="error" class="error" ></span>
                                        <a id="remove_field_1"  class="Delete" href="#"></a>
                                        <br/> <span id='char_span_support1' class='margin-left5'>0 of 2000. </span>    					
                                    </div>
                                    <input type="hidden" id="crclm_id" value=" <?php echo $curriculum_id['value']; ?>"
                                    <?php echo form_hidden($curriculum_id); ?>
                                    <?php echo form_input($crclm); ?>
                                </div>
                            </div>
                        </div>

                        <div id="insert_before">
                        </div>
                        <input type="hidden" name="counter" id="counter" value="1" >
                        <br/>
                        <div >
                            <div id="attendees_div" class="control-group">
                                <p class="control-label" for="attendees"> Attendees Name: <font color="red"> * </font></p>
                                <div class="controls">
                                    <?php echo form_textarea($attendees); ?>  	 <?php echo str_repeat("&nbsp;", 50); ?> 
                                    <span> Meeting Notes :  &nbsp;&nbsp;   </span>  <?php echo form_textarea($notes); ?> 
                                </div>
                            </div>

                        </div>
                        <div class="pull-right">       
                            <button  class="submit1 btn btn-primary peo_save"  type="button"><i class="icon-file icon-white"></i> Save</button>
                            <button type="reset" class="btn btn-info"><i class="icon-refresh icon-white"></i> Reset</button>
                            <a href= "<?php echo base_url('curriculum/peo/peo'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close </a>
                        </div><br>

                    </form>
                    <?php //echo form_close(); ?>	
                    <!--Do not place contents below this line-->	
                </div>


                <input type="hidden" id="peo_id" name="peo_id"/>
                <div id="edit_peo_modal" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="edit_peo_modal" data-backdrop="static" data-keyboard="false">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_edit_peo">
                                Edit PEO
                            </div>
                            <b id="crclm_name_text_1"></b>
                        </div>
                    </div>
                    <form id="peo_edit_form">
                        <div class="modal-body">
                            <div class="" style="">
                                <div class="control-group">
                                    <label class="control-label" for="peo_reference"><span data-key="lg_peo_ref"> Program Educational Objective(PEO) Reference</span>: <font color="red"> * </font>&nbsp;&nbsp;
                                        <input class="input-mini" type="text" id="peo_state" name="peo_state" required/>	
                                    </label>										
                                </div>

                                <p class="control-label" for="peo_statement"><span data-key="lg_peo_stmt">Program Educational Objective(PEO) Statement</span>: <font color="red"> * </font></p>
                                <div class="controls" >			
                                    <textarea id="peo_statement" class="char-counter" maxlength="2000" name="peo_statement" style="margin: 0px; width: 495px; height: 50px;" required></textarea>
                                    <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                </div><br>

                                <div id="attendees" class="control-group">
                                    <p class="control-group" for="attendees"><span data-key="lg_attendees_name"> Attendees Name</span>: <font color="red"> * </font></p>
                                    <div class="controls">
                                        <?php //echo form_textarea($attendees); ?>
                                        <textarea id="attendees_name" name="attendees_name" style="margin: 0px; width: 495px; height: 50px;" required></textarea>
                                    </div>
                                </div>

                                <div id="notes" class="control-group">
                                    <p class="control-label" for="notes"><span data-key="lg_attendees_notes"> Attendees Notes</span>: </p>
                                    <div class="controls">
                                        <?php //echo form_textarea($notes); ?>
                                        <textarea id="attendees_notes" name="attendees_notes" style="margin: 0px; width: 495px; height: 50px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="modal-footer">
                        <button class="btn btn-primary" id="peo_update" name="peo_update"><i class="icon-file icon-white"></i> <span data-key="lg_update">Update</span></button>
                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button> 
                    </div>
                </div>	
                <!-- Modal to display the confirmation message before deleting PEO statement -->
                <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModal_delete" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                <span data-key="lg_del_conf">Delete Confirmation </span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p data-key="lg_delete_conf" data-key="lg_delete_conf"> Are you sure you want to Delete? </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary delete_peo" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> <span data-key="lg_ok">Ok</span></button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_cancel">Cancel</span></button>
                    </div>
                </div>

                <!-- Modal for duplicate PEO Statement-->
                <div id="duplicate_peo" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="duplicate_peo" aria-hidden="true" data-controls-modal="duplicate_peo" data-backdrop="static" data-keyboard="true">
                    <div class="modal-header">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Warning
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <p data-key="lg_delete_conf" data-key="lg_delete_conf"> This PEO Statement already exists. </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> <span data-key="lg_close">Close</span></button> 
                    </div>


                </div>


        </div>					
        </section>
    </div>
</div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/peo.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

<!-- End of file add_peo_vw.php 
                        Location: .curriculum/peo/add_peo_vw.php -->
