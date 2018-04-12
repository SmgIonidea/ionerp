<?php
/**
 * Description	:	It is to display the complete report of CO.

 * Created		:	May 26th, 2015

 * Author		:	Mritunjay B S 

 *
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
                <?php $this->load->view('includes/sidenav_5'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/clo_po_map_report/export_pdf'); ?>">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Overall Course Outcome(CO) Attainment
                                    </div>
									<div class="pull-right">
										<!--<a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export </a>-->
									</div>
                                </div>
								
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid" style="width:100%; overflow:auto;">
											<table style="width:100%; overflow:auto;">
												<tr>
													<td>
														<p>
															Curriculum <font color="red"> * </font>:
															<select size="1" id="crclm" name="crclm" autofocus = "autofocus" >
																<option value="Curriculum" selected> Select Curriculum </option>
																<?php foreach ($crclm_list as $list_item): ?>
																	<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
																<?php endforeach; ?>
															</select>
														</p>
													</td>
													<td>
														<p>
															Term <font color="red"> * </font>:
															<select size="1" id="term" name="term" aria-controls="example" >
																<option value="Term" selected> Select Term </option>
															</select>
														</p>
													</td>
													<td>
														<p>
															Course <font color="red"> * </font>:
															<select size="1" id="course" name="course" aria-controls="example" >
																<option value="Course" selected> Select Course </option>
															</select>
														</p>
													</td>
												</tr>
											</table>
											
                                            <div class="bs-docs-example " >
                                                <div id="table_view" style="overflow:auto;">
                                                </div>
                                            </div>
                                            <br>
											<div id="po_modal_view" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false" style="width:1000px; margin-left:-225px;">
												<div class="modal-header">
													<div class="navbar">
														<div class="navbar-inner-custom" id="change_navbar">
															<?php echo $this->lang->line('so'); ?> Data
														</div>
													</div>
												</div>
												<div id="crs_name">
												</div>
												<div class="modal-body" id="po_data_modal_body">
												</div>
												<div class="modal-footer">
													<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
												</div>
											</div>
                                            <div class="pull-right">
                                                <!--<a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export </a>-->
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
	    <script>
    		var entity_tlo = "<?php echo $this->lang->line('entity_tlo_singular'); ?>";
	    </script>		
	<script src="<?php echo base_url('twitterbootstrap/js/custom/assessment_attainment/co_report.js'); ?>" type="text/javascript"> </script>

