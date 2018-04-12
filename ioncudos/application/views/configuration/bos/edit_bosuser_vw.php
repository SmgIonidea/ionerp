<?php
/**
* Description	:	Edit BoS User View for BoS Module.
* Created		:	25-03-2013. 
* Modification History:
* Date				Modified By				Description
* 23-08-2013		Abhinay B.Angadi        Added file headers, indentations.
* 30-08-2013		Abhinay B.Angadi		Variable naming, Function naming &
*											Code cleaning.
* 10-07-2017            Jyoti                   Modified  UI display of the form 
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
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                                <div class="navbar">
                                        <div class="navbar-inner-custom">
                                                Edit Board of Studies(BoS) Member
                                        </div>
                                </div>
                            
                                <?php if ($message): ?>					
                                        <div class="message error" id="infoMessage" style="color:brown;">
                                                <?php echo $message; ?>
                                        </div> 
                                <?php endif ?>
                            <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <?php
                                    $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'edit_bos_user');
                                    echo form_open(current_url(), $attributes);
                                    ?>
                                    <div class="span6">

                                            <div class="control-group">
                                            <p class="control-label" for="user_title"> Title: <font color='red'> * </font></p>
                                            <div class="controls">
                                                <select id="user_title" name="user_title" class="input-small required" align="center">
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
                                                <p class="control-label" for="first_name">First Name:<font color='red'>*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($first_name); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="last_name">Last Name: </p>
                                                <div class="controls">
                                                    <?php echo form_input($last_name); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="organization">Organization:<font color='red'>*</font></p>
                                                <div class="controls">
                                                    <?php echo form_input($organization); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <p class="control-label" for="email">Email Id:<font color='red'>*</font></p>
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
                                            <p class="control-label" for="qualification">Highest Qualification: </p>
                                            <div class="controls">
                                                                                        <?php echo form_input($qualification); ?>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label" for="experience">Experience(In Years): </p>
                                            <div class="controls">
                                                                                        <?php echo form_input($experience); ?>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label" for="password">Reset Password:</p>
                                            <div class="controls">
                                                                                        <?php echo form_input($password); ?>
                                                                                        <button type="button" class="btn btn-warning reset_randomPassword myTagRemover" title = "Reset Password"><i class="icon-refresh icon-white"></i><span></span> </button>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label" for="designation_id">Designation:<font color='red'>*</font></p>
                                            <div class="controls">
                                                <?php
                                                foreach ($designation as $listitem2) {
                                                    $select_options2[$listitem2['designation_id']] = $listitem2['designation_name']; //group name column index
                                                }
                                                echo form_dropdown('designation_id', array('' => 'Select Designation') + $select_options2, set_value('designation_id', $user->designation_id), 'class="required"');
                                                ?>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label" for="bos_dept_id">BoS for: <font color='red'>*</font></p>
                                            <div class="controls">
                                                <?php
                                                foreach ($department as $listitem1) {
                                                    $select_options1[$listitem1['dept_id']] = $listitem1['dept_name']; //group name column index
                                                }
                                                echo form_dropdown('bos_dept_id', array('' => 'Select Department') + $select_options1, set_value('dept_id', $default_department), 'class="required"');
                                                ?>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <p class="control-label" for="user_dept_id">Home Department: <font color='red'>*</font></p>
                                            <div class="controls">
                                                <?php
                                                foreach ($department as $listitem1) {
                                                    $select_options1[$listitem1['dept_id']] = $listitem1['dept_name']; //group name column index
                                                }
                                                echo form_dropdown('user_dept_id_one', array('' => 'Select Department') + $select_options1, set_value('dept_id', $user->user_dept_id), 'class="required" disabled="disabled"');
                                                ?>
                                                <input type="hidden" name="user_dept_id" id="user_dept_id" value="<?php echo $user->user_dept_id; ?>"
                                            </div>
                                        </div>
                                        <?php echo form_hidden('id', $user->id); ?>
                                                                        <?php echo form_hidden($csrf); ?>
                                                        <br>
                                        <div class="pull-right">
                                            <button type="submit" class="btn btn-primary"><i class="icon-file icon-white"></i> Update</button>
                                            <a href="<?php echo base_url('configuration/bos'); ?>" style="margin-left: 1px;" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i><span></span> Cancel</a>		
                                        </div>	
                                        <br />

                                                                        <?php echo form_close(); ?>
                                                </div>

                                    </div>           
                                </div></div>
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
		<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/bos.js'); ?>" type="text/javascript"> </script>
	<link href="<?php echo base_url('twitterbootstrap/css/intlTelInput.css'); ?>" rel="stylesheet">	
<script language="javascript" type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/intlTelInput.js'); ?>"></script>


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