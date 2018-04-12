<?php
/**
 * Description	:	Department users edit page - View
 * Created		:	27-03-2013
 * Author		:	Arihant Prasad
 * Modification History:
 *   Date                Modified By                			Description
 * 10-07-2017            Jyoti                   Modified  UI display of the form and multiselect of User roles with checkboxes
  --------------------------------------------------------------------------------------------------- */
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
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit User
                                </div>
                            </div>
                            <?php if ($message) { ?>
                                <div class="message error" id="infoMessage" style="color:brown;">
                                    <?php echo $message; ?>
                                </div> 
                            <?php } ?>

                            <!--<?php $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'edit_user');
                                            echo form_open(current_url(), $attributes); ?>-->
                                    <?php $e_id = $this->crypter->c_encode($user_id); ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <form class="form-horizontal edit_dept_user" id="edit_dept_user" name="edit_dept_user" method="POST" action="<?php echo base_url('curriculum/dept_users/edit_dept_user/'.$e_id);?>">
                                        <div class="span6">
                                            <div class="control-group">
                                                <p class="control-label" for="user_title"> Title: <font color='red'> * </font></p>
                                                <div class="controls">
                                                    <select id="user_title" name="user_title" class="input-small required" align="center" autofocus = "autofocus">
                                                        <option value="<?php echo $selected_title; ?>"><?php echo $selected_title; ?></option>
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
                                                    <p class="control-label" for="last_name"> Last Name:  </p>
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
                                                                            <input id="mobile-number" style="width:220px;" type="tel" value="<?php echo $contact;?>" name="mobile-number" class=" phoneNumber "  >
                                                                            <span id="valid-msg" class="hide" style="color:green"></span>
                                                                            <span id="error-msg" class="hide" style="color:red">Invalid number</span>								
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
                                                    <p class="control-label" for="reset_password">Reset Password:</p>
                                                    <div class="controls">
                                                            <?php echo form_input($password); ?>
                                                            <button type="button" class="btn btn-warning reset_randomPassword myTagRemover" title = "Reset Password"><i class="icon-refresh icon-white"></i><span></span> </button>
                                                    </div>
                                            </div>

                                            <div class="control-group">
                                                    <p class="control-label" for="designation_id"> Designation: <font color='red'> * </font></p>
                                                    <div class="controls">
                                                            <?php
                                                            foreach ($designation as $list_item_designation) {
                                                                    $select_options_designation[$list_item_designation['designation_id']] = $list_item_designation['designation_name'];
                                                            }
                                                            echo form_dropdown('designation_id', $select_options_designation, $selected_designation);
                                                            ?>
                                                    </div>
                                            </div>

                                            <div class="control-group">
                                                    <p class="control-label" for="usergroup_id"> User Group: <font color='red'> * </font></p>
                                                    <div class="controls">
                                                            <?php
                                                            foreach ($group as $list_item_group) {
                                                                    $select_options_group[$list_item_group['id']] = $list_item_group['name'];
                                                            }
                                                            //echo form_multiselect('usergroup_id[]', $select_options_group, $selected_group);
                                                            ?>
                                                        <?php echo form_dropdown('usergroup_id[]', $select_options_group, $selected_group, "id='usergroup_id' class='input-medium multiselect' multiple"); ?>
                                                    </div>
                                            </div>
                                        </div>			
                                                <?php echo form_hidden('id', $user->id);
                                                          echo form_hidden($csrf); //csrf - clear flash messages (errors) ?>

                                                <div class="pull-right">
                                                        <button type="submit" class="btn btn-primary"><i class="icon-file icon-white"></i> Update</button>
                                                        <a href="<?php echo base_url('curriculum/dept_users/index'); ?>" style="margin-left: 1px;" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i><span></span> Cancel </a>
                                                </div></br></br>
                                        </form>
					<!--<?php echo form_close(); ?>-->
                                    </div>
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
		<?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/dept_user.js'); ?>" type="text/javascript"> </script>
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
<!-- End of file edit_user.php 
           Location: .configuration/users/edit_user.php -->
