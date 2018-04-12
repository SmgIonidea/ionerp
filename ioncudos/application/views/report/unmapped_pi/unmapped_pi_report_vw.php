<?php
/**
 * Description	:	Generates unmapped Performance Indicator statements

 * Created		:	May 15th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 18-09-2013		   Arihant Prasad			File header, function headers, indentation and comments.
 * 24-12-2015		   Shayista Mulla			Added loading image and cookie.
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
                <?php $this->load->view('includes/sidenav_3'); ?>
                <div class="span10">
                    <!-- Contents -->
		    <div id="loading" class="ui-widget-overlay ui-front">
                	<img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            	    </div>
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <form target="_blank" name="form_blank" id="form_blank" method="POST" action="<?php echo base_url('report/unmapped_pi_report/export_pdf'); ?>">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Unmapped <?php echo $this->lang->line('outcome_element_plu_full'); ?> Report
                                    </div>
									<div class="pull-right">
                                        <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf</a>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Curriculum: <font color="red"> * </font>
                                                <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_pos();
                                                        fetch_curriculum();">
                                                    <option value="Curriculum" selected> Select Curriculum </option>
                                                    <?php foreach ($results as $list_item): ?>
                                                        <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
													<?php endforeach; ?>
                                                </select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
                                            </p>
                                            <div class="bs-docs-example">
                                                <div id="table_view" style="overflow:auto;">
                                                </div>
                                            </div>									
                                            <input type="hidden" name="pdf" id="pdf" />
                                            <input type="hidden" name="curr" id="curr"/><br>
                                            <div class="pull-right">
                                                <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf</a>
                                            </div>
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

<!-- End of file unmapped_pi_report_vw.php 
                    Location: .report/unmapped_pi/unmapped_pi_report_vw.php -->
