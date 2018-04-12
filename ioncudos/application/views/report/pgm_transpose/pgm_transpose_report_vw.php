<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Program Articulation Matrix view page.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                    Mritunjay B S                           Added file headers, function headers & comments. 
 * 23-12-2015			Shayista Mulla 				Added loading symbol and cookie.
  ---------------------------------------------------------------------------------------------------------------------------------
 */
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
                    <form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/transpose_report/export_pdf'); ?>">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Transpose of Program Articulation Matrix Report
                            </div>
                            <div class="pull-right">
                                <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        Curriculum :<font color="red"> * </font>
                                                <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_pos();
                                                        fetch_crclm();">
                                            <option value="Curriculum" selected>Select Curriculum</option>
                                            <?php foreach ($crclm_list as $curriculum_data): ?>
                                                <option value="<?php echo $curriculum_data['crclm_id']; ?>"> <?php echo $curriculum_data['crclm_name']; ?> </option>
                                            <?php endforeach; ?>
                                        </select> 
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							
                                    </p>
                                    <div class="bs-docs-example">
                                        <div id="program_articulation_matrix_grid" style="overflow:auto;">
                                        </div>
                                    </div>
                                    <input type="hidden" name="pdf" id="pdf" />
                                    <input type="hidden" name="curr" id="curr"/>
                                    <br>	
                                    <div class="pull-right">
                                        <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
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
</body>
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/report/program_transpose_matrix.js'); ?>" ></script>
</html>
