<?php
/* -----------------------------------------------------------------------------------------------------------------------------
 * Description	: View for Department Adding, Editing and Disabling/Enabling operations performed through this file.	  
 * Modification History:
 * Date							Modified By						Description
 * 20-08-2013                   Mritunjay B S                   Added file headers, function headers & comments. 
  ------------------------------------------------------------------------------------------------------------------------------
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
                            Add Department
                        </div>
                        <div>
                            <font color= "red" ><?php echo validation_errors(); ?></font>
                        </div>

                        <br>
                    <form class=" form-horizontal" method="POST" id="add_form" name="add_form" action="<?php echo base_url('configuration/add_department/index/1'); ?>">
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>	
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
                                    foreach ($hod_data as $hod_information) {
                                        $select_options3[$hod_information['id']] = $hod_information['title'] . " " . $hod_information['first_name'] . " " . $hod_information['last_name']; //group name column index
                                        $title_list[$hod_information['id']] = $hod_information['email'];
                                    };
                                    echo form_dropdown_custom_new('HOD', array('' => 'Select Head Of Department') + $select_options3, set_value('HOD'), 'class="input-large required" id="HOD"', array('' => '') + $title_list);
                                    ?> 
                                </div>
                            </div>    
                            <div class="control-group">
                                <p class="control-label" for="Offerprogram">Year Of Establishment :<font color="red"><b>*</b></font></p>
                                <div class="controls">
                                    <div class="input-append date">
                                        <span class="add-on" id="btn" style="cursor:pointer;"><i class="icon-calendar"></i></span>
                                        <input type="text" class="span10 required yearpicker" id="dp3" name="dp3" readonly>
                                        <span class="year_error"></span>
                                    </div>
                                </div>
                            </div>
						</div>
						<div class="span6">
                            <div class="control-group">
                                <p class="control-label" for="no_of_journals"> Number of Journals :</p>
                                <div class="controls">
									<input type="text" id="no_of_journals" name=" no_of_journals" class="text_align_right text-left allownumericwithoutdecimal" />
                                </div>
                            </div> 
							<div class="control-group">
                                <p class="control-label" for="no_of_magazines"> Number of Magazines :</p>
                                <div class="controls">
									<input  type="text" id="no_of_magazines" name=" no_of_magazines" class="text_align_right text-left allownumericwithoutdecimal" />
                                </div>
                            </div> 							
                            <div class="control-group">
                                <p class="control-label" for="professional_bodies"> Professional Bodies :</p>
                                <div class="controls">
									<textarea cols="4" rows="3" id="professional_bodies" name=" professional_bodies" ></textarea>
                                </div>
                            </div> 
						</div>	
						
						</div>
						<div class="control-group span12">
                                <p class="control-label" for="inputDescription"> Description :</p>
                                <div class="controls">
                                    <?php echo form_textarea($dept_description); ?>
                                    <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                </div>
                        </div> 
					
                            <div class="pull-right">
                                <button class="submit1 btn btn-primary" type=""><i class="icon-file icon-white"></i> Save</button>
                                <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>
					
                        <a href= "<?php echo base_url('configuration/department'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a>
                    </div>
					</form><br/><br/><br/>
                </div>
                <!--Do not place contents below this line-->		
				</div>
            </section>	
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/department.js'); ?>"></script>

