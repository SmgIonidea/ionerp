<!DOCTYPE html>
<?php
/*
  --------------------------------------------------------------------------------------------------------------------------------
 * Description           : Curriculum view page, provides the list of curriculums  and progress of each curriculum.	  
 * Modification History  :
 * Date			  Modified By			Description
 * 05-09-2013		Mritunjay B S               Added file headers, function headers & comments. 
 * 31-12-2015 		Shayista Mulla              Added Loading image. 
  ---------------------------------------------------------------------------------------------------------------------------------
 */
?>
<html lang="en">
    <!--head here -->
    <?php $this->load->view('includes/head'); ?>
    <body data-spy="scroll" data-target=".bs-docs-sidebar">
        <!--branding here-->
        <?php $this->load->view('includes/branding'); ?>
        <!-- Navbar here -->
        <?php $this->load->view('includes/navbar'); ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php $this->load->view('includes/sidenav_2'); ?>
                <div class="span10">
                    <!-- Contents -->
                    <section id="contents">
                        <div id="loading" class="ui-widget-overlay ui-front">
                            <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                        </div>
                        <div class="bs-docs-example">
                            <!--content goes here-->
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Curricula (Regulations) List

                                    <a href="#help" class="pull-right show_help" data-toggle="modal" style="text-decoration: underline; color: white; font-size: 12px;">Guidelines&nbsp;<i class="icon-white icon-question-sign"></i></a>
                                </div>
                            </div>				
                            <div class="row">
                                <a  class="btn btn-primary pull-right"" href="<?php echo base_url('curriculum/curriculum/add_curriculum'); ?>"><i class="icon-plus-sign icon-white"></i> Add</a>
                            </div>
                            <br />
                            <div id="loading" class="ui-widget-overlay ui-front">
                                <img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
                            </div>
                            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="header headerSortDown span1"  role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Curriculum</th>
                                            <th class="header span2"  role="columnheader" tabindex="0" aria-controls="example" >Program</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >Dept.</th>
                                            <th class="header" style = "width : 35px;" role="columnheader" tabindex="0" aria-controls="example" >From</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" >To</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example" ><?php echo $this->lang->line('program_owner_full'); ?></th>
                                            <th class="header" style = "width : 60px;" role="columnheader" tabindex="0" aria-controls="example" >PEO Creation</th>
                                            <th class="header" style = "width : 30px;" role="columnheader" tabindex="0" aria-controls="example" >Import</th>
                                            <th class="header" style = "width : 30px;" role="columnheader" tabindex="0" aria-controls="example">Edit</th>
                                            <th class="header" style = "width : 40px;" role="columnheader" tabindex="0" aria-controls="example">Status</th>
                                            <th class="header span1"  role="columnheader" tabindex="0" aria-controls="example"><?php echo $this->lang->line('outcome_element_sing_short'); ?> and PI status</th>
                                            <th  class="header" style = "width : 120px;" role="columnheader" tabindex="0" aria-controls="example">Course - <?php echo $this->lang->line('entity_clo'); ?> to Bloom's Level Mapping Status</th>
                                        </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                        <?php foreach ($curriculum_list_result as $curriculum_list): ?>
                                            <tr class="gradeU even">
                                                <td class="sorting_1 table-left-align"><a href="<?php echo base_url('curriculum/curriculum/details_curriculum') . '/' . $curriculum_list['crclm_id']; ?>"><?php echo $curriculum_list['crclm_name']; ?> </a></td>
                                                <td class="sorting_1"><?php echo $curriculum_list['pgm_title']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['dept_acronym']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['start_year']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['end_year']; ?> </td>
                                                <td class="sorting_1"><?php echo $curriculum_list['title'] . " " . $curriculum_list['first_name'] . " " . $curriculum_list['last_name']; ?> </td>                   
                                                <td>
                                                    <?php
                                                    if ($curriculum_list['crclm_release_status'] == 2) {
                                                        ?>
                                                        <button style="margin-right: 2px;" class="btn btn-success" disabled="disabled">Initiated</button>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a id="<?php echo $curriculum_list['crclm_id']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>" class="btn btn-warning publish" type="submit">Pending</a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                        <div id="myModal4" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal4" data-backdrop="static" data-keyboard="false">
                                            <div class="modal-header">
                                                <div class="navbar">
                                                    <div class="navbar-inner-custom">
                                                        Proceed to PEO Confirmation 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <p><b>Current Step : </b>Curriculum is created.
                                                <p><b>Next Step : </b>Add Program Educational Objectives (PEOs). 
                                                <p> An Email will be sent to Chairman - <b id="chairman_username" style="color:#E67A17;"></b>
                                                <h4><center>Current State of Curriculum : <font color="brown"><b id="modal_crclm_name" style="color:#E67A17; text-decoration: underline;"></b></center></font></h4>
                                                <img src="<?php echo base_url('twitterbootstrap/img/modal_workflow_img/proceed_to_peo.png'); ?>">
                                                </img>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to proceed to the creation of Program Educational Objectives(PEOs)?
                                            </div>               
                                            <div class="modal-footer">
                                                <button class="submit btn btn-primary" id="confirmPublish" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button> 
                                                <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                            </div>
                                        </div>
                                        <td>
                                            <?php
                                            if ($curriculum_list['crclm_release_status'] == 1 || $curriculum_list['crclm_release_status'] == 2) {
                                                ?>
                                                <a id="imp_data_<?php echo $curriculum_list['crclm_id']; ?>" class="import_rollback cursor_pointer" title="<?php echo $curriculum_list['modify_date']; ?>" data-attr = "<?php echo $curriculum_list['modify_date']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>">Roll-back</a>
                                                <?php
                                            } else {
                                                ?>
                                                <a id="imp_data_<?php echo $curriculum_list['crclm_id']; ?>" class="import_curriculum cursor_pointer" title="<?php echo $curriculum_list['crclm_name']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>"> <i class="icon-download-alt"></i>Import Data</a>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                        <center>
                                            <a class="" href="<?php echo base_url('curriculum/curriculum/edit') . '/' . $curriculum_list['crclm_id']; ?>"><i class="icon-pencil "></i></a>
                                        </center>
                                        </td>	
                                        <?php if ($curriculum_list['status'] == 0) { ?>
                                            <td><center><a data-toggle="modal" href="#myModalenable" class="get_id"   id="<?php echo $curriculum_list['crclm_id']; ?>"><i class="icon-ok-circle"></i> </a></center></td>
                                        <?php } else {
                                            ?>
                                            <td><center><a data-toggle="modal" href="#myModaldisable" class="get_id"    id="<?php echo $curriculum_list['crclm_id']; ?>"><i class="icon-ban-circle"></i></a></center></td>
                                        <?php } ?> 
                                        <td>
                                            <?php if ($curriculum_list['oe_pi_flag'] == 0) { ?>
                                                <a id="<?php echo $curriculum_list['crclm_id']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>"  class="btn btn-warning span10 oe_pi_mandatory" disabled="disabled" >Optional</a>
                                            <?php } else { ?>
                                                <a id="<?php echo $curriculum_list['crclm_id']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>" class="btn btn-success span10 oe_pi_optional">Mandatory</a>
                                            <?php } ?>
                                        </td>
                                        <td> 
                                            <?php if ($curriculum_list['clo_bl_flag'] == 0) { ?>
                                                <a id="<?php echo $curriculum_list['crclm_id']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>"  class="btn btn-warning span7 clo_bl_mandatory"  >Optional</a>
                                            <?php } else { ?>
                                                <a id="<?php echo $curriculum_list['crclm_id']; ?>" abbr="<?php echo $curriculum_list['crclm_name']; ?>" class="btn btn-success span7 clo_bl_optional">Mandatory</a>
                                            <?php } ?>
                                        </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <br/><br/><br/>
                                <div class="row pull-right">
                                    <a  class="btn btn-primary pull-right" href="<?php echo base_url('curriculum/curriculum/add_curriculum'); ?>"><i class="icon-plus-sign icon-white"></i> Add</a>
                                </div>
                                <br><br>
                                <!-- Modal for progress bar -->
                                <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                    <div class="container-fluid"><br>
                                        <div class="navbar">
                                            <div class="navbar-inner-custom">
                                                Progress of Curriculum 
                                            </div>
                                        </div><br>
                                    </div>
                                    <div class="modal-body" id="status">
                                    </div>		
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button>
                                    </div>
                                </div>
                                <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"> 
                                    <div class="modal-header">
                                        <div class="navbar-inner-custom">
                                            Enable Confirmation 
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <p> Are you sure you want to Enable? </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="enable_crclm btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </div>
                                </div>
                                <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"> 
                                    <div class="modal-header">
                                        <div class="navbar-inner-custom">
                                            Disable Confirmation
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to Disable?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="disable_crclm btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok</button>
                                        <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                    </div>
                                </div>

                                <!-- Modal for import option -->
                                <form name="import_form" id="import_from" method="post" action="<?php echo base_url('curriculum/import_curriculum/curriculum_import_data'); ?>">
                                    <div id="import_data" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                                        <div class="modal-header">
                                            <div class="navbar">
                                                <div class="navbar-inner-custom">
                                                    Import Data From Previous Curriculum
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal-body">
                                            <div><b>Importing Data For The Curriculum: </b><b id="crclm_name"></b></div></br>
                                            <input type="hidden" id="crclm_id" name="crclm_id" value="" />
                                            <div id="check_box_list" class="tooltip-demo" ></div>
                                        </div>
                                        <div id="error_msg"></div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name="import" id="import" disabled="disabled"><i class="icon-download-alt icon-white"></i> Import</button>
                                            <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                        </div>
                                </form>
                            </div>

                            <!--Modal For import rollback-->
                            <div id="rollback_import" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Roll-Back Confirmation
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body" id="status">
                                    <p><b>All the data entered or imported (such as Course(s) their Lesson Plans and their respective Assessment and Attainment data)<font color="red"> will be deleted permanently from the software </font> for the selected Curriculum. Once deleted, data cannot be retrieved back.</b></p>
                                    <p><b>Kindly be sure about the above instructions and Click Ok to continue to roll-out.</b></p>
                                    <p><b>Enter Login Password: </b><input type="password" name="rollback_pwd" id="rollback_pwd"/></p>
                                    <p><b><span name="rollback_date" id="rollback_date" value=""> </span></b></p>
                                </div>
                                <input type="hidden" name="rollback_crclm_id" id="rollback_crclm_id" value=""/>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary roll_back" data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>

                            <div id="invalid_rollback_pwd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    Invalid password. Please provide valid password and try again.
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                                </div>
                            </div>

                            <!-- Modal to display help content -->
                            <div id="help" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_help" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Curricula guideline files
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="help_content">
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                                </div>
                            </div>

                            <!-- Modal to display credits information (lesser) -->
                            <div id="no_curriculum" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="no_curriculum" data-backdrop="static" data-keyboard="true">
                                <div class="modal-header">
                                    <div class="navbar">
                                        <div class="navbar-inner-custom">
                                            Warning
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    There is no curriculum to import against.
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Close</button> 
                                </div>
                            </div>
                            <!-- OE PI Toggle Modal Mandatory-->
                            <div id="myModal_oe_pi_mandatory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"> 
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('outcome_element_sing_short'); ?> PI Enable Confirmation 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> Are you sure that you want to make <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators mandatory ? </p>
                                    Caution - You won't be able to revert back once <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators(PIs) are made mandatory.
                                    <input type="hidden" id="crclm_id_optional" name="crclm_id_optional" value=""/>
                                </div>
                                <div class="modal-footer">
                                    <button class="mandatory btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>
                            <!-- OE PI Toggle Modal Optional-->
                            <div id="myModal_oe_pi_optional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"> 
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        <?php echo $this->lang->line('outcome_element_sing_short'); ?> PI Disable Confirmation 
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> Are you sure that you want to make <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators optional ? </p>
                                    Caution - You won't be able to revert back once <?php echo $this->lang->line('outcome_element_plu_full'); ?> and Performance Indicators(PIs) are made optional.
                                    <input type="hidden" id="crclm_id_mandatory" name="crclm_id_mandatory" value=""/>
                                </div>
                                <div class="modal-footer">
                                    <button class="optional btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>
                            <!-- Clo Bloom Level Toggle Modal Mandatory-->
                            <div id="myModal_clo_bl_mandatory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"> 
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        Enable Confirmation
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> Are you sure that you want to make <?php echo $this->lang->line('entity_clo_full_singular'); ?> Bloom's Level (s) mandatory ? </p>							
                                    <input type="hidden" id="crclm_id_bloom_mandatory" name="crclm_id_bloom_mandatory" value=""/>


                                </div>
                                <div class="modal-footer">
                                    <button class="bloom_mandatory btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>
                            <!-- Clo Bloom Level Toggle Modal Optional-->
                            <div id="myModal_clo_bl_optional" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false"> 
                                <div class="modal-header">
                                    <div class="navbar-inner-custom">
                                        <?php echo "CLO Bloom Level Enable Confirmation "; ?>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <p> Are you sure that you want to make Bloom Level(s) optional ? </p>							
                                    <input type="hidden" id="crclm_id_bloom_optional" name="crclm_id_bloom_optional" />
                                </div>
                                <div class="modal-footer">
                                    <button class="bloom_optional btn btn-primary " data-dismiss="modal"><i class="icon-ok icon-white"></i> Ok </button>
                                    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button>
                                </div>
                            </div>                                        
                            <input type="hidden" name="stack_counter" id="stack_counter" value="1" />
                        </div>
                        <br/>
                        <br/>
                </div>
            </div>
        </div>
    </div>
    <!---place footer.php here -->
    <?php $this->load->view('includes/footer'); ?> 
    <!---place js.php here -->
    <?php $this->load->view('includes/js'); ?>
</body>
<script language="javascript"  type="text/javascript" src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/curriculum.js'); ?>"></script>
</html> 