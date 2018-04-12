<?php
/**
 * Description	:	Generates Course Delivery Report

 * Created		:	March 24th, 2014

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 23-12-2015		   Arihant Prasad				Minor bug fixes and UI changes
 * 30-12-2015		   Shayista Mulla			Added loading image and cookies.			
  ------------------------------------------------------------------------------------------ */
?>

<!--head here -->
<?php $this->load->view('includes/head'); ?>
<!--branding here-->
<?php $this->load->view('includes/branding'); ?>

<!-- Navbar here -->
<?php $this->load->view('includes/navbar'); 
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
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Lesson Plan Report
                        </div>
                    </div>

				<form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/course_delivery_report/export_word'); ?>">
					<div class="pull-right"><br>
						<a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export to .doc</a>
					</div>
                    <table style="width:90%">
                        <tr>
                            <td>
                                <p>
                                    Curriculum: <font color="red"> * </font><br>
                                    <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "fetch_term();">
                                        <option value="Curriculum" selected> Select Curriculum </option>
                                        <?php foreach ($curriculum as $list_item): ?>
                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </p>
                            </td>
                            <td>
                                <p>
                                    Term: <font color="red"> * </font><br>
                                    <select size="1" id="term" name="term" aria-controls="example" onChange = "fetch_course();">
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
			    <td>
                                <p>Mapped List:&nbsp&nbsp&nbsp<input id="status" class="check" type="checkbox" name="status"></input></p> 
                            </td>
                        </tr>
                    </table>

                    <div class="bs-example bs-example-tabs">
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active"><a href="#lesson_plan" data-toggle="tab"> Lesson Plan </a></li>
                            <li style="display:none;"><a href="#mapping" data-toggle="tab"> Mapping </a></li>
                        </ul>								
                        <div id="myTabContent" class="tab-content">

                            <!-- Tab one - Lesson Plan starts here -->
                            <div class="tab-pane fade in active" id="lesson_plan">
                                    <div id="lesson_table" class="bs-docs-example">
									
                                        <div class="div2">
                                            <!-- Pass the values to the lesson plan grid -->
                                        </div>
                                        <input type="hidden" name="pdf" id="pdf" />
                                    </div>
                                    <div class="pull-right"><br>
                                        <a id="export" href="#" class="btn btn-success "><i class="icon-book icon-white"></i> Export to .doc</a> 
                                    </div>
                                </form>
                            </div>
                            <!-- Tab one - Lesson Plan ends here -->

                            <!-- Tab two - Mapping starts here -->
                            <div class="tab-pane fade" id="mapping">
                                <form target="_blank" name="form2" id="form2" method="POST" action="<?php echo base_url('report/course_delivery_report/export_mapping_pdf'); ?>">	
                                    <div class="pull-right">
                                        <a id="export_mapping" href="#" class="btn btn-info "><i class="icon-book icon-white"></i> Export to .pdf</a>
                                    </div>
                                    <div id="main_table" class="bs-docs-example span8 wrapper2" style="width: 71%; height:100%; overflow:auto;">
                                        <div class="div3">
                                            <!-- Pass the values to the mapping grid -->
                                        </div>
                                    </div>

                                    <!-- Program Outcome Side navbar -->
                                    <div class="span3 pull-right" id="po_stmt"><br>
                                        <div class="bs-docs-example span3" style="overflow:auto; width:auto;">	
                                            <div>
                                                <p><b><font color="blue"> <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></font></b></p>
                                                <div style="overflow:auto;" id="text_po_statement">
                                                    <!-- display program outcome statements -->
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--span3 ends here-->

                                    <!-- Course Learning Outcomes Side navbar -->
                                    <div class="span3 pull-right" id="clo_stmt"><br>
                                        <div class="bs-docs-example span3" style="overflow:auto; width:auto;">	
                                            <div>
                                                <p> <b><font color="blue"> Course Outcomes (CO)</font></b></p>
                                                <div style="overflow:auto;" id="text_clo_statement">
                                                    <!-- display program outcome statements -->
                                                </div>
                                            </div>
                                        </div>
                                    </div><br><!--span3 ends here-->

                                    <input type="hidden" name="pdf_mapping" id="pdf_mapping" />
                                </form>
                            </div>
                        </div>
                    </div>
            </section>
				</div>
		</div>
	</div>
</div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
    <script src="<?php echo base_url('twitterbootstrap/js/custom/report/course_delivery_report.js'); ?>" type="text/javascript"></script>
