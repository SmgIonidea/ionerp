<?php
/**
 * Description          :	To display, add and edit Program Outcomes

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
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Edit <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcome'); ?>
                        </div>
                    </div>

                    <p><h5 style="font-size:12px;"> Curriculum: <?php echo $crclm_name; ?><h5></p>
                            <?php
                            $attributes = array('class' => 'form-horizontal', 'method' => 'POST', 'id' => 'add_po');
                            echo form_open("curriculum/po/update_po", $attributes);
                            ?>
                            <div class="row-fluid">
                                <div class="span12">

                                    <div class="row-fluid">
                                        <div>
                                            <div class="bs-docs-example">
                                                <div class="control-group">
                                                    <div class="control-group span12">
                                                        <div>
                                                            <label class="control-label"> BOS Comments: &nbsp;&nbsp;&nbsp;</label>
                                                            <textarea class="required bos_comment po_textarea_size" style="margin: 0px; height: 60px;" name="bos_comment" rows="2" id="bos_comment" type="text" disabled="disabled"><?php
                                                                $i = 1;
                                                                foreach ($bos_comment as $bos_comment) {
                                                                    ?><?php echo $i++ . '.' . $bos_comment['cmt_statement']; ?><?php } ?>
                                                            </textarea>
                                                        </div><br>

                                                        <div class="controls span3" style="font-size:12px;">
                                                            <label class="control-label" for="po_name_1"> <?php echo $this->lang->line('so'); ?> Reference: <font color='red'> * &nbsp;</font>
                                                            </label>
															<?php echo form_input($po_name); ?>

                                                        </div>
                                                        <div class="controls span3">
                                                            <label class="control-label" for="po_name_1"> <?php echo $this->lang->line('entity_pso'); ?> Flag : <font color='red'>  &nbsp;</font>
                                                            </label>
															<?php echo form_checkbox($pso_cb); ?>
                                                        </div>
                                                        <div class="controls span3">
                                                            </label>
                                                        <?php echo form_dropdown('po_type_id', $po_types, $po_type_selected, "class='po_types input-medium'"); ?>
                                                        </div>
														<?php ?>
                                                        <div class="controls span3">

															<?php echo form_multiselect('ga_data[]', $ga_list, $ga_map_list, "class='ga_data_val input-medium'"); ?>
                                                        </div>
                                                    </div><br>

                                                    <div class="">
                                                        <label class="control-label" for="error" style="margin-top:0px"> <?php echo $this->lang->line('so'); ?> Statement: <font color='red'> * &nbsp;&nbsp;&nbsp;</font>
                                                        </label>
                                                        <textarea class="required clo_stmt po_textarea_size" maxlength="2000" style="margin: 0px; height: 50px;" name="po_statement" autofocus="autofocus" rows="2" id="po_statement_1" type="text"><?php echo $po_data['0']['po_statement']; ?>
                                                        </textarea><br/><label class="control-group controls" for="error" style="margin-top:0px"><span id="char_span_support" class="margin-left5">0 of 2000</span></label>
                                                    </div>
                                                    <span id="error"></span>
                                                </div>
                                                <!----------------------------->
                                            </div>
                                        </div> <!-- Ends here-->
                                    </div> <!--End of row-fluid-->
                                </div><!--End of span12-->
                            </div><br><!--End of row-fluid-->

                            <div class="pull-right row">
                                <button type="submit" class="btn btn-primary update"><i class="icon-file icon-white"></i> Update</button>
                                <a href="<?php echo base_url('curriculum/po'); ?>"><button type="button" class="btn btn-danger dropdown-toggle"><i class="icon-remove icon-white"></i> Cancel </button></a>
                            </div><br>
                            <?php echo form_input($po_id); ?>
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
                            <script src="<?php echo base_url('twitterbootstrap/js/bootstrap_multiselect_dropdown.js'); ?>" type="text/javascript"></script>
                            <script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po_edit.js'); ?>" type="text/javascript"></script>
