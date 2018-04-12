<?php
/**
 * Description	:	Approved Program Outcome grid along with its corresponding Performance Indicators
					and Measures

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 30-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
 ----------------------------------------------------------------------------------------------*/
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
                <?php $this->load->view('includes/static_sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators (PIs)
                                </div>
                            </div>
                            <div>
                                <label> Curriculum:
                                    <select name="curriculum_list" id="curriculum_list" onChange = "static_select_curriculum();">
                                        <option value=""> Select Curriculum </option>
                                        <?php foreach ($curriculum_list as $curriculum): ?>
                                            <option value="<?php echo $curriculum['crclm_id']; ?>" > <?php echo $curriculum['crclm_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
								</label>
								<h4><center><b id="pi_measures_current_state"></b></center></h4>
                            </div>
							
							<input type="hidden" id="cloneCntr" value=0>
							<input id="item_msr" type="hidden" value = 0>
							
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:95px">
                                <table class="table table-bordered table-hover " id="example" aria-describedby="example_info" style="font-size: 14px;">
                                    <thead align = "center">
                                        <tr role="row">
                                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> Approved <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>) </th>
                                            <th class="header" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> Selected <?php echo $this->lang->line('outcome_element_plu_full'); ?> & Performance Indicators </th>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all" id="list_the_curriculum">

                                    </tbody>
                                </table>	
                            </div>
                            <div class="clear">
                            </div><br><br><br><br>

							<!-- Modal to display Performance Indicator and corresponding Measures of Program Outcome -->
                            <div id="myModal_po_pi_msr_list" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_po_pi_msr_list" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            <?php echo $this->lang->line('outcome_element_plu_full'); ?> and PIs for PO 
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div id="po_pi_msr_list"> 
									
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Close </button>
                                </div>
                            </div>
                    </section>
						</div>
				</div>
			</div>
		</div>
		<!---place footer.php here -->
		<?php $this->load->view('includes/footer'); ?> 
		<!---place js.php here -->
		<?php $this->load->view('includes/js'); ?>
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/pi_and_measures.js'); ?>" type="text/javascript"> </script>


<!-- End of file static_list_po_pi_msr_vw.php 
        Location: .curriculum/pi_measures/static_list_po_pi_msr_vw.php -->