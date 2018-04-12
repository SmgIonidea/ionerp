<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description	: Course Articulation Matrix view page, provides the term all courses mapping with po details.	  
 * Modification History:
 *    Date			  Modified By					Description
 * 05-09-2013                    Mritunjay B S                           Added file headers, function headers & comments. 
 * 23-12-2015			Shayista Mulla				Added loading symbol and cookies.
 * 12-01-2016			Shayista Mulla				Added functionality to select only mapped column List.
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
                    <form target="_blank" name="form1" id="form1" method="POST" action="<?php echo base_url('report/crs_articulation_report/export_pdf'); ?>">
                        <div class="navbar">
                            <div class="navbar-inner-custom" data-key="lg_pgm_artmtrx_rprt">
                                Program Articulation Matrix Report
                            </div>
                        </div>

                        <div class="pull-right">
                            <a id="export" href="#" class="btn btn-success"><i class= "icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span></a>   
                        </div>

                        <div class="row-fluid">
                            <div class="span12">
                                <div>
                                    <div class="row-fluid">
                                        <table style="width:70%">
                                            <tr>
                                                <td>
                                                    <p>
                                                        <span data-key="lg_crclm">Curriculum</span>:<font color="red"> * </font><br>
                                                        <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "select_term();
                                                                fetch_crclm();">
                                                            <option value="" selected data-key="lg_sel_crclm">Select Curriculum</option>
                                                            <?php foreach ($results as $listitem): ?>
                                                                <option value="<?php echo $listitem['crclm_id']; ?>"> <?php echo $listitem['crclm_name']; ?> </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p>
                                                        <span data-key="lg_term">Term</span>:<font color="red"> * </font><br>
                                                        <select size="1" id="term" name="term" aria-controls="example" onChange = "func_grid();">
                                                        </select>
                                                    </p>
                                                </td>
                                                <td>
                                                  <!--  <p>
                                                        <input type="checkbox" name="course" id="course" onChange= "func_grid();" value="core" >&nbsp;<span data-key="lg_core_crs">Core Course</span>
                                                    </p>-->
													 <span data-key="lg_term">Course Type</span>:<font color="red"> * </font><br>
													<p><select id = "course_type"  name="course_type">
														<option value="-1">All</option>
													<?php foreach($course_type as $crs) { ?>
														<option value="<?php echo $crs['crs_type_id']; ?>"><?php echo $crs['crs_type_name']; ?></option>
													<?php } ?>
													</select>
													</p>
                                                </td>
                                                <td>

                                                    <p>
                                                        <span data-key="lg_mapd_list">Mapped List</span>:&nbsp&nbsp                     
                                                        <input id="status" class="check" type="checkbox" name="status"></input>
                                                    </p>
                                                </td>

                                            </tr>
                                        </table>

                                        <div class="bs-docs-example span8" style="width:98%;height:100%; overflow:auto;">
                                            <div id="main_table">
                                                <div id="course_articulation_matrix_grid">
                                                </div>
                                            </div>
                                        </div><!-- span8 ends here-->
                                        <div class="span8">
                                            <br/>
                                        </div>
                                        <div class="bs-docs-example span8"style="width:98%;height:100%; overflow:auto;" id= "po_stmt">
                                            <div  style="overflow:auto; width:auto;" >	
                                                <div >
                                                    <p> <b><font color="blue"><span data-key="lg_peos"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></span></font></b></p>
                                                    <table class="" id="po_statement" aria-describedby="example_info" >
                                                        <tbody style="overflow:auto;" id="text1">
                                                        </tbody>
                                                    </table>
                                                </div>	
                                            </div><!--span4 ends here-->
                                        </div>
                                    </div>
                                    <input type="hidden" name="pdf" id="pdf" />
                                    <input type="hidden" name="stmt" id="stmt" />
                                    <input type="hidden" name="curr" id="curr"/>
                                    <input type="hidden" name="term_name" id="term_name"/>
                                    <br/>
                                    <div class="pull-right"><br/>
                                        <a id="export" href="#" class="btn btn-success"><i class= "icon-book icon-white"></i> <span data-key="lg_export">Export .pdf</span></a>
                                        </form>
                                    </div>
									<!-- Modal showing table of COs to POs mapping matrix-->
									 <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" 	aria-hidden="true" style="width: 800px; left: 600px; display: block;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                                <div class="modal-header">
                                                    <div class="navbar">
                                                        <div class="navbar-inner-custom">
                                                            List Course Outcomes (COs) mapped to <?php echo $this->lang->line('student_outcomes_full'); ?> (<?php echo $this->lang->line('sos'); ?>).
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-body" id="comments">
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button> 
                                                </div>
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
<script type="text/javascript" language="javascript" src="<?php echo base_url('twitterbootstrap/js/custom/report/course_articulation_matrix.js'); ?>" ></script>
