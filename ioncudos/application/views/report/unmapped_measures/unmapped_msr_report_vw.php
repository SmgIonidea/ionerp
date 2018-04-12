<?php
/** 
* Description	:	List View for Unmapped Measures Report Module. Select Curriculum then 
*					its related POs, PIs and Measures is displayed in a report format 
*					which can be exported as a pdf file.
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 17-09-2013		Abhinay B.Angadi        Added file headers, indentations.
*24-12-2015		Shayista Mulla		Added loading image and cookie.
------------------------------------------------------------------------------------------------------------
*/
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
        <?php
        $this->load->view('includes/navbar');
        ?> 
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
                            <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/unmapped_msr_report/export_pdf'); ?>">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Unmapped Performance Indicators (PIs) Report
                                    </div>
									<div class="pull-right">
                                        <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf</a>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Curriculum <font color="red"> * </font>
                                                <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_unmapped_measures(); fetch_crclm();">
                                                    <option value="Curriculum" selected>Select Curriculum</option>
                                                    <?php foreach ($results as $listitem): ?>
                                                        <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
                                            </p>
                                            <div class="bs-docs-example">
                                                <div id="unmapped_measures_table_id" style="overflow:auto;">
                                                </div>
                                            </div>
                                            <input type="hidden" name="pdf" id="pdf" />
                                            <input type="hidden" name="curriculum_id" id="curriculum_id"/>
                                            <br>	
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
			<script src="<?php echo base_url('twitterbootstrap/js/custom/report/unmapped_msr_report.js'); ?>" type="text/javascript"> </script>
