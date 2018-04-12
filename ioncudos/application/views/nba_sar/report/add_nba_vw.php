<?php
/**
 * Description          :	View for add NBA SAR report.
 * Created              :	3-8-2015
 * Author               :
 * Modification History :
 * Date	                        Modified by                      Description
 * 10-8-2015                    Jevi V. G.              Added file headers, public function headers, indentations & comments.
  --------------------------------------------------------------------------------------------------------------- */
?>
<html lang="en">
        <!--head here -->
        <?php $this->load->view('includes/head'); ?>
        <body data-spy="scroll" data-target=".bs-docs-sidebar">
                <!--branding here-->
                <?php $this->load->view('includes/branding'); ?>
                <!-- Navbar here -->
                <?php $this->load->view('includes/navbar'); ?> 
                <div class="container-fluid">
                        <div class="row-fluid">
                                <!--sidenav.php-->
                                <?php $this->load->view('includes/sidenav_6'); ?>
                                <div class="span10">
                                        <!-- Contents -->
                                        <div class="bs-docs-example">
                                                <?php
                                                $select_dept_options[''] = 'Select Department';
                                                
                                                if (!empty($dept_list)) {
                                                        foreach ($dept_list as $list) {
                                                                $select_dept_options[$list['dept_id']] = $list['dept_name'];
                                                        }
                                                }
                                                $select_program_options[''] = 'Select Program';
                                                
                                                if (!empty($progaram_list)) {
                                                        foreach ($progaram_list as $program_list_data) {
                                                                $select_program_options[$program_list_data['pgm_id']] = $program_list_data['pgm_title'];
                                                        }
                                                }
                                                ?>
                                                <div class="row-fluid">
                                                        <div class="span12">
                                                                <div class="navbar-inner-custom">Add NBA - SAR Details</div>
                                                                <form class=" form-horizontal" method="POST" id="add_form" name="add_form" action="<?php echo base_url('nba_sar/nba_list/add_details'); ?>">
                                                                        <div class="control-group">
                                                                                <label class="control-label">Department : <font color="red">*</font></label>
                                                                                <div class="controls">
                                                                                        <?php
                                                                                        echo form_dropdown('dept_id', $select_dept_options, '', 'id="dept_id" class="required input-xlarge" autofocus = "autofocus"');
                                                                                        ?>	
                                                                                </div>
                                                                        </div>
                                                                        <div class="control-group">
                                                                                <label class="control-label">Program : <font color="red">*</font></label>
                                                                                <div class="controls">
                                                                                        <?php
                                                                                        echo form_dropdown('program_list', $select_program_options, '', ' id="program_list" class="required input-xlarge" autofocus = "autofocus"');
                                                                                        ?>
                                                                                        <input type="hidden" name="program_type" id="program_type"/>
                                                                                </div>
                                                                        </div>
                                                                        <div class="pull-right">
                                                                                <button class="unique btn btn-primary" type=""><i class="icon-file icon-white"></i><span></span> Save</button>
                                                                                <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i><span></span> Reset</button>
                                                                                <a href="<?php echo base_url('nba_sar/nba_list'); ?>"><span class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span>Cancel</span></a>
                                                                        </div>
                                                                </form>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <!---place footer.php here -->
                <?php $this->load->view('includes/footer'); ?> 
                <!---place js.php here -->
                <?php $this->load->view('includes/js'); ?>
        </body>
        <script type="text/javascript" src="<?php echo base_url('assets/nba_js/nba_list.js') ?>"></script>
</html>
<!-- End of file add_nba_vw.php 
        Location: .nba_sar/report/add_nba_vw.php -->