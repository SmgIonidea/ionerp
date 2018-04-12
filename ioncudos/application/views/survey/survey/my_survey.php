<?php ?>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <div class="success"><?php echo @$this->session->flashdata('template_msg_success'); ?></div>
                    <div class="error"><?php echo @$this->session->flashdata('template_msg_error'); ?></div>
                    <!-- Span6 starts here-->
                    <div class="span12">
                        <div class="control-group ">
                            <div style="position:relative; display:inline-block;" >
                                <label>	
                                    Old Survey: <input type="checkbox" name="old_survey" id="old_survey" value="1" onclick="old_survey();"/>
                                </label>
                            </div>
                            <div class="pull-right" >
                                <label>	
                                    Survey Type: 

                                    <select size="1" class="font-class" id="survey_type" name="survey_type" aria-controls="example" onchange="select_survey_type();" style="width:215px;" >
                                        <option value="0" >Select Survey type</option>
                                        <option value="1" >Fresh Survey</option>
                                        <option value="2" >Feedback Survey</option>
                                    </select>

                                </label>
                            </div>
                        </div>
                    </div>

                    <?php //var_dump(@$survey_data); ?>

                    <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                        <thead>
                            <tr role="row">
                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Survey Title</th>
                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Program</th>
                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Start Date</th>
                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >End Date</th>
                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Status</th>
                                <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Survey Type</th>
                                <th class="header span1">Edit</th>
                                <th class="header span1">Delete</th>
                            </tr>
                        </thead>
                        <tbody role="alert" aria-live="polite" aria-relevant="all">
                            <?php
                            $survey_list[0] = array('survey_id'=>1,'title'=>'test title1', 'program'=>'test program', 'start', 'end', 'status'=>0, 'survey_type', 'edit', 'delete');
                            $survey_list[1] = array('survey_id'=>2,'title'=>'test title1', 'program'=>'test program', 'start', 'end', 'status'=>1, 'survey_type', 'edit', 'delete');
                            $survey_list[2] = array('survey_id'=>2,'title'=>'test title1', 'program'=>'test program', 'start', 'end', 'status'=>2, 'survey_type', 'edit', 'delete');
                            foreach ($survey_list as $listData):
                                ?>
                                <tr class="gradeU even">                                
                                    <td class="sorting_1"><?php echo @$listData['title']; ?> </td>
                                    <td class="sorting_1">
                                        <?php echo anchor("survey/surveys/view_survey/$listData[survey_id]", "<i class='icon-plus-sign icon-white'></i>$listData[program]", array('class' => 'pull-center'));?>
                                    </td>
                                    <td class="sorting_1"><?php echo @$listData['start']; ?></td>
                                    <td class="sorting_1"><?php echo @$listData['end']; ?></td>
                                    <td>
                            <center>
                                <?php
                                echo ($listData['status'] == 0) 
                                    ?anchor("#myModal_Initiate", "Initiate", "data-toggle='modal' class='modal_action_status' sts='1' title='Click to enable' id= modal_ini_$listData[survey_id]") 
                                    : ($listData['status'] == 1)
                                        ?anchor("#myModal_open", "Open", "data-toggle='modal' class='modal_action_status' sts='0' title='Click to disable' id= modal_opn_$listData[survey_id]")
                                        :"Close";
                                ?>
                            </center>
                            </td>                                    
                            <td class="sorting_1"><?php echo @$listData['survey_type']; ?></td>
                            <td>
                            <center>
                                <?php echo anchor("survey/surveys/edit_survey/$listData[survey_id]", "<i class='icon-pencil'></i>"); ?>
                            </center>
                            </td>
                            <td>
                            <center>
                                <?php echo anchor("survey/surveys/delete_tsurvey/$listData[survey_id]", "<i class='icon-remove'></i>"); ?>
                            </center>
                            </td>                                                     
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>


                </div>
                <div class="pull-right">


                    <br><br>

                    <!-- Modal to display Close survey confirmation message -->
                    <div id="myModal_open" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Close Survey Confirmation
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p> Are you sure that you want to close the Survey? </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:close_survey();"> <i class="icon-ok icon-white"></i> Ok</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                        </div>
                    </div>

                    <!-- Modal to display Initiate survey confirmation message -->
                    <div id="myModal_Initiate" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Initiate Survey Confirmation
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p> Are you sure that you want to Initiate the Survey? </p>
                        </div>

                        <div class="modal-footer">
                            <a role="button"  class="btn btn-primary " id="initiate" href="#myModal" data-toggle="modal" data-dismiss="modal" aria-hidden="true" > <i class="icon-ok icon-white"></i> Ok</a>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                        </div>
                    </div>
                    <!-- Modal to confirm before enabling a user -->
                    <div id="myModal_survey_enable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Enable Confirmation
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure that you want to enable? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary enable-survey-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>

                    <!-- Modal to confirm before disabling a user -->
                    <div id="myModal_survey_disable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-header">
                            <div class="navbar-inner-custom">
                                Disable Confirmation
                            </div>
                        </div>
                        <div class="modal-body">
                            <p> Are you sure that you want to disable? </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary disable-survey-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                            <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                        </div>
                    </div>
                    <div id="myModal_no_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_no_edit" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Edit status
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p> Survey is closed. It cant be edited. </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="initiate" > <i class="icon-ok icon-white"></i> Ok</button>
                        </div>
                    </div>
                    <div id=container >

                    </div>
                    <!-- Modal to display Delete survey confirmation message -->
                    <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                        <div class="container-fluid">
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                    Delete Survey Confirmation
                                </div>
                            </div>
                        </div>

                        <div class="modal-body">
                            <p> Are you sure that you want to delete the Survey? </p>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="delete"  Onclick="javascript:delete_survey();" > <i class="icon-ok icon-white"></i> Ok</button>
                            <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                        </div>
                    </div>

                    <div id="question" >
                        <?php ?>
                    </div>
                    <div id="myModal" data-backdrop="static" data-keyboard="false" class="modal1 hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true">

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>