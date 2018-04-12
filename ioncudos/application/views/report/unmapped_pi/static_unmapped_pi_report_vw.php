<?php
/**
 * Description	:	Generates unmapped Performance Indicator statements

 * Created		:	May 15th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 18-09-2013		   Arihant Prasad			File header, function headers, indentation 
												and comments.
  ------------------------------------------------------------------------------------------ */
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
                <?php $this->load->view('includes/static_sidenav_3'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <form target="_blank" name="form_blank" id="form_blank" method="POST">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Unmapped Performance Indicators (PI) Report
                                    </div>
                                </div><br>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Curriculum: <font color="red"> * </font>
                                                <select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_pos();">
                                                    <option value="Curriculum" selected> Select Curriculum </option>
                                                    <?php foreach ($results as $list_item): ?>
														<option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
                                            </label>
                                            <div class="bs-docs-example">
                                                <div id="table_view" style="overflow:auto;">
                                                </div>
                                            </div>
                                            <input type="hidden" name="pdf" id="pdf"/><br>
                            </form>
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
	<script src="<?php echo base_url('twitterbootstrap/js/custom/report/unmapped_pi_report.js'); ?>" type="text/javascript"> </script>

<!-- End of file static_unmapped_pi_report_vw.php 
                    Location: .report/unmapped_pi/static_unmapped_pi_report_vw.php -->