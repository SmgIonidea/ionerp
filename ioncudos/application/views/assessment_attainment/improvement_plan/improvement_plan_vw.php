<?php
/**
 * Description	:	Improvement Plan
 * 					
 * Created		:	17-08-2015
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
  ------------------------------------------------------------------------------------------------- */
?>

<!--head here -->
    <!-- /TinyMCE -->
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />

	
    <?php $this->load->view('includes/head'); ?>
        <!--branding here-->
		
		<script>

		
		</script>
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_5'); ?>
                <div class="span10">
										
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Improvement Plan
                                </div>
                            </div>

                            <form method="POST">
                                <?php echo form_input($sip_id); ?>
                                <?php echo form_input($entity_id); ?>
                                <?php echo form_input($crclm_id); ?>
                                <?php echo form_input($term_id); ?>
                                <?php echo form_input($crs_id); ?>
                                <?php echo form_input($qpType_id); ?>
                                <?php echo form_input($qpd_id); ?>
                                <?php echo form_input($flag_val); ?>
								
								<!-- Tiny MCE Code -->
                                <!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
                                <div class="controls">
                                    <p> Problem Statement : </p>
                                </div>
                                <?php echo form_textarea($problem_stmt); ?>
                                <br>
								
								<div class="controls">
                                    <p> Root Cause : </p>
                                </div>
                                <?php echo form_textarea($root_cause); ?>
                                <br>
								
								<div class="controls">
                                    <p> Corrective Action : </p>
                                </div>
                                <?php echo form_textarea($corrective_action); ?>
                                <br>
								
								<div class="controls">
                                    <p> Action Plan : </p>
                                </div>
                                <?php echo form_textarea($action_plan); ?> 
								<?php echo form_input($student_usn); ?>
                                
								
								
								<br>
                                <div class="pull-right">
                                    <a href="#" id="update" class="btn btn-primary" data-toggle="modal"><i class="icon-file icon-white"></i> Save </a>
									<a href= "<?php echo base_url('assessment_attainment/student_threshold'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel</b></a>
                                </div>
                                <br><br>

                                <!-- Modal to display the confirmation message on save -->
                                <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                    <div class="modal-header">
                                        <div class="navbar-inner-custom">
                                            Confirmation Message 
                                        </div>
									</div>

									<div class="modal-body">
										<p></p>
									</div>
									
									<div class="modal-footer">
										<a href="<?php echo base_url('assessment_attainment/student_threshold'); ?>" class="btn btn-primary dropdown-toggle"><i class="icon-ok icon-white"></i><span></span> Ok </a>
									</div>
                                </div>
                            </form>						
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
		
    <script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/improvement_plan.js'); ?>" type="text/javascript"> </script>
	<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>

<!-- End of file improvement_plan_vw.php 
Location: .assessment_attainment/improvement_plan/improvement_plan_vw.php -->