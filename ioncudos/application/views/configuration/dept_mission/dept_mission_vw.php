<?php
/**
 * Description          :	View for Department Mission Module.
 * Created		:	22-12-2014 
 * Modification History :
 * Date				Modified By				Description
 * 27-12-2014                   Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 20-01-2016                   Shayista M              Removed white spaces from modal footer.
  -------------------------------------------------------------------------------------------------
 */
?>

<!--head here -->
<!-- /TinyMCE -->
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>

<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?>
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Department Vision/Mission
                        </div>
                    </div>
                    <div class=" form-horizontal">
                        <div class="control-group">
                            <p class="control-label" for="vision">Organization Vision:</p>
                            <div class="controls">
                                <?php echo form_textarea($vision); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="mission">Organization Mission:</p>
                            <div class="controls">
                                <?php echo form_textarea($mission); ?>
                            </div>
                        </div>
                    </div>
                    <form class=" form-horizontal" method="POST" id="add_form_dept_vission" name="add_form_dept_vission">                   
                        <br/></br/>
                        <?php echo form_input($dept_id); ?>
                        <div class="control-group">
                            <p class="control-label" for="dept_vision">Department Vision: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <?php echo form_textarea($dept_vision); ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <p class="control-label" for="dept_mission">Department Mission: <font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <?php echo form_textarea($dept_mission); ?>
                            </div>
                        </div>

                    </form>
                    <div class=" pull-right"><a href="#" id="update_dept_mission" class="btn btn-primary"><i class="icon-file icon-white"></i> Save </a>  </div>
                    <br/><br/>
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Manage Mission Elements
                        </div>
                    </div>
                    <div style=" margin-left: 1cm;">
                        <table class="table table-bordered table-hover" id="example" style="font-size:12px" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header headerSortDown" width="10px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" > Sl.No.</th>
                                    <th class="header headerSortDown" width="650px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Mission Elements</th>
                                    <th class="header" width="20px" role="columnheader" tabindex="0" aria-controls="example"align="center">Edit</th>
                                    <th class="header " width="20px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Delete</th>												 				

                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                            </tbody>
                        </table>
                    </div>
                    <div class="tab1">
                        <form class=" form-horizontal" method="POST" id="add_form" name="add_form">
                            <?php echo form_input($dept_id); ?>

                            <br/><br/>
                            <div class="control-group ">
                                <label class="control-label" for="dept_mission_element">Mission Elements: <font color="red"><b>*</b></font></label>
                                <div class="controls">
                                    <textarea name = "mission_element_1" id = "mission_element_1"	 cols = "100"	rows = "2" style = "width: 93%; height: 45px;"	class = 
                                              " required noSpecialChars mission_ele"></textarea>
                                </div>
                            </div>
                            <!-- <div class="control-group ">
                                 <label class="control-label" for="dept_mission_element">Mission Elements: <font color="red"><b>*</b></font></label>
     
                            <?php
                            if ($missions) {
                                $mission_counter = 1;
                                $count = 0;

                                foreach ($missions as $me) {
                                    $missions['value'] = $me['dept_me'];
                                    ?>
                                                                 <div class="add_me1" id="add_me_div">
                                                                     <div class="control-group ">
                                                                         <div class="controls input-append">
                                                                             <div class="span11" >
                                    <?php if ($me['dept_me'] != '0') { ?>
                                                                                                 <textarea name = "mission_element_<?php echo $mission_counter; ?>" id = "mission_element_<?php echo $mission_counter; ?>"	 cols = "100"	rows = "2" style = "width: 93%; height: 45px;"	class = 
                                                                                                           "required noSpecialChars mission_ele"><?php echo $me['dept_me']; ?></textarea>
                                    <?php } else { ?>
                                                                                                 <textarea name = "mission_element_<?php echo $mission_counter; ?>" id = "mission_element_<?php echo $mission_counter; ?>"	 cols = "100"	rows = "2" style = "width: 93%; height: 45px;"	class = 
                                                                                                           " required noSpecialChars mission_ele"></textarea>
                                    <?php } ?>
                                                                             </div>
                                                                             <div class="span1" >
                                    <?php if ($count > 0) { ?>
                                                                                                 <button id="remove_field_<?php echo $mission_counter; ?>" class="btn btn-danger delete_mission_element error_add" type="button"><i class="icon-minus-sign icon-white"></i> Delete  </button>
                                    <?php } else { ?>
                                                                                                 <button id="add_mission_element" class="btn btn-primary add_mission_element" type="button"><i class="icon-plus-sign icon-white"></i> Add More  </button>
                                    <?php } ?>
                                                                             </div>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                    <?php
                                    $mission_counter++;
                                    $count++;
                                }
                                ?>
                                <?php
                                echo form_input($mission_counter_val);
                                echo form_input($list);
                            } else {
                                ?>  
                                                 <div class="add_me1" id="add_me_div">
                                                     <div class="control-group ">
                                                         <div class="controls input-append">
                                                             <div class="span11" >
                                                                 <textarea name = "mission_element" id = "mission_element"	 cols = "100"	rows = "2" style = "width: 93%; height: 45px;"	class = " required noSpecialChars"></textarea>
                                                             </div>
                                                                                                                 <div class="span1">
                                                                 <button id="add_mission_element" class="btn btn-primary add_mission_element" type="button"><i class="icon-plus-sign icon-white"></i> Add More </button>										
                                                             </div>
                                                         </div>
                                                     </div>
                                                 </div> 
                                <?php
                                echo form_input($mission_counter_val);
                                echo form_input($list);
                            }
                            ?>
     
                                 <div id="mission_element_insert">
     
                                 </div>
                             </div> -->

                            <div class="pull-right">
                                <a href="#" id="update" class="btn btn-primary" data-toggle="modal"><i class="icon-file icon-white"></i> Save </a>  
                                <a href="#" id="update_mission_element" class="btn btn-primary" data-toggle="modal"><i class="icon-file icon-white"></i> Update </a>
                                <button type="reset"  class=" btn btn-info"><i class="icon-refresh icon-white"></i> Reset </button>
                            </div>
                            <br><br>
                            <input type="hidden" id="mission_id" name="mission_id"/>

                        </form>
                    </div>
                    <!-- Modal to display the confirmation message on save -->
                    <div id="delete_myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-controls-modal="" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Delete Confirmation
                            </div>
                        </div>

                        <div class="">
                            <br/><p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Are you sure you want to Delete?</p>
                        </div>

                        <div class="modal-footer">   
                            <button class="btn btn-primary" data-dismiss="modal" id="delete_mission" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>
                        </div>
                    </div>           



                    <div id="myModal_cant_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Warning Message 
                            </div>
                        </div>

                        <div class="modal-body">
                            You can't delete this Mission Element as it is Mapped with PEO's.

                        </div>

                        <div class="modal-footer">   
                            <button class="btn btn-primary" data-dismiss="modal" id="delete_mission" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
<!--<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>-->
                        </div>
                    </div>

                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Success message 
                            </div>
                        </div>

                        <div class="modal-body">
                            <p id="succ_note" ></p>
                        </div>

                        <div class="modal-footer">   
                            <button class="btn btn-primary" data-dismiss="modal" id="" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok </button>
<!--<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel </button>-->
                        </div>
                    </div>	
                    <div id="error_message" style="color:red">
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/dept_mission.js'); ?>" type="text/javascript"></script>

<!-- End of file dept_mission_vw.php 
Location: .configuration/dept_mission/dept_mission_vw.php -->
