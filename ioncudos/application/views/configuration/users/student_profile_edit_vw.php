<?php
/**
 *
 * Description	:	Displaying the list of users, adding new users, editing existing users
 * 					
 * Created		:	27-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 02-09-2013		   Arihant Prasad			File header, indentation, comments and variable 
  naming.
 *
  ---------------------------------------------------------------------------------------------- */
?>

<!DOCTYPE html>
<html lang="en">
    <!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>

    <body data-spy="scroll" data-target=".bs-docs-sidebar">
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
                <?php $this->load->view('includes/sidenav_3'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    My Profile Edit
                                </div>
                            </div>	
                            <?php if ($message): ?>					
                                <div class="message error" id="infoMessage">
                                    <?php echo $message; ?>
                                </div> 
                                <?php
                            endif;

                            $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'edit_user');
                            echo form_open(current_url(), $attributes);
                            ?>
                            <div class="row-fluid"><br>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label" for="user_title"> Title: <font color='red'> * </font></label>
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
                                        <label class="control-label" for="first_name"> First Name: <font color='red'> * </font></label>
                                        <div class="controls">
                                            <?php echo form_input($first_name); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="last_name"> Last Name: <font color='red'> * </font></label>
                                        <div class="controls">
                                            <?php echo form_input($last_name); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="email"> Email Id: <font color='red'> * </font></label>
                                        <div class="controls">
                                            <?php echo form_input($email); ?>
                                        </div>
                                    </div>


                                </div>                            
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label" for="contact">Contact:</label>
                                        <div class="controls">
                                            <?php echo form_input($contact); ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="reset_password">Reset Password:</label>
                                        <div class="controls">
                                            <?php echo form_input($password); ?>
                                        </div>
                                    </div>

                                    <div class="control-group">
                                        <label class="control-label" for="designation_id"> Designation: <font color='red'> * </font></label>
                                        <div class="controls">
                                            <?php
                                            foreach ($designation as $list_item_designation) {
                                                $select_options_designation[$list_item_designation['designation_id']] = $list_item_designation['designation_name'];
                                            }
                                            echo form_dropdown('designation_id', $select_options_designation, $selected_designation, 'disabled = "disabled"');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="user_dept_id"> Department: <font color='red'> * </font></label>
                                        <div class="controls">
                                            <?php
                                            foreach ($department as $list_item_department) {
                                                $select_options_department[$list_item_department['dept_id']] = $list_item_department['dept_name'];
                                            }
                                            echo form_dropdown('user_dept_id', $select_options_department, $selected_department, 'disabled = "disabled"');
                                            ?>
                                        </div>
                                    </div>
                                    <?php echo form_input($qualification); ?>
                                    <?php echo form_input($experience); ?>
                                    <?php echo form_hidden('usergroup_id[]', $selected_group[0]); ?>
                                    <?php echo form_hidden('id', $user->id); ?>
                                </div><br/><br/>
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary"><i class="icon-plus-sign icon-white"></i> Update</button>
                                <a href="<?php echo base_url('home/'); ?>" style="margin-left: 10px;" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i><span></span> Cancel </a>
                            </div><br/><br/>
                            <?php echo form_close(); ?>
                            <!--Do not place contents below this line-->	
                            <br/><br/>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <?php $this->load->view('includes/js'); ?>
        <script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/user_create_edit.js'); ?>" type="text/javascript"></script>
            <!--<script type="text/javascript" src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>-->

        <!-- End of file edit_user.php 
                   Location: .configuration/users/edit_user.php -->
