<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
  will display related course. For each Course its related Assessment Occasions (AO)
  to Course Outcomes (CO) mapping grid will be displayed.

 * Created		:	Oct 27th, 2015

 * Author		:	Abhinay B.Angadi

 * Modification History:
 *   Date                     Modified By                         Description
 * 27-10-2015               Abhinay B.Angadi			File header, function headers, indentation 
  and comments.
 * 24-12-2015               Shayista Mulla			Added loading image and cookies.
 * 18-12-2016               Shayista Mulla                      Added Export button at the top of the page.    
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
	<?php $this->load->view('includes/sidenav_3'); ?>
        <div class="span10">
            <!-- Contents -->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/ao_clo_map_report/export_pdf'); ?>">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Assessment Occasions (AOs) to  <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) Mapped Report (Coursewise)
                            </div>
                        </div>
                        <div class="pull-right">
                            <a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export .pdf </a>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid" style="width:100%; overflow:auto;">
                                    <table style="width:100%; overflow:auto;">
                                        <tr>
                                            <td>
                                                <p>
                                                    Curriculum <font color="red"> * </font><br>
                                                    <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
                                                        <option value="Curriculum" selected> Select Curriculum </option>
							<?php foreach ($curriculum_result as $list_item): ?>
    							<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
							<?php endforeach; ?>
                                                    </select>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    Term <font color="red"> * </font><br>
                                                    <select size="1" id="term" name="term" aria-controls="example" onChange = "select_term_course();">
                                                        <option value="Term" selected> Select Term </option>
                                                    </select>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    Course <font color="red"> * </font><br>
                                                    <select size="1" id="course" name="course" aria-controls="example" onChange = "select_course();">
                                                        <option value="Course" selected> Select Course </option>
                                                    </select>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="bs-docs-example" id="ao_clo_view_id">
                                    </div>
                                    <br>
                                    <div class="pull-right">
                                        <a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export .pdf </a>
                                    </div>
                                    </form>
                                    <input type="hidden" name="pdf" id="pdf" />
                                </div>
                            </div>
                        </div>
                        <!--Do not place contents below this line-->
                        </section>			
                </div>					
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/report/ao_clo_map_report.js'); ?>" type="text/javascript"></script>

    <!-- End of file clo_po_map_report_vw.php 
                                    Location: .report/ao_clo_map/clo_po_map_report_vw.php -->
