<?php
/**
 * Description	:	Course Type add page will allow the admin to add unique course type(s) such 
  as basic, elective, open elective etc.
 * 					
 * Created		:	25-03-2013
 *
 * Author		:	
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 * 26-08-2013		   Arihant Prasad			File header, indentation, comments and variable 
  naming.
 * 27-03-2014		   Jevi V. G				Added description field for course_type		
 * 29-09-2014			Jyoti					Added Weightage section for course_type
 * 29-10-2014		   Shayista Mulla			Added curriculum component name dropdown list 
 * 30-03-2016               Shayista Mulla                      Added character counter to textarea.
  ------------------------------------------------------------------------------------------------- */
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
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add Course Type
                        </div>
                    </div>	
                    <br>
                    <form  class="form-horizontal" method="POST" id="form" name="form" action= "<?php echo base_url('configuration/course_type/course_insert_record'); ?>">
                        <div class="row-fluid">
                            <div class="span5">
                                <div class="control-group">
                                    <p class="control-label" for="crclm_component_name">Curriculum Component Name:<font color="red">*</font></p>
                                    <div class="controls" >
                                        <select id="crclm_component_name" name="crclm_component_name" autofocus = "autofocus" class="span12">
                                            <option value=""> Select Curriculum Component</option>
                                            <?php foreach ($crclm_component_name_data as $list_item): ?>
                                                <option value="<?php echo $list_item['cc_id']; ?>"> <?php echo $list_item['crclm_component_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select></div>

                                </div>
                            </div>
							<div class="span5">
                                <div class="control-group">
                                    <p class="control-label" for="crs_type_name">Course Type Name:<font color="red"> * </font>
                                    </p>
                                    <div class="controls">
                                        <?php echo form_input($crs_type_name); ?>

                                    </div>

                                </div>
                            </div>

                            <div class="span2" style="visibility:hidden;">
                                <div class="control-group">
                                    <p class="checkbox inline">
                                        <input id="toggleElement" type="checkbox" name="toggle" onchange="setToggleStatusValues()">
                                        Import option.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="control-group">
                            <p class="control-label" for="crs_type_description">Description:</p>
                            <div class="controls">
                                <?php echo form_textarea($crs_type_description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="import"></p>
                            <div class="controls">
                                <?php echo form_input($import, 'id="import"'); ?>
                            </div>
                        </div>                  
                        <!-- Modal to display the uniqueness message -->
                        <div id="myModal_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_warning" data-backdrop="static" data-keyboard="true">                          
                            <div class="modal-header">                            
                                <div class="navbar-inner-custom">
                                    Uniqueness Warning 
                                </div>                              
                            </div>

                            <div class="modal-body" id="comments">
                                <p>This Course Type Name already exists. </p>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary close1" data-dismiss="modal"><i class="icon-ok icon-white"></i><span></span> Ok </button>
                            </div>
                        </div>

                        <div class="pull-right">       
                            <button class="submit btn btn-primary" type="submit"><i class="icon-file icon-white"></i><span></span> Save </button>
                            <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i><span></span> Reset </button>
                    </form>
                    <a href= "<?php echo base_url('configuration/course_type'); ?>"  class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Cancel </a>

                </div>
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
<script>
    var cia_language = "<?php echo$this->lang->line('entity_cie'); ?>";
    var tee_language = "<?php echo $this->lang->line('entity_see'); ?>";
</script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/course_type_add_edit.js'); ?>" type="text/javascript"></script>

<!-- End of file course_add_vw.php 
     Location: .configuration/standards/course_type/course_add_vw.php -->
