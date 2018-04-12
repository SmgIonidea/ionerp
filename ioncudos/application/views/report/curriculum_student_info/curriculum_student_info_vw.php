<?php
/**
 * Description          :	View page for Crriculum Student performance.

 * Created		:	19-05-2016

 * Author		:       Shayista Mulla

 * Modification History:
 *   Date                Modified By                         Description

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
                    <form target="_self" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/curriculum_student_info/insert_student_info'); ?>" class="row-fluid" style="width:100%; overflow:auto;">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Student Performance
                            </div>                        
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid" style="width:100%; overflow:auto;">
                                    <table style="width:90%">
                                        <tr>
                                            <td>
                                                <p>
                                                    Department: <font color="red"> * </font><br>
                                                    <select size="1" id="dept" name="dept" aria-controls="example" onChange = "fetch_program();">
                                                        <option value="" selected> Select Department</option>
                                                        <?php foreach ($department_list as $list_item): ?>
                                                            <option value="<?php echo $list_item['dept_id']; ?>"> <?php echo $list_item['dept_name']; ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    Program: <font color="red"> * </font><br>
                                                    <select size="1" id="program" name="program" aria-controls="example" onChange = "fetch_curriculum();">
                                                        <option>Select Program</option>
                                                    </select>
                                                </p>
                                            </td>
                                            <td>
                                                <p>
                                                    Curriculum <font color="red"> * </font><br>
                                                    <select size="1" id="curriculum" name="curriculum" aria-controls="example" onChange = "fetch_details();">
                                                        <option>Select Curriculum</option>
                                                    </select>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <!-- Display student performance data -->
                                <div id="main_table" class="bs-docs-example span8 wrapper2" style="width:98%;height:100%;overflow:auto;display:none">
                                    <div class="map_po_to_peo"><div class="accordion">
                                            <div class="accordion-group">

                                                <div class="brand-custom">
                                                    <a class="brand-custom collapsed" data-toggle="collapse" href="#std_intake" style="text-decoration:none;">
                                                        <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Student Intake Details</b></h5>
                                                    </a>                    
                                                </div>
                                            </div>
                                        </div>
                                        <div id="std_intake" name="std_intake" class="panel-body"></div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="accordion-group">
                                                    <a class="brand-custom collapsed" data-toggle="collapse" href="#student_graduate" style="text-decoration:none;">
                                                        <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Student Graduate Details</b></h5>
                                                    </a> 
                                                </div>
                                            </div>
                                            <div id="student_graduate" class="panel-collapse collapse">

                                            </div>
                                        </div>
                                        <div class="accordion">
                                            <div class="accordion-group">

                                                <div class="brand-custom">
                                                    <a class="brand-custom collapsed" data-toggle="collapse" href="#student_placement" style="text-decoration:none;">
                                                        <h5><b>&nbsp;<i class="icon-chevron-down"></i>&nbsp;Student Placement Details</b></h5>
                                                    </a>                    
                                                </div>
                                            </div>
                                        </div>
                                        <div id="student_placement" class="panel-collapse collapse">                                           
                                        </div>                                  
                                    </div>
                                </div>
                                </form>
                                <div class="row-fluid" style="width:100%; overflow:auto;"><br></br>
                                    <div class="pull-right" id="button_list" style="display:none">
                                        <button id="add_form_submit" class="add_form_submit btn btn-primary" type="button"><i class="icon-file icon-white"></i> Save</button>
                                        <button class="btn btn-info" type="reset"><i class="icon-refresh icon-white"></i> Reset</button>						
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--Do not place contents below this line-->
                </div>
            </section>			
        </div>					
    </div>
</div> 
<!---place footer.php here -->
<?php $this->load->view('includes/footer'); ?> 
<!---place js.php here -->
<?php $this->load->view('includes/js'); ?>
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/curriculum_student_info.js'); ?>" type="text/javascript"></script>
<!-- End of file curriculum_student_info_vw.php 
                        Location: report/curriculum_student_info/curriculum_student_info_vw.php  -->