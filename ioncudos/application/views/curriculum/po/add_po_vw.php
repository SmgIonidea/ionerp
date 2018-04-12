<?php
/**
 * Description	:	To display, add and edit Program Outcomes

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 21-10-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
 * 04-01-2015		   Shayista Mulla 			Added loading image and cookie.
  ---------------------------------------------------------------------------------------------- */
?>
<!--head here -->
<?php
$this->load->view('includes/head');
?>
<!--branding here-->
<?php
//var_dump($ga_list_new);
$this->load->view('includes/branding');
?>
<!-- Navbar here -->
<?php
$this->load->view('includes/navbar');
?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_2'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Add <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>
                        </div>
                    </div>

                    <input type="hidden" id="crclm_id" name="crclm_id" value="<?php echo $curriculum_id; ?>"/> 
                    <div class="row-fluid span12" id="accreditation_type">

                        <div class="span3 pull-left">
                            <p><h5 style="font-size:12px;"> Curriculum: <?php echo $crclm_name; ?><h5></p>
                                    </div>
                                    <div class="span3 row_fluid pull-right form-horizontal">
                                        <p class="control-label" for="custom">CUSTOM</p>&nbsp;&nbsp;
                                        <input type="radio" checked="checked" class="accreditation" name="acridit" id="custom">
                                    </div>
                                    <?php foreach ($accredit as $accreditation): {
                                            ?>
                                            <div class="span3 row_fluid pull-right form-horizontal">
                                                <p class="control-label" for="abet"><?php echo $accreditation['atype_name']; ?></p>&nbsp;&nbsp;
                                                <input type="radio" name="acridit" id="<?php echo $accreditation['atype_name']; ?>" class="accreditation" abbr="<?php echo $accreditation['atype_id']; ?>">

                                            </div>
                                            <?php
                                        }
                                    endforeach;
                                    ?>
                                    </div>

                                    <?php
                                    $attributes = array('class' => 'form-inline', 'method' => 'POST', 'id' => 'add_form_id');
                                    echo form_open("curriculum/po/insert_po", $attributes);
                                    ?>	
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <input type="hidden" id="po_state" name="po_state" value="<?php echo $po_state_id; ?>"/>
                                            <div class="bs-docs-example">
                                                <div id="add_me">								
                                                    <div class="">
                                                        <div class="span12">
                                                            <div class="control-group controls span4">
                                                                <label class="control-label" for="po_name_1"> <?php echo $this->lang->line('so'); ?> Reference: <font color='red'> * </font></label>
                                                                <?php echo form_input($po_name); ?>
                                                            </div>
                                                            <div class=" control-group controls span2">
                                                                <label class="control-label" for="po_name_1"> <?php echo $this->lang->line('entity_pso'); ?> Flag : <font color='red'>  &nbsp;</font>
                                                                </label>
                                                                <?php echo form_checkbox($pso_custom); ?>
                                                            </div>
                                                            <div class="  controls span3">
                                                                <select id="po_types1" name="po_types1" class="required po_types input-medium">
                                                                    <option value=""><?php echo $this->lang->line('so'); ?> Types</option>
                                                                    <?php foreach ($po_types['po_types_id_names'] as $po_type): ?>
                                                                        <option value="<?php echo $po_type['po_type_id']; ?>"><?php echo $po_type['po_type_name']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <span for="po_types1" generated="true" class="help-inline font_color"></span>
                                                            </div>

                                                            <div class="control-group controls span3 pull-right">

                                                                <select name="ga_data1[]" id="ga_data1" class="ga_data input-medium" multiple="multiple">
                                                                    <?php foreach ($ga_list_new as $ga_data) { ?>
                                                                        <option value="<?php echo $ga_data['ga_id']; ?>" title="<?php echo $ga_data['ga_statement']; ?>"><?php echo 'GA - ' . $ga_data['ga_reference']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                        </div>

                                                        <div class=" control-group controls">
                                                            <label class="control-label" for="error" style="margin-top:0px"> <?php echo $this->lang->line('so'); ?> Statement: <font color='red'> * </font></label>
                                                            <?php echo form_textarea($po_statement); ?>
                                                            <br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <span id="char_span_support" class="margin-left5">0 of 2000</span>
                                                        </div>

                                                        <span id="error"></span>
                                                    </div> 											
                                                </div>



                                            </div>
                                            <div>
                                                <div id="add_before">
                                                </div>
                                            </div>	

                                            <input type="hidden" name="counter" id="counter" value="1"/>
                                            <input type="hidden" name="po_type_counter" id="po_type_counter" value="1"/>

                                            <?php echo form_input($crclm_id); ?>
                                        </div> <!--End of row-fluid-->
                                    </div><!--End of span12-->
                                    <br>
                                    <div class="pull-right row">
                                        <a id="add_field" class="btn btn-primary global cursor_pointer"><i class="icon-plus-sign icon-white"></i> Add More <?php echo $this->lang->line('so'); ?> </a>
                                        <button type="submit"  class="add_form_submit btn btn-primary"><i class="icon-file icon-white"></i> Save</button>
                                        <a href="<?php echo base_url('curriculum/po'); ?>" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i> Cancel </a>	
                                    </div><br>
                                    </div><br><!--End of row-fluid-->

                                    <?php echo form_close(); ?>	
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
                                    <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po.js'); ?>" type="text/javascript"></script>
                                    <script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
                                    <script>
                                        var student_outcome = "<?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>";
                                            var student_outcome_short = "<?php echo $this->lang->line('student_outcome'); ?>";
                                            var so = "<?php echo $this->lang->line('so'); ?>";
                                            var student_outcomes = "<?php echo $this->lang->line('student_outcomes_full'); ?><?php echo $this->lang->line('student_outcomes'); ?>";
                                            var student_outcomes_short = "<?php echo $this->lang->line('student_outcomes'); ?>";
                                            var sos = "<?php echo $this->lang->line('sos'); ?>";
                                    </script>
