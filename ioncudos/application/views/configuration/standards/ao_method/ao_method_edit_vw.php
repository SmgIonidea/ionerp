<?php
/**
 * Description	:	Assessment Method add page will allow the admin to add assessment methods and their criterias.
 * 					
 * Created		:	14-08-2014
 *
 * Author		:	Jyoti
 * 		  
 * Modification History:
 *   Date                Modified By                			Description
 *
  ------------------------------------------------------------------------------------------------- */
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
        <?php $this->load->view('includes/sidenav_1'); ?>
        <div class="span10">
            <!-- Contents -->
            <section id="contents">
                <div class="bs-docs-example ">
                    <!--content goes here-->
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Edit Assessment Method
                        </div>
                    </div>	
                    <br>
                    <form  class="form-horizontal" method="POST" id="ao_method_edit_form" name="ao_method_form" action= "<?php echo base_url('configuration/ao_method/ao_method_update_record'); ?>">

                        <div class="control-group">
                            <p class="control-label inline" for="">Program : <font color="red">* </font></p>
                            <div class="controls">
                                <?php
                                foreach ($results as $listitem1) {
                                    $select_options1[$listitem1['pgm_id']] = $listitem1['pgm_title']; //group name column index
                                }
                                echo form_dropdown('ao_method_pgm_id', array('' => 'Select Department') + $select_options1, set_value('pgm_id', $default_program_id), 'class="required ao_method_pgm_id span5" id="ao_method_pgm_id"');
                                ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="ao_method_name">Assessment Method Name :<font color="red">*</font>
                            </p>
                            <div class="controls">
                                <?php echo form_input($ao_method_id); ?>										
                                <?php echo form_input($default_ao_method_pgm_id); ?>
                            </div>
                            <div class="controls">
                                <?php echo form_input($ao_method_name); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <p class="control-label" for="ao_method_description">Outcome Assessed :</p>
                            <div class="controls">
                                <?php echo form_textarea($ao_method_description); ?>
                                <br/> <span id='char_span_support' class='margin-left5'>0 of 2000. </span>
                            </div>
                        </div>			


                        <?php if ($range_data == NULL) { ?>

                            <div class="pull-right"> 
                                <a id="define_rubrics" class="btn btn-primary global" href="#">Define Rubrics</a>
                                <input type="hidden" name="range_count" id="range_count" value="4" readonly />
                                <input type="hidden" name="is_define_rubrics" id="is_define_rubrics" value="0" readonly />
                            </div>
                            <br><br>

                            <div id="check_main" class="bs-docs-example" style="width:auto; height:auto; overflow:auto;">
                                <div id="check"><!--style="width:1100px;height:100%;overflow:auto;">-->

                                </div>
                            </div>

                        <?php
                        } else {
                            echo "<input type=hidden name=range_count id=range_count value=" . count($range_data) . " /><input type=hidden name=is_define_rubrics id=is_define_rubrics value=1 readonly />";
                            echo "<div class=bs-docs-example style='width:auto; overflow:auto;'>";
                            $range_count = count($range_data);
                            $colspan_val = $range_count + 1;

                            $criteria_count = count($criteria_data);

                            echo "<div class='bs-docs-example' style='width:auto; overflow:auto;'>
											<div class='navbar-inner-custom'>
												Criteria
											</div>";
                            echo "<table border=0 cellpadding=10 style='width:auto; overflow:auto;'>
										<tr ><td></td><td colspan=" . $colspan_val . "><center><b style=font-size:10pt>Scale of Assessment</b></center></td></tr>
										<tr></tr>
											<div style='style='display: inline-block; white-space: nowrap; margin-left: auto;  margin-right: auto;'>
												<tr><td class='span2'><center><b style=font-size:10pt>Criteria : <font color='red'> * </font></b></center></td>";
                            for ($k = 1; $k <= $range_count; $k++) {
                                echo "<td style='width:20%'><center><input type=text name=range_" . $k . " id=range_" . $k . " class='loginRegex required input-mini' value=" . $range_data[$k - 1]['criteria_range'] . " /><font color='red'> * </font></center></td>";
                            }

                            echo "<td><center><b style=font-size:10pt>Action</b></center></td></tr></div>
										<tr id='add_more_1'><td style='border-top: 1px solid #E6E6E6;'><textarea class='criteria_check required input-medium' name=criteria_1 id=criteria_1 rows=5 cols=10 >" . $criteria_data[0]['criteria'] . "</textarea></td>";
                            $cid = $criteria_data[0]['rubrics_criteria_id'];
                            for ($j = 1; $j < $range_count + 1; $j++) {
                                $rid = $range_data[$j - 1]['rubrics_range_id'];

                                echo "
													<td style='border-top: 1px solid #E6E6E6;'><center><textarea name=c_" . $j . "_stmt_1 id=c_" . $j . "_stmt_1 rows=5 cols=10 class='required input-medium ' >" . $criteria_description_data[$j - 1]['criteria_description'] . "</textarea></center></td>";
                            }

                            echo "
								<td width=60px style='border-top: 1px solid #E6E6E6;'><center><a id=remove_criteria1 value=1 class=Delete_edit href=# data-toggle=modal data-original-title=Delete rel=tooltip title=Delete ><i class=icon-remove></i></a></center></td>
								</tr></table></div>";

                            for ($i = 1; $i < $criteria_count; $i++) {
                                $j = $i + 1;

                                echo "<div id=add_more_" . $j . ">
											<div id=remove_" . $j . " >
											<div class='bs-docs-example'>
												<table border=0 cellpadding=10 style='width:auto; overflow:auto;'><tr><td><textarea class='criteria_check required input-medium 'name = criteria_" . $j . " id=criteria_" . $j . " rows=5 cols=10>" . $criteria_data[$i]['criteria'] . "</textarea></td>";
                                $c_id = $criteria_data[$i]['rubrics_criteria_id'];
                                for ($k = 1; $k <= $range_count; $k++) {
                                    $r_id = $range_data[$k - 1]['rubrics_range_id'];
                                    for ($l = 0; $l < count($criteria_description_data); $l++) {
                                        if ($criteria_description_data[$l]['rubrics_range_id'] == $r_id &&
                                                $criteria_description_data[$l]['rubrics_criteria_id'] == $c_id) {
                                            echo "<td><center><textarea name=c_" . $k . "_stmt_" . $j . " id=c_" . $k . "_stmt_" . $j . " rows=5 cols=10 class='required input-medium'>" . $criteria_description_data[$l]['criteria_description'] . "</textarea></center>
												</td>";
                                        }
                                    }
                                }
                                //echo "<td width=60px><center><a id=remove_criteria".$j." value=".$criteria_data[$i]['rubrics_criteria_id']." class=Delete_edit href=# data-toggle=modal data-original-title=Delete rel=tooltip title=Delete ><i class=icon-remove></i></a></center></td>";
                                echo "<td width=60px><center><a id=remove_criteria" . $j . " value=" . $criteria_data[$i]['rubrics_criteria_id'] . " href=# class=Delete data-toggle=modal data-original-title=Delete rel=tooltip title=Delete ><i class=icon-remove></i></a></center></td>";
                                echo "</tr></table>		
												</div>
											</div>
										</div>";
                            }
                            if (!empty($counter_value)) {
                                // $counter_value = explode(',',$counter_value);
                                echo "<div id=insert_before></div> 									
											<input type='hidden' name='counter' id='counter' value=" . $counter_value . " readonly>
											<input type='hidden' name='ao_method_counter' id='ao_method_counter' value=" . $criteria_count . " readonly>";
                            } else {
                                echo "<div id=insert_before></div> 									
										<input type='hidden' name='counter' id='counter' value=1 readonly>
										<input type='hidden' name='ao_method_counter' id='ao_method_counter' value=1 readonly>";
                            }
                            echo "<br>
									<div id='duplicate_message' style='color:red;font-weight: bold;font-size: small;'></div>
									<div class='pull-right'><br>
										<a id='add_more_criteria' class='btn btn-primary global' href='#'><i class='icon-plus-sign icon-white'></i> Add More Criteria </a>
									</div></div><br>";
                        }
                        echo "<br><br><div class='pull-right'>
                                    <button class='ao_method_edit_form_submit btn btn-primary'><i class='icon-file icon-white'></i>  Update</button>";
                        ?>
                        <a href="<?php echo base_url('configuration/ao_method'); ?>" class="btn btn-danger"><i class="icon-remove icon-white"></i> Cancel</a> <br><br>
                        <?php
                        echo "	
									</div></br>";
                        echo "</div>";
                        ?>


                        <!--Checkbox Modal-->
                        <div id="myModal1" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                            </br>
                            <div class="container-fluid">
                                <div class="navbar">
                                    <div class="navbar-inner-custom">
                                        Warning 
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" id="comments">
                                <p >Assessment Method Name already exists under the selected Program.</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger close1" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close </button>
                            </div>
                        </div>

                        <div id="myModaldelete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class=" navbar-inner-custom">
                                    Delete Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete test?</p>
                                <input type="hidden" value="" name="deleteId" id="deleteId" disabled />	
                            </div>
                            <div class="modal-footer">
                                <button class="delete_confirm btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>  Cancel</button>
                            </div>
                        </div>
                        <!-- Delete modal for already existing database values  -->
                        <div id="myModaldelete_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="delete_dialog" data-backdrop="static" data-keyboard="true">
                            <div class="modal-header">
                                <div class=" navbar-inner-custom">
                                    Delete Confirmation 
                                </div>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete?</p>
                                <input type="hidden" value="" name="deleteId_edit" id="deleteId_edit" disabled />	
                                <input type="hidden" value="" name="delete_criteria_id_edit" id="delete_criteria_id_edit" disabled />	
                            </div>
                            <div class="modal-footer">
                                <button class="delete_confirm_edit btn btn-primary"  data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i>  Cancel</button>
                            </div>
                        </div>

                    </form><br></div> 
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
<script src="" type="text/javascript"></script>
<script src="<?php echo base_url('twitterbootstrap/js/custom/configuration/ao_method_edit.js'); ?>" type="text/javascript"></script> 
<!-- End of file ao_method_add_vw.php 
                        Location: .configuration/standards/ao_method/ao_method_add_vw.php -->
