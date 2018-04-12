<?php
/**
 * Description	:	Improvement Plan
 * Created on	:	24-08-2015
 * Created by	:	Arihant Prasad
 * Modification History:
 * Date                Modified By           Description
 * 24-12-2015		Shayista Mulla		Added loading image and cookies.        
  ------------------------------------------------------------------------------------------------------------
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
                    <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/improvement_plan/export_pdf'); ?>">
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_imp_plan_rprt">
                                Improvement Plan Report
                            </div>
                            <div class="pull-right">
                                <button type="submit" href="#" class="btn btn-success export"><i class="icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span></button>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid" style="width:100%; overflow:auto;">
                                    <table style="width:100%; overflow:auto;">
                                        <tr>
                                            <td>
                                                <p>
                                                    <span data-key="lg_crclm">Curriculum </span><font color="red"> * </font><br>
                                                    <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();">
                                                        <option value="Curriculum" selected data-key="lg_sel_crclm"> Select Curriculum </option>
                                                        <?php foreach ($curriculum_result as $list_item): ?>
                                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <span data-key="lg_term">Term</span> <font color="red"> * </font><br>
                                                    <select size="1" id="term" name="term" aria-controls="example" onChange = "select_course();">
                                                        <option value="Term" selected data-key="lg_sel_term"> Select Term </option>
                                                    </select>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    <span data-key="lg_course">Course</span> <font color="red"> * </font><br>
                                                    <select size="1" id="course" name="course" aria-controls="example" onChange="improvement_plan_display();">
                                                        <option value="Course" selected data-key="lg_sel_course"> Select Course </option>
                                                    </select>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                    <div class="bs-docs-example " >
                                        <div id="table_view" style="overflow:auto;">
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="pull-right">
                                        <button type="submit" href="#" class="btn btn-success export"><i class="icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span></button>
                                    </div>
                                    <input type="hidden" name="pdf" id="pdf" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <!--Do not place contents below this line-->
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/improvement_plan.js'); ?>" type="text/javascript"></script>
