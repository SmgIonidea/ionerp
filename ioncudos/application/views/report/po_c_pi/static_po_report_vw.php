<?php
/** 
* Description	:	Static (read only) List View for PO, Compentency & Performance Indicators Report Module. 
*					Select Curriculum then its related , Compentency & Performance Indicators are displayed in a report format.
* Created on	:	03-05-2013
* Modification History:
* Date              Modified By           	Description
* 14-01-2016		Vinay M Havalad        Added file headers, indentations & comments.
-------------------------------------------------------------------------------------------
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
                                        <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Report
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="row-fluid">
                                            <label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                Curriculum <font color="red"> * </font>
                                                <select size="1" id="crclm" name="crclm" aria-controls="example" onChange = "select_mapped_po_peo();">
                                                    <option value="Curriculum" selected>Select Curriculum</option>
                                                    <?php foreach ($results as $listitem): ?>
                                                        <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                    <?php endforeach; ?>
                                                </select> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
                                            </label>
                                            <div class="bs-docs-example">
                                                <div id="po_report_table_id" style="overflow:auto;">
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
			<script src="<?php echo base_url('twitterbootstrap/js/custom/report/po_report.js'); ?>" type="text/javascript"> </script>
			