<?php
/**
 * Description	:	Displaying the list of users, adding new users, editing existing users
 * 					
 * Created		:	27-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 02-09-2013		    Arihant Prasad			File header, indentation, comments and variable 
												naming.
 * 10-07-2017           Jyoti                   Modifed UI form design and multiselect user roles with checkboxes
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
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Add User
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid"> 
                            <?php if ($message): ?>
                                <div class="message error" id="infoMessage" style="color:brown;">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif ?>

                            <?php
                            $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'create_user');
                            echo form_open("configuration/users/create_user", $attributes);
                            ?>				
                            <div class="span6">
                                <div class="control-group">
                                    <p class="control-label" for="user_title"> Title: <font color='red'> * </font></p>
                                    <div class="controls">
                                        <select id="user_title" name="user_title" class="input-small required" align="center" autofocus = "autofocus">
                                                                                    <option value="">--</option>
                                                                                    <option value="Mr.">Mr.</option>
                                                                                    <option value="Mrs.">Mrs.</option>
                                                                                    <option value="Ms.">Ms.</option>
                                                                                    <option value="Miss.">Miss.</option>
                                                                                    <option value="Prof.">Prof.</option>
                                                                                    <option value="Dr.">Dr.</option>
                                                                            </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <p class="control-label" for="first_name"> First Name: <font color='red'> * </font></p>
                                    <div class="controls">
                                        <?php echo form_input($first_name); ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <p class="control-label" for="last_name"> Last Name: </p>
                                    <div class="controls">
                                        <?php echo form_input($last_name); ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <p class="control-label" for="email"> Email Id: <font color='red'> * </font></p>
                                    <div class="controls">
                                        <?php echo form_input($email); ?>
                                    </div>
                                </div>
                                <div class="control-group">
                                                        <label class="control-label" for="country_code">Contact Number: </label>
                                                                <div class="controls">
                                                                        <input id="mobile-number" style="width:220px;" type="tel" value="" name="mobile-number" class=" phoneNumber "  >
                                                                        <span id="valid-msg" class="hide" style="color:green"></span>
                                                                        <span id="error-msg" class="hide" style="color:red">Invalid number</span>								
                                                                </div>
                                </div>
    
                                <div class="control-group">
                                    <p class="control-label" for="user_dept_id">Department: <font color='red'> * </font></p>
                                    <div class="controls">
                                        <?php
                                        foreach ($department as $list_item_department) {
                                            $select_options_department[$list_item_department['dept_id']] = $list_item_department['dept_name']; 
                                        }

                                        echo form_dropdown('user_dept_id', array('' => 'Select Department') + $select_options_department, set_value('dept_id', '0'), 'class="required"');
                                        ?>
                                    </div>
                                </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <p class="control-label" for="qualification"> Highest Qualification: </p>
                                <div class="controls">
                                    <?php echo form_input($qualification); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <p class="control-label" for="experience"> Experience(In Years): </p>
                                <div class="controls">
                                    <?php echo form_input($experience); ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <p class="control-label" for="password"> Password: <font color='red'> * </font></p>
                                <div class="controls">
                                    <?php echo form_input($password); ?>
									<button type="button" class="btn btn-warning randomPassword myTagRemover" title = "Generate Password"><i class="icon-refresh icon-white"></i><span></span> </button>
								</div>
                            </div>

                            <div class="control-group">
                                <p class="control-label" for="designation_id"> Designation: <font color='red'> * </font></p>
                                <div class="controls">
                                    <?php
                                    foreach ($designation as $list_item_designation) {
                                        $select_options_designation[$list_item_designation['designation_id']] = $list_item_designation['designation_name'];
                                    }

                                    echo form_dropdown('designation_id', array('' => 'Select Designation') + $select_options_designation, set_value('designation_id', '0'), 'class="required"');
                                    ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <p class="control-label" for="usergroup_id">User Group: <font color='red'> * </font></p>
                                <div class="controls ">
                                    <?php
                                    foreach ($group as $list_item_group) {
                                        $select_options_group[$list_item_group['id']] = $list_item_group['name'];
                                    }
                                    //echo form_multiselect('usergroup_id[]', array('1' => 'Admin') + $select_options_group, set_value('usergroup_id', ''), 'class="required"');
                                    ?>
                                    
                                    <select name="usergroup_id[]" id="usergroup_id" class="usergroup_id input-large multiselect" multiple >
                                        <?php foreach ($group as $list_item_group) { ?>
                                            <option value="<?php echo $list_item_group['id']; ?>" title="<?php echo $list_item_group['name']; ?>"><?php echo $list_item_group['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                
                                </div>
                            </div>
                        </div><br />
                            <div class="pull-right"><br />
                                <button type="submit" class="btn btn-primary"><i class="icon-file icon-white"></i> Save</button>
                                <button type="reset" class="btn btn-info"><i class="icon-refresh icon-white"></i> Reset</button>
                                <a href="<?php echo base_url('configuration/users/list_users'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i> Cancel</a>
                            </div><br />
                            <?php echo form_close(); ?>
                        </div></div></div></div></div>
                        <!--Do not place contents below this line-->	
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?> 
        <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/user_create_edit.js'); ?>" type="text/javascript"> </script>      
	<link href="<?php echo base_url('twitterbootstrap/css/intlTelInput.css'); ?>" rel="stylesheet">	
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/intlTelInput.js'); ?>"></script>
<script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>

<script>
// Dont remove comment 
$("#mobile-number").intlTelInput({
//autoFormat: false,
//autoHideDialCode: false,
//defaultCountry: "jp",
//nationalMode: true,
//numberType: "MOBILE",
//onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
//preferredCountries: ['cn', 'jp'],
//responsiveDropdown: true,
utilsScript: "<?php echo base_url('twitterbootstrap/js/utils.js'); ?>"
});
</script>

<!-- End of file create_success_vw.php 
			Location: .configuration/users/create_success_vw.php -->
