<?php
/**
 * Description	:	Select Curriculum and then select the related term (semester) which
					will display related course. For each course related Course Learning 
					Outcomes (CLOs) and Program Outcomes (POs) are displayed for the guest user.

 * Created		:	April 29th, 2013

 * Author		:	 

 * Modification History:
 * 	Date                Modified By                			Description
  18/09/2013		  Arihant Prasad D			File header, function headers, indentation
												and comments.
  --------------------------------------------------------------------------------------------- */
?>
    <!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
        <!--branding here-->
        <?php
        $this->load->view('includes/branding');
        ?>

		 <!-- Navbar here -->
		<style>
			#header_scroll {
				position: fixed;
				width: 100%;
				top: 40%;
				right: 0;
				bottom: 0;
				left: 77%;
		   }
		</style>
		
        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 

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
                                    Mapping of Course Outcomes (COs) to <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Termwise
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div>
                                        <div class="row-fluid">
                                            
                                                <table>
												<tr>
												<td>												
												<label>
                                                    Curriculum <font color="red"> * </font>
                                                    <select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_term();">
                                                        <option value="Curriculum" selected> Select Curriculum </option>
                                                        <?php foreach ($curriculum as $listitem): ?>
                                                            <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												</label>
												</td>
												<td>
												<label>
                                                    Term <font color="red"> * </font>
                                                    <select size="1" id="term" name="term" aria-controls="example" onChange = "static_func_grid();">
                                                    </select> 													
                                                </label>
												<td>
												</tr>
												</table>
                                                <div id="main_table" class="bs-docs-example span8 scrollspy-example" style="width: 775px; height:100%; overflow:auto;" >
                                                    <form id="table_view" >
                                                </div>
                                            <div class="span3 pull-right">
                                                <div id="header_scroll" class="bs-docs-example span3" style="width:260px; height:330px;">
                                                    <div>
                                                        <label> <?php echo $this->lang->line('student_outcome_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> </label>
                                                        <textarea id="text_po_view" rows="5" cols="5" disabled>
													
                                                        </textarea>
                                                    </div>	
                                                    </br>

                                                    <div>
                                                        <label> Course Outcome (CO) </label>
                                                        <textarea id="text_clo_view" rows="5" cols="5" disabled>
														
                                                        </textarea>
                                                    </div>
                                                </div><!--span4 ends here-->
                                            </div>
                                        </div>
													</form>
                                    </div>
                                    <div id="approver">
                                    </div>

                                    <!-- Modal to display Performance Indicators and Measures -->
                                    <div id="myModal_indicator_measure" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 	aria-hidden="true" style="display: none;" data-controls-modal="myModal_indicator_measure" data-backdrop="static" data-keyboard="false"></br>
                                        <div class="container-fluid">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Performance Indicators & Measures
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-body" id="comments">
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i><span></span> Close </button>
                                        </div>
                                    </div>
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
	<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/clo_po.js'); ?>" type="text/javascript"> </script>

<!-- End of file static_clo_po_vw.php 
                        Location: .curriculum/map_clo_to_po/static_clo_po_vw.php -->