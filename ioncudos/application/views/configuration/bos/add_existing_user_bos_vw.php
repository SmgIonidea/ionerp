<?php
/**
 * Description		:	Add Existing User View for BoS Module.
 * Created		:	25-03-2013. 
 * Modification History :
 * Date				Modified By			Description
 * 23-08-2013		     Abhinay B.Angadi		Added file headers, indentations.
 * 30-08-2013		     Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
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
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->	
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Existing User as Board of Studies(BoS) Member
                        </div>
                    </div>
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'create_user');
                    echo form_open("configuration/bos/insert_existing_bos_user", $attributes);
                    ?>				
                    <div class="control-group">
                        <p class="control-label" for="user_dept_id">Department: <font color='red'>*</font></p>
                        <div class="controls">
                            <?php
                            foreach ($department as $listitem1) {
                                $select_options1[$listitem1['dept_id']] = $listitem1['dept_name']; //group name column index
                            }
                            echo form_dropdown('user_dept_id', array('' => 'Select Department') + $select_options1, set_value('dept_id', '0'), 'class="required target" autofocus = "autofocus" ');
                            ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <p class="control-label" for="user_dept_id">Staff Name: <font color='red'>*</font></p>
                        <div class="controls">
                            <select name="id" class="required" id="username">
                                <option value="">Select User</option>
                            </select>
                        </div>
                    </div>	
                    <div class="control-group">
                        <p class="control-label" for="bos_dept_id">BoS for: <font color='red'>*</font></p>
                        <div class="controls">
                            <?php
                            foreach ($department as $listitem1) {
                                $select_options1[$listitem1['dept_id']] = $listitem1['dept_name']; //group name column index
                            }
                            echo form_dropdown('bos_dept_id', array('' => 'Select Department') + $select_options1, set_value('dept_id', '0'), 'class="required "');
                            ?>
                        </div>
                    </div>
                    <div class="pull-right">
                        <button type="submit"  class="btn btn-primary" ><i class="icon-file icon-white"></i> Save</button>
                        <a href="<?php echo base_url('configuration/bos'); ?>" class="btn btn-danger dropdown-toggle" ><i class="icon-remove icon-white"></i><span></span> Cancel</a>
                        <!--<a href = "#myModal" type="submit" class="btn btn-primary icon-plus-sign icon-white" data-toggle="modal" ><span></span> Save </a>-->
                    </div>	
                    <br><br><br><br><br><br><br><br><br><br><br>

                    <div id="bos_success" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Add Existing BoS User Status 
                            </div>           
                        </div>
                        <div class="modal-body">
                            <p> BoS Member Added Successfully.</p>
                        </div>
                        <div class="modal-footer">
                            <a href="<?php echo base_url('configuration/bos'); ?>" class="btn btn-primary dropdown-toggle"><i class="icon-ok icon-white"></i><span></span> Ok </a>
                        </div>
                    </div>

                    <div id="bos_retry" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Add Existing BoS User Status 
                            </div>           
                        </div>
                        <div class="modal-body">
                            <p> This member already exist in BoS members group. </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </section>
            <!--Do not place contents below this line-->	
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/bos.js'); ?>" type="text/javascript"></script>