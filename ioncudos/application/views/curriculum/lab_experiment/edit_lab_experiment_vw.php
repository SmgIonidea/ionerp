<?php
/**
 * Description	:	Lab experiment controller model and view

 * Created		:	March 24th, 2015

 * Author		:	 Jyoti Shetti

 * Modification History:
 *   Date                Modified By                         Description
 * 05-01-2016		Shayista Mulla			Added loading image.
  ---------------------------------------------------------------------------------------------- */
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
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <!--content goes here-->	
                <div class="bs-docs-example" >
                    <!--content goes here-->
                    <div class="row-fluid">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Edit Lab Experiment
                            </div>
                        </div> 
                        <form  class="form-horizontal" method="POST" id="lab_experiment_edit_form" name="lab_experiment_edit_form" action= "<?php echo base_url('curriculum/lab_experiment/update_lab_experiment'); ?>">
                            <div class="span12">
                                <div class="span4">
                                    Curriculum : <?php echo form_input($crclm_name); ?>
                                </div>
                                <div class="span4">
                                    Term : <?php echo form_input($term); ?>
                                </div>
                                <div class="span4">
                                    Course : <?php echo form_input($course); ?>
                                </div>
                            </div><br /><br />
                            <div class="span12">
                                <div class="span4">
                                    Category :<font color='red'>*</font>
                                    <?php
                                    foreach ($category_data as $listitem2) {
                                        $select_options2[$listitem2['mt_details_id']] = $listitem2['mt_details_name'];
                                    }
                                    echo form_dropdown('category_id', $select_options2, $category_value, 'class="input-medium required" id="category_id"');
                                    ?>
                                </div>
                            </div><br /><br /><br />
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Lab Experiment Details
                                </div>
                            </div> 
                            <div id="lab_expt_main_div_1" class="bs-docs-example" style="width:95%;height:100%;overflow:auto;" >
                                <div class="span12">
                                    <div class="span11">
                                        <div class="span12">
                                            <div class="span4" style="text-align:center;">
                                                Job / Expt No. :<font color="red">*</font> <?php echo form_input($expt_no); ?>
                                            </div>
                                            <div class="span4">
                                                No. of Sessions :<font color="red">*</font> <?php echo form_input($sessions); ?>
                                            </div>
                                            <div class="span4" style="padding-left:5%;">
                                                Marks :<font color="red">*</font> <?php echo form_input($marks); ?>
                                            </div>
                                        </div><br /><br />
                                        <div class="span12">
                                            <div class="span8">
                                                <div class="span3">Experiment / Job Details:<font color="red">*</font></div>  <?php echo form_textarea($expt); ?>
                                                <div class="span3"></div><br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                            </div>
                                            <div class="span4">
                                                Correlation : <?php echo form_textarea($correlation); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php echo form_input($topic_id); ?>
                            <br />

                            <div class='pull-right'>
                                <a id='lab_expt_update_update' class='btn btn-primary' href='#'><i class='icon-file icon-white'></i> Update </a>
                                <a href= "<?php echo base_url('curriculum/lab_experiment'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Close </a>
                            </div>

                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/lab_experiment.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
<!-- End of file add_lab_experiment_vw.php 
                        Location: .curriculum/lab_experiment/add_lab_experiment_vw.php -->
