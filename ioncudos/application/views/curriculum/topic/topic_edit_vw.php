<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Topic Edit view page, provides the fecility to Update the Topic Contents.
 * Modification History:-
 * Date				Modified By				Description
 * 05-09-2013                   Mritunjay B S                           Added file headers, function headers & comments. 
 * 2-04-2015                    Jevi V G                                Added delivery method feature 
 * 08-05-2015			Abhinay B Angadi			Included Delivery methods under edit view
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
                            Edit <?php echo $this->lang->line('entity_topic_singular'); ?>
                        </div>
                        <div><font color="red"><?php echo validation_errors(); ?></font></div>
                    </div>	

                    <!-- Form Name -->
                    <form name="topic_edit_form" id="topic_edit_form" class="form-horizontal form-inline" method="POST" action="<?php echo base_url('curriculum/topicadd/edit_topic') . '/' . $topic_update[0]['topic_id'] . '/' . $topic_update[0]['course_id'] . '/' . 1; ?>" >

                        <div></div>
                        <div class="span12">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="pgm_title">Curriculum :</label>
                                    <div class="controls">
                                        <b><font color="blue"><?php echo $curriculum[0]['crclm_name']; ?></font></b>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="pgm_title">Term :</label>
                                    <div class="controls">
                                        <b><font color="blue"><?php echo $term[0]['term_name']; ?></font></b>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="span12">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="pgm_title">Course :</label>
                                    <div class="controls">
                                        <b><font color="blue"><?php echo $course[0]['crs_title']; ?> (<?php echo $course[0]['crs_code']; ?>)</font></b>
                                    </div>
                                </div>
                            </div> 

                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="pgm_title"><?php echo $this->lang->line('entity_unit'); ?> :</label>
                                    <div class="controls">
                                        <select id="topic_unit" name="topic_unit" class="input-medium required">
                                            <option value>Select <?php echo $this->lang->line('entity_unit'); ?></option>
                                            <?php foreach ($unit_data as $unit) { ?>
                                                <option value="<?php echo $unit['t_unit_id']; ?>" <?php if ($topic_unit_id == $unit['t_unit_id']) { ?> selected="selected" <?php } ?> > <?php echo $unit['t_unit_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="span12">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="topic_title1"><?php echo 'Sl No. & '.$this->lang->line('entity_topic_singular'); ?> Title <font color='red'>*</font></label>
                                    <div class="controls">
                                        <?php echo form_input($slno); ?> - <?php echo form_input($topictitle); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="topic_hours1">Duration in Hours <font color='red'>*</font></label>

                                    <div class="controls">
                                        <?php echo form_input($topic_hours); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="span12 add_me" id="topic">
                            <div class="control-group">
                                <label class="control-label" for="topic_content1"><?php echo $this->lang->line('entity_topic_singular'); ?> Content<font color='red'>*</font></label>
                                <div class="controls">
                                    <?php echo form_textarea($topic_content); ?>
                                    <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                                </div>
                            </div>
                            <div class="control-group">
                                <p class="control-label" for="delivery_method">Delivery Method: </p>
                                <div class="controls" id="dm_select" >
                                    <?php echo form_dropdown('delivery_method[]', $delivery_methods, $delivery_methods_selected, 'class ="required dm_list_data example-getting-started " multiple="multiple" id="delivery_method"'); ?> 
                                </div>
                            </div>
                        </div>									                    
                        </br></br>
                        <div class="pull-right">
                            <input type="hidden" id="counter" name="counter" value="1" />
                            <button  value="Update" id="update" class="btn btn-primary update"><i class="icon-file icon-white"></i>&nbsp;Update</button>
                            <a class="btn btn-danger" id="cancel" href="<?php echo base_url('curriculum/topic'); ?>"><i class="icon-remove icon-white"></i>Cancel</a>
                        </div>
                        </fieldset>
                    </form>				
                </div>
        </div>
    </div>
</div>
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script>
    var entity_topic = "<?php echo $this->lang->line('entity_tlo_singular'); ?>";
</script>
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/topic.js'); ?> "></script>
<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?> "></script> 
<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/bootstrap-multiselect.css'); ?>" media="screen" />
