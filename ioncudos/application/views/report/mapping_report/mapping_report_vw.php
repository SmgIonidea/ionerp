<?php
/**
 * Description		:	Generates Course Delivery Report

 * Created		:	23-02-2016

 * Author		:	Shayista Mulla

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
                    <form target="_blank" name="form_id" id="form_id" method="POST" action="<?php echo base_url('report/mapping_report/export_pdf'); ?>" class="row-fluid" style="width:100%; overflow:auto;">
                        <div class="navbar">
                            <div class="navbar-inner-custom">
                                Mapping Report
                            </div>
                            <div class="pull-right">
                                <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf</a>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid" style="width:100%; overflow:auto;">
                                    <table style="width:100%">
                                        <tr>
                                            <td>
                                                <p>Curriculum: <font color="red"> * </font><br>
                                                    <select size="1" id="crclm" name="crclm" autofocus = "autofocus" aria-controls="example" onChange = "fetch_term();">
                                                        <option value="" selected> Select Curriculum </option>
                                                        <?php foreach ($curriculum as $list_item): ?>
                                                            <option value="<?php echo $list_item['crclm_id']; ?>"> <?php echo $list_item['crclm_name']; ?> </option>
                                                        <?php endforeach; ?>
                                                    </select></p>
                                            </td>
                                            <td>
                                                <p>Term: <font color="red"> * </font><br>
                                                    <select size="1" id="term" name="term" aria-controls="example" onChange = "fetch_course();">
                                                        <option>Select Term</option>
                                                    </select></p>
                                            </td>
                                            <td>
                                                <p>Course:<font color="red"> * </font><br>
                                                    <select size="1" id="course" name="course" aria-controls="example" onChange = "fetch_all_details();">
                                                        <option>Select Course</option>
                                                    </select></p>
                                            </td>
                                            <td>
                                                <p>Select Option: <br>
                                                    <select size="1" id="option" name="option" aria-controls="example" onChange = "fetch_term();">
                                                        <option value="1" selected>All ( <?php echo $this->lang->line('sos'); ?> & <?php echo $this->lang->line('entity_psos'); ?> )</option >
                                                        <option value="2"><?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?></option>
                                                        <option value="3"><?php echo $this->lang->line('entity_psos_full'); ?> (<?php echo $this->lang->line('entity_psos'); ?>)</option>
                                                    </select></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div id="main_table" class="bs-docs-example span8 wrapper2" style="width:98%;height:100%;overflow:auto;display:none">
                                    <div class="map_po_to_peo">
                                        <!-- Pass the values to the po to peo mapping grid -->
                                    </div>
                                </div>
                                <div id="gap" class="span8 wrapper2" style="width:71%; height:100%; overflow:auto;">
                                    <br/>
                                    <td>
                                        <p id="mapping" style="display:none;">Mapped <?php echo $this->lang->line('sos'); ?>:&nbsp&nbsp&nbsp<input id="status" class="check" type="checkbox" name="status"></input></p> 
                                    </td>
                                </div>
                                <div id="main_table1" class="bs-docs-example span8 wrapper2" style="width:98%;height:100%; overflow:auto;display:none;">
                                    <div class="map_co_to_po">
                                        <!-- Pass the values to the co to po mapping grid -->
                                    </div>
                                </div>
                                </form>
                                <div class="span8">

                                </div>
                                <!-- Program Outcome Side navbar -->
                                <div class="bs-docs-example span8" id="po_stmt1" style="width:98%;height:100%; overflow:auto;display:none;"><br>
                                    <div>
                                        <table class="table table-hover">
                                            <tr>
                                                <td style="border:0;">
                                                    <!--<b><font color="blue" id="po"> <?php //echo $this->lang->line('student_outcomes_full');  ?> <?php //echo $this->lang->line('student_outcomes');  ?></font></b>-->
                                                    <b><font color="blue" id="po"> Program Educational Objectives (PEOs) </font></b>
                                                </td>
                                            </tr>
                                        </table>	
                                        <input type="hidden" value="<?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>" id="pos"/>
                                        <input type="hidden" value="<?php echo $this->lang->line('entity_psos_full'); ?> (<?php echo $this->lang->line('entity_psos'); ?>)" id="psos"/>
                                        <table class="" id="po_statement" aria-describedby="example_info" >
                                            <tbody style="overflow:auto;" id="text_po_statement" >
                                                <!-- display program outcome statements -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row-fluid" style="width:100%; overflow:auto;"><br></br>
                                    <div class="pull-right">
                                        <a id="export" href="#" class="btn btn-success"><i class="icon-book icon-white"></i> Export .pdf</a>
                                    </div>
                                </div>
                                <input type="hidden" name="pdf" id="pdf" />
                                <input type="hidden" name="curr" id="curr" />	
                            </div>
                        </div>
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/report/mapping_report.js'); ?>" type="text/javascript"></script>
