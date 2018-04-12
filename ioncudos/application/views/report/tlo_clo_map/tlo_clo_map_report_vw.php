<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: TLO to CLO Mapping report view page, provides the term all TLO mapping with CLO Report.	  
 * Modification History:
 * Date				Modified By				Description
 * 05-09-2013                    Mritunjay B S                           Added file headers, function headers & comments.
 * 24-12-2015			Shayista Mulla 				Added loading symbol and cookies. 
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
            <!-- Contents-->
            <div id="loading" class="ui-widget-overlay ui-front">
                <img src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
            </div>
            <section id="contents">
                <div class="bs-docs-example">
                    <!--content goes here-->
                    <form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/tlo_clo_map_report/export'); ?>">
                        <input type="hidden" name="doc_type" id="doc_type">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                <?php echo $this->lang->line('entity_tlo_full'); ?> (<?php echo $this->lang->line('entity_tlo'); ?>) to <?php echo $this->lang->line('entity_clo_full'); ?> (<?php echo $this->lang->line('entity_clo'); ?>) Mapped Report - <?php echo $this->lang->line('entity_topic_singular'); ?> wise
                            </div>
                        </div>

                        <div class="pull-right">
                            <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                        </div>

                        <div class="pull-right">
                            <a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                        </div>

                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid" style="width:100%; overflow:auto;">
                                    <table style="width:100%; overflow:auto;">
                                        <tr>
                                            <td align="left">
                                                <label>
                                                    Curriculum:<font color="red"> * </font><br>
                                                    <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
                                                        <option value="Curriculum" selected>Select Curriculum</option>
                                                        <?php foreach ($results as $listitem): ?>
                                                            <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>&nbsp;&nbsp;&nbsp;
                                                </label>
                                            </td>
                                            <td align="left">
                                                <label>
                                                    Term:<font color="red"> * </font><br>
                                                    <select size="1" id="term" name="term" aria-controls="example" onChange = "select_course();">
                                                        <option> Select Term</option>
                                                    </select>&nbsp;&nbsp;&nbsp;
                                                </label>
                                            </td>
                                            <td align="left">
                                                <label>
                                                    Course:<font color="red"> * </font><br>
                                                    <select size="1" id="course" name="course" aria-controls="example" onChange = "select_topic();">
                                                        <option>Select Course</option>
                                                    </select>&nbsp;&nbsp;&nbsp;
                                                </label>
                                            </td>
                                            <td align="left" >
                                                <label>
                                                    <?php echo $this->lang->line('entity_topic') . '<br>'; ?> 
                                                    <select size="1" id="topic" name="topic" aria-controls="example" onChange = "func_grid();">
                                                        <option>Select <?php echo $this->lang->line('entity_topic'); ?> </option>
                                                    </select> 													
                                                </label>
                                            </td>
                                        </tr>
                                    </table>
                                    <div id="main_table" class="bs-docs-example">
                                        <div id="tlo_clo_mapping" style="overflow:auto;">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="pull-right">
                                        <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                                    </div>

                                    <div class="pull-right">
                                        <a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
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
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/report/tlo_clo_mapped_report.js'); ?>"></script>
