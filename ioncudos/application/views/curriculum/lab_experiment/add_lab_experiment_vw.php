<?php
/**
 * Description	:	Lab experiment controller model and view

 * Created		:	March 24th, 2015

 * Author		:	 Jyoti Shetti

 * Modification History:
 *   Date                Modified By                         Description
 *   05-01-2016		Shayista Mulla			Added loading image.
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
        <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/tinymce/tinymce.min.js') ?>"></script>
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
                        <div class="navbar ">
                            <div class="navbar-inner-custom">
                                Add Lab Experiment
                            </div>
                        </div> 
                        <div class="span12 bs-docs-example">
                            <div class="span3">
                                Curriculum : <span style="color:blue;font-size:12px;"><?php echo $crclm_name; ?></span>
                            </div>
                            <div class="span2">
                                Term : <span style="color:blue;font-size:12px;"><?php echo $term; ?></span>
                            </div>
                            <div class="span4">
                                Course : <span style="color:blue;font-size:12px;"><?php echo $course; ?></span>
                            </div>
                            <div class="span3">
                                Category : <span style="color:blue;font-size:12px;"><?php echo $category; ?></span>
                            </div>
                        </div><br /><br /><br/><br/>

                        <!--<div class="navbar">
                                <div class="navbar-inner-custom">
                                        Lab Experiment Details
                                </div>
                        </div> 	-->		

                        <table class="table table-bordered table-hover" id="example_lab_list" aria-describedby="example_info">
                            <thead>
                                <tr role="row">
                                    <th class="header headerSortDown" width="85px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Job / Exp No.</th>
                                    <th class="header headerSortDown" width="350px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >No. of Sessions</th>
                                    <th class="header headerSortDown" width="120px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Marks</th>
                                    <th class="header headerSortDown" width="100px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Experiment / Job Details </th>
                                    <th class="header headerSortDown" width="100px" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Correlation</th>
                                    <th class="header" width="30px" role="columnheader" tabindex="0" aria-controls="example"align="center">Edit</th>
                                    <th class="header " width="40px" role="columnheader" tabindex="0" aria-controls="example" align="center" >Delete</th>												 				

                                </tr>
                            </thead>
                            <tbody role="alert" aria-live="polite" aria-relevant="all">
                            </tbody>
                        </table>										
                        <form  class="form-horizontal" method="POST" id="lab_experiment_add_form" name="lab_experiment_add_form" ">
                            <div id="lab_expt_main_div_1" class="tab1" style="" >
                                <div class="span12">
                                    <div class="span4" style="text-align:center;">
                                        <?php echo str_repeat("&nbsp;", 6); ?>	Job / Expt No. :<font color="red">*</font> <?php echo form_input($expt_no); ?>
                                    </div>
                                    <div class="span4" >
                                        No. of Sessions :<font color="red">*</font> <?php echo form_input($sessions); ?>
                                    </div>
                                    <div class="span4" style=" text-align:center;">
                                        Marks :<font color="red">*</font> <?php echo form_input($marks); ?>
                                    </div>
                                </div>
                                <div class="span12">
                                    <br/><div class="span8">
                                        <div class="span3">Experiment / Job Details :<font color="red">*</font></div> <?php echo form_textarea($expt); ?>
                                        <div class="span3"></div><br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>  
                                    </div>
                                    <div class="span4">
                                        Correlation : <?php echo form_textarea($correlation); ?>
                                    </div>
                                </div>
                            </div>
                            <div id="additional_lab_expt">

                            </div>
                            <br />
                            <div class='pull-right'>
                                <?php
                                echo form_input($crclm_id);
                                echo form_input($term_id);
                                echo form_input($crs_id);
                                echo form_input($category_id);
                                ?>
                                <input type ="hidden" name="topic_id" id ="topic_id" />
                                <input type='hidden' name='counter' id='counter' value='1' readonly>
                                <input type='hidden' name='lab_expt_counter' id='lab_expt_counter' value='1' readonly>

                            </div>
                            <div class='pull-right'>
                                    <!--<a id='add_more_lab_expt' class='btn btn-primary global' href='#'><i class='icon-plus-sign icon-white'></i> Add More Experiment </a>-->
                                <a id='lab_expt_save' class='btn btn-primary global' href='#'><i class='icon-file icon-white'></i> Save </a>
                                <a id='lab_expt_update' class='btn btn-primary' href='#'><i class='icon-file icon-white'></i> Update </a>
                                <a href="<?php echo base_url('curriculum/lab_experiment'); ?>" class="btn btn-danger"> <i class="icon-remove icon-white"></i><span></span> Cancel </a>
                            </div>

                            <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class=" navbar-inner-custom">
                                        Delete Lab Experiment 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p>Do you want to delete selected lab experiment?</p>
                                    <input type="hidden" value="" name="deleteId" id="deleteId" readonly />
                                </div>
                                <div class="modal-footer">
                                    <button class="delete_confirm btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>

                            </div>
								<input type="hidden" id="category_id_val" name ="category_id_val" />
                        </form>
                    </div>
                </div>
            </section>
            <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Delete Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Experiment ?</p> 
                    <p>It's associated <?php echo $this->lang->line('entity_tlo'); ?> will be deleted. If yes press on Ok button.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_experiment();"><i class="icon-ok icon-white"></i> Ok </button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel </button>
                </div>
            </div>
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
