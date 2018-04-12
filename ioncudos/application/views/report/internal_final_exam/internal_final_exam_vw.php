<?php
/**
 * Description	:	Generates Internal & Final Exam Report

 * Created		:	December 15th, 2015

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------------------------ */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar');
?> 
<div class="container-fluid">
    <div class="row-fluid">
        <!--sidenav.php-->
        <?php $this->load->view('includes/sidenav_3'); ?>
        <div class="span10"> 
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            <?php echo $this->lang->line('entity_cie_full'); ?> ( <?php echo $this->lang->line('entity_cie'); ?> ) , 
							<?php if($mte_flag == 1){ echo $this->lang->line('entity_mte_full');  ?> ( 
							<?php echo $this->lang->line('entity_mte'); ?> ) , <?php  } ?>  
							<?php echo $this->lang->line('entity_see_full'); ?> (
							<?php echo $this->lang->line('entity_see'); ?> ) Report
                        </div>
                    </div>

                    <form target="_blank" name="ia_tee_form" id="ia_tee_form" method="POST" action="<?php echo base_url('report/internal_final_exam/export'); ?>">
                        <input type="hidden" name="doc_type" id="doc_type">
                        <input type="hidden" name="pdf" id="pdf">

                        <div class="pull-right">
                            <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                        </div>

                        <div class="pull-right">
                            <a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                        </div>
                        <table style="width:90%">
                            <tr>
                                <td>
                                    Curriculum: <font color="red"> * </font><br>
                                    <select id="crclm" name="crclm" class="input-medium" autofocus = "autofocus" aria-controls="example" onChange = "fetch_term();">
                                        <option value="Curriculum" selected> Select Curriculum </option>
                                        <?php foreach ($curriculum as $list_item) { ?>
                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    Term: <font color="red"> * </font><br>
                                    <select id="term" name="term" class="input-medium" aria-controls="example" onChange="fetch_course();">
                                    </select>
                                </td>
                                <td>
                                    Course: <font color="red"> * </font><br>
                                    <select id="course" name="course" class="input-medium" aria-controls="example" onChange="fetch_type();">
                                    </select>
                                </td>
                                <td>
                                    Type: <font color="red"> * </font><br>
                                    <select id="ao_type_id" name="ao_type_id" class="input-medium" aria-controls="example">
                                        <option>Select Type</option>
                                    </select>
                                </td>
                                <td>
                                    <div id="occasion_div" style="display:none;">
                                        Occasion: <font color='red'> * </font><br>
                                        <select id="occasion" name="occasion" class="input-medium">	
                                            <option value="">Select Occasion</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <input type="hidden" name="word" id="word" />

                        <div class="exam_table_grid">
                            <!-- Pass the values to the lesson plan grid -->
                        </div>
                        <div id ="cia_error_msg">

                        </div>
                        <div class="pull-right">
                            <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                        </div>

                        <div class="pull-right">
                            <a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                        </div><br><br>

                        <!-- Modal to display the warning message - select all dropdown's -->
                        <div id="export_to_doc_warning" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="export_to_doc_warning" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class="navbar-inner-custom">
                                    Warning !!!
                                </div>
                            </div>

                            <div class="modal-body">
                                Select all the drop-downs.
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/internal_final_exam.js'); ?>" type="text/javascript"></script>
<script>
                                        var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
                                        var entity_see = "<?php echo $this->lang->line('entity_see'); ?>";
										var entity_mte = "<?php echo $this->lang->line('entity_mte'); ?>";
                                        var entity_cie_full = "<?php echo $this->lang->line('entity_cie_full'); ?>";
</script>	