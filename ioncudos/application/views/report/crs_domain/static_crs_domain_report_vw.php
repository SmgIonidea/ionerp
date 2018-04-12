<?php
/** 
* Description	:	Static (read only) List View for Course Stream Report Module. 
* Created on	:	03-05-2013
* Modification History:
* Date                Modified By           Description
* 12-09-2013		Abhinay B.Angadi        Added file headers, indentations & Code cleaning.
-----------------------------------------------------------------------------------------------
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
                <?php $this->load->view('includes/static_sidenav_3'); ?>
                <div class="span10">
                    <!-- Contents
            ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example">
                            <!--content goes here-->
							<div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Course Stream (Domain) Report
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <label class="control-label" for="pgm_title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											Department: <font color='red'>*</font>
                                                <select size="1" id="dept" name="dept" aria-controls="example" onChange = "fetch_curriculum();">
                                                    <option value="Curriculum" selected>Select Department</option>
                                                    <?php foreach ($results as $listitem): ?>
                                                        <option value="<?php echo $listitem['dept_id']; ?>"> <?php echo $listitem['dept_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Curriculum: <font color='red'>*</font>
                                                <select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "display_table(); fetch_crclm();">
                                                </select> 
                                            </label>
                                            <div class="bs-docs-example">
                                                <div id="crs_stream_report_table_id" style="overflow:auto;">
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
			<script src="<?php echo base_url('twitterbootstrap/js/custom/report/crs_domain_report.js'); ?>" type="text/javascript"> </script>