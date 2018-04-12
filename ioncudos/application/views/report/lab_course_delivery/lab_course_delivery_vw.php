<?php
/**
 * Description	:	Generates Course Delivery Report

 * Created		:	June 27th, 2015

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 30-11-2015		Shayista 			Added loading image and cookies.
 * 18-03-2016           Shayista Mulla                  Added doc button at the top of the page.
  ------------------------------------------------------------------------ */
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
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Lab Experiment Course Delivery Report
                        </div>
                    </div>
                    <div class="pull-right"><br>
                        <a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export to .doc </a>
                    </div>
                    <form target="_blank" name="lab_form" id="lab_form" method="POST" action="<?php echo base_url('report/lab_course_delivery_report/export_word'); ?>">
                        <table style="width:90%">
                            <tr>
                                <td>
                                    <p>
                                        Curriculum: <font color="red"> * </font><br>
                                        <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "fetch_term();">
                                            <option value="Curriculum" selected> Select Curriculum </option>
                                            <?php foreach ($curriculum as $list_item) { ?>
                                                <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                            <?php } ?>
                                        </select>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        Term: <font color="red"> * </font><br>
                                        <select size="1" id="term" name="term" aria-controls="example" onChange = "fetch_lab_course();">
                                        </select>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        Course <font color="red"> * </font><br>
                                        <select size="1" id="course" name="course" aria-controls="example" onChange = "fetch_all_details();">
                                        </select>
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <div id="lesson_table" class="bs-docs-example">
                            <div class="expt_table" id="expt_table">
                                <!-- Pass the values to the lesson plan grid -->
                            </div>

                        </div>
                        <input type="hidden" name="pdf" id="pdf" />
                        <div class="pull-right"><br>
                            <a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export to .doc </a>
                        </div>
                    </form><br><br>
                </div>
            </section>
        </div>
    </div>
</div>

<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/lab_course_delivery_report.js'); ?>" type="text/javascript"></script>


