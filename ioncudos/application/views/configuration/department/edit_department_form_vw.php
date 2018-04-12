<?php
/* -----------------------------------------------------------------------------------------------------------------------------
 * Description: View for Department Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date				Modified By				Description
 * 20-08-2013		Mritunjay B S       	Added file headers, function headers & comments. 
  -------------------------------------------------------------------------------------------------------------------------------
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
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Edit Department
                        </div>
                        <div>
                            <font color= "red" ><?php echo validation_errors(); ?></font>
                        </div>
                    </div>	
                   <br/>

                    <form class=" form-horizontal frm" method="POST" id="frm" name="frm" action="<?php echo base_url('configuration/add_department/department_edit') . '/' . $dept_id['value'] . '/' . '1'; ?>">
					
					<div class="span12">
						<div class="span6">
                        <div class="control-group">
                            <p class="control-label" for="inputEmail">Name :<font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <?php echo form_input($dept_name); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="inputPassword">Short Name :<font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <?php echo form_input($dept_acronmy); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="HeadofDepartment">Chairman (HoD) :<font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <?php
                                foreach ($dept_hod_data as $dept_hod_info) {
                                    $select_options3[$dept_hod_info['id']] = $dept_hod_info['title'] . " " . $dept_hod_info['first_name'] . " " . $dept_hod_info['last_name'];
                                    $title_list[$dept_hod_info['id']] = $dept_hod_info['email'];
                                }
                                echo form_dropdown_custom_new('HOD', $select_options3, $select_hod_id, 'class="input-large required" id="HOD"', array('' => '') + $title_list);
                                ?>
                            </div>
                        </div>

                        <div class="control-group">
                            <p class="control-label" for="Offerprogram">Year Of Establishment :<font color="red"><b>*</b></font></p>
                            <div class="controls">
                                <div class="input-append date">
                                    <input type="text" class="span12 yearpicker" id="dp3" name="dp3" readonly value="<?php echo $dept_establishment_date; ?>">
                                    <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                </div>
                            </div>
                        </div>
						</div>
						<div class="span6">
						    <div class="control-group">
                                <p class="control-label" for="no_of_journals"> Number of Journals :</p>
                                <div class="controls">
									<input  value="<?php echo $no_of_journals;?>" type="text" id="no_of_journals" name=" no_of_journals" class="text_align_right text-left allownumericwithoutdecimal" />
                                </div>
                            </div> 
							<div class="control-group">
                                <p class="control-label" for="no_of_magazines"> Number of Magazines :</p>
                                <div class="controls">
									<input  value="<?php echo $no_of_magazines;?>" type="text" id="no_of_magazines" name=" no_of_magazines" class="text_align_right text-left allownumericwithoutdecimal" />
                                </div>
                            </div> 
                            <div class="control-group">
                                <p class="control-label" for="inputDescription">Professional Bodies :</p>
                                <div class="controls">
									<textarea cols="4" rows="3" id="professional_bodies" name=" professional_bodies" ><?php echo $professional_bodies; ?></textarea>
                                </div>
                            </div> 
  
						</div>
						</div>
					                       <div class="control-group">
                            <p class="control-label" for="inputDescription">Description :</p>
                            <div class="controls">
                                <?php echo form_textarea($dept_description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div> 
					
                        <?php echo form_input($dept_id); ?>
                        <?php echo form_input($user_id); ?>
                        <div class="pull-right">
                            <button type="submit" class="edit_submit btn btn-primary"><i class="icon-file icon-white"></i> Update</button>
                            <a href="<?php echo base_url('configuration/department'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                        </div></br>
                    </form>
                    <!--Do not place contents below this line-->	
                </div>
            </section>
        </div>

        <!-- Modal to display the mapping status  -->
        <div id="myModal_warning" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="true">
            <div class="modal-header">
                <div class="navbar">
                    <div class="navbar-inner-custom">
                        Warning
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <p> The Department Name already exists. </p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button> 
            </div>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>

<link rel="stylesheet" href="/resources/demos/style.css" />

<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/configuration/department.js'); ?>"></script>
