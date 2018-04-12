<?php
/**
 * Description	:	Generates Report for Curriculum courses

 * Created		:	1 July 2015

 * Author		:	

 * Modification History:
 *   Date                Modified By                         Description
 * 24-12-2015		Shayista Mulla			Added loading symbol and cookies.
 * 18-03-2016           Shayista Mulla                  Added Pdf and Doc buttons at bottom of the page.
  ------------------------------------------------------------------------------------------ */
?><!--head here -->
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
                <div class="bs-docs-example fixed-height">
                    <!--content goes here-->                    
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Term-wise Curriculum Structure Report
                        </div>
                    </div>

                    <form class="form-inline" method="POST" target="_blank" id="list_course_details" name="list_course_details" action="<?php echo base_url('report/course_report/export'); ?>">
                        <input type="hidden" name="doc_type" id="doc_type">
                        <div class="control-group">
                            <div class="controls">
                                <label class="control-label">Curriculum:<font color='red'>*</font></label>

                                <select id="curriculum" name="curriculum" autofocus = "autofocus" class="input-xlarge span3" onChange="termlist();">
                                    <option value=""> Select Curriculum </option>
                                    <?php foreach ($curriculum_data as $listitem) { ?>
                                        <option value="<?php echo htmlentities($listitem['crclm_id']); ?>"> <?php echo $listitem['crclm_name']; ?></option>
                                    <?php } ?>
                                </select>

                                <?php echo str_repeat('&nbsp', '8'); ?>
                                <label class="control-label">Term:<font color='red'>*</font></label>
                                <select id="term" name="term" class="input-xlarge span2" onChange="get_courses();">
                                    <option>Select Term</option>
                                </select>
                            </div><br>   
                            <div class="pull-right">
                                <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                            </div>

                            <div class="pull-right">
                                <a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                            </div>
                            <br/><br/>
                            <div class="bs-docs-example">
                                <div id="course_info">				
                                </div>
                            </div>
                            <input type="hidden" name="course_info_hidden" id="course_info_hidden" />
                        </div>
                    </form>
                    <div class="pull-right">
                        <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf </a>
                    </div>

                    <div class="pull-right">
                        <a id="export_doc" href="#" class="btn btn-success" style="margin-right: 2px;"><i class="icon-book icon-white"></i> Export .doc </a>
                    </div>
                    <br/><br/>
                </div>
            </section>
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script>
    var entity_cie = "<?php echo $this->lang->line('entity_cie'); ?>";
    var entity_tee = "<?php echo $this->lang->line('entity_tee'); ?>";
    var entity_cie_full = "<?php echo $this->lang->line('entity_cie_full'); ?>";
    var entity_tee_full = "<?php echo $this->lang->line('entity_see_full'); ?>";
</script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/course_report.js'); ?>" type="text/javascript"></script>
