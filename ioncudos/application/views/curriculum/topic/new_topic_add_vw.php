<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic Add view page, provides the fecility to Add the Topic Contents.
 * Modification History:-
 * Date							Modified By								Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
 * 1-04-2015                    Jevi V G                                Added delivery method feature 
 * 05-01-2016			Shayista Mulla				Added loading image.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>
<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); ?> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.noty.css'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/noty_theme_default.css'); ?>" media="screen" />
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents-->
            <section id="contents">
                <div class="bs-docs-example fixed-height" >
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add <?php echo $this->lang->line('entity_topic_singular'); ?>
                        </div>
                        <div><font color="red"><?php echo validation_errors(); ?></font></div>
                    </div>	               						
                    <div class="span12">
                        <div class="span4">
                            <b> Curriculum : <span style="color:blue;font-size:12px;"><?php echo $crclm; ?></span></b>
                        </div>
                        <div class="span3">
                            <b>   Term : <span style="color:blue;font-size:12px;"><?php echo $term; ?></span></b>
                        </div>
                        <div class="span4">
                            <b> Course : <span style="color:blue;font-size:12px;"><?php echo $course; ?></span></b>
                        </div>
                        <!--<div class="span3">
                                Unit : <span style="color:blue;font-size:12px;"><?php echo $unit_nm; ?></span>
                        </div>-->
                    </div><br /><br /><br/><br/>
                    <table class="table table-bordered table-hover" id="example_topic_list" aria-describedby="example_info">
                        <thead>
                            <tr role="row">
                                <th class="header headerSortDown" width="16%" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Unit</th>
                                <th class="header headerSortDown" width="16%" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Code</th>
                                <th class="header headerSortDown" width="16%" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo "Sl No. & " . $this->lang->line('entity_topic_singular'); ?> Title</th>
                                <th class="header headerSortDown" width="40%" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Content</th>
                                <th class="header headerSortDown" width="10%" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Hours</th>
                                <th class="header headerSortDown" width="20%" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" ><?php echo $this->lang->line('entity_topic_singular'); ?> Delivery Methods</th>
                                <th class="header" width="4%" role="columnheader" tabindex="0" aria-controls="example"align="center">Edit</th>
                                <th class="header " width="5%" role="columnheader" tabindex="0" aria-controls="example" align="center" >Delete</th>												 				

                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                        </tbody>
                    </table>	
                    <br/><br/>

                    <form class="form-horizontal_new tab1" method="POST" id="topic_add_form"  name="topic_add_form">    
                        <div class="tab1">
                            <div class="row-fluid ">
                                <div class="span12">
                                         <div class="span5">
                                            <div class="control-group">
                                                <label class="control-label" for="topic_title" style=""> Topic Units <font color='red'>*</font>:</label>
                                                <div class="controls">
                                                   &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; 
                                                    <select name="units_list" id="units_list" class="input-medium required">
                                                        <option value="">Select Unit</option>
                                                        <?php
                                                            foreach($unit_list as $unit){
                                                                echo '<option value="'.$unit['t_unit_id'].'">'.$unit['t_unit_name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span5">
                                            <div class="control-group">
                                                <label class="control-label" for="topic_title" style="width:30%;"><?php echo "Sl No. & " . $this->lang->line('entity_topic_singular'); ?> Title <font color='red'>*</font>:</label>
                                                <div class="controls">
                                                    <input placeholder="Sl No."type="text" class="input-small required numeric" name="slno" id="slno" style="width:33px;" maxlength="3"/> - <?php echo form_input($topictitle); ?>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                <div class="span12" style="margin: 0px;">
                                        <div class="span5">
                                            <div class="control-group">
                                                <label class="control-label" for="topic_hours"> Duration in Hrs <font color='red'>*</font>:</label>
                                                <div class="controls">
                                                    &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
                                                    <?php echo form_input($topic_hours); ?>                                               
                                                </div>

                                            </div>
                                        </div>
                                        <div class="span5">
                                            <div class="control-group">
                                                <label class="control-label" for="topic_hours">Delivery Method:</label>
                                                <div class="controls" id="dm_select" >
                                                    <select multiple="multiple" name="delivery_method_1[]" id="delivery_method_1" class="dm_list_data delivery_method">
                                                        <?php echo $delivery_method_options; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>      
                                    </div>
                                <div class="control-group">
                                    <br/><label class="control-label" for="topic_content" style="width:12%"><?php echo $this->lang->line('entity_topic_singular'); ?> Content<font color='red'>*</font>:</label>
                                    <div class="controls">
                                        <?php echo form_textarea($topic_content); ?>
                                        <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                    </div>
                                </div>							
                            </div>
                        </div>
                        <div id="topic_insert">
                        </div>
                        <?php echo form_input($crclm_id); ?>
                        <?php echo form_input($term_id); ?>
                        <?php echo form_input($course_id); ?>
                        <?php //echo form_input($unit_id); ?>
                        <input type="hidden" id="counter" name="counter" value="1" />  
                        <input type="hidden" id="topic_id_data" name="topic_id_data" value="" />		

                        </fieldset>
                    </form>
                    <div class="pull-right">

                        <button type="button" id="submit" class="btn btn-primary topic_save"><i class="icon-file icon-white"></i> Save</button>
                        <button type="button" id="update_topic" class="btn btn-primary "><i class="icon-file icon-white"></i> Update</button>
                        <button type="reset" id="reset" class="btn btn-info" ><i class="icon-refresh icon-white"></i> Reset</button>
                        <a href="<?php echo base_url('curriculum/topic'); ?>" id="" class="btn btn-danger"><i class="icon-remove icon-white"></i> Close</a>
                    </div><br/><br/>
                </div>

                <!--Modal-->
                <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Delete Confirmation 
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_topic_data();"><i class="icon-ok icon-white"></i> Ok</button>
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                    </div>
                </div>
                <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-header">
                        <div class="navbar-inner-custom">
                            Warning
                        </div>
                    </div>
                    <div class="modal-body">
                        <p>You cannot delete this <?php echo $this->lang->line('entity_topic_singular'); ?>, as there are <?php echo $this->lang->line('entity_tlo'); ?> defined.</p>

                        <p>Delete all <?php echo $this->lang->line('entity_tlo'); ?>, then you will be able to delete this <?php echo $this->lang->line('entity_topic_singular'); ?>.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                    </div>
                </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.dataTables.rowGrouping.js'); ?>"></script>
    <script>
        var entity_topic = "<?php echo $this->lang->line('entity_topic_singular'); ?>";

    </script>
    <script>
        var delivery_method_options = "<?php echo $delivery_method_options; ?>";
    </script> 
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/topic.js'); ?> "></script>
    <script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?> "></script>
    <script type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/jquery.noty.js'); ?>"></script>
