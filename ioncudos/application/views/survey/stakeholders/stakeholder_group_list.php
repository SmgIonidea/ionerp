<?php ?>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <!-- Span6 starts here-->
            <div class="row">                
                <?php echo anchor('survey/stakeholders/add_stakeholder_group_type', "<i class='icon-plus-sign icon-white'></i>Add", array('class' => 'btn btn-primary pull-right')); ?>
            </div>

            <br>										
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="twitterbootstrap/img/load.gif" alt="loading" />
            </div>

            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                <div class="success">
                    <?php //echo $this->session->flashdata('stk_grp_sts_msg_success'); ?>
                </div>
                <div class="error">
                    <?php echo $this->session->flashdata('stk_grp_sts_msg_error'); ?>
                </div>
                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                    <thead>
                        <tr role="row">                            
                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >StakeholderGroup Title</th>
                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Description</th>
                            <th class="header span1">Edit</th>

                            <th class="header" style="width:50px;">Status</th>
                        </tr>
                    </thead>

                    <tbody role="alert" aria-live="polite" aria-relevant="all">

                        <?php
                        //print_r($list);

                        foreach ($list as $listData):
                            ?>
                            <tr class="gradeU even">                                
                                <td class="sorting_1"><?php echo $listData['title']; ?> </td>
                                <td class="sorting_1">
                                    <?php echo ($listData['description']) ? $listData['description'] : 'No description provided.'; ?>
                                </td>			
                                <td>
                                    <center>
                                        <?php echo anchor("survey/stakeholders/edit_stakeholder_group_type/$listData[stakeholder_group_id]", "<i class='icon-pencil'></i>"); ?>
                                    </center>
                                </td>	
                                <td>
                                    <center>
                                        <?php
                                        echo ($listData['status'] == 0) ? anchor("#myModalenable", "<i class='icon-ok-circle'></i>","data-toggle='modal' class='modal_action_status' sts='1' title='Click to enable' id= modal_$listData[stakeholder_group_id]") : anchor("#myModaldisable", "<i class='icon-ban-circle'></i>","data-toggle='modal' class='modal_action_status' sts='0' title='Click to disable' id= modal_$listData[stakeholder_group_id]");                                        
                                        ?>
                                    </center>
                                </td>                         
                            </tr>
                        <?php endforeach; ?>                                                 

                    </tbody>
                </table>
            </div> <!-- End of div class="dataTables_wrapper" -->							

        </div> <!-- End of div class="row-fluid" -->

        <div class="pull-right">
            <div class="row">
                <?php echo anchor('survey/stakeholders/add_stakeholder_group_type', "<i class='icon-plus-sign icon-white'></i>Add", array('class' => 'btn btn-primary pull-right')); ?>
            </div>            

            <!-- Modal to confirm before enabling a user -->
            <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Enable Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure that you want to enable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable-stk-grp-type-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>

            <!-- Modal to confirm before disabling a user -->
            <div id="myModaldisable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Disable Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure that you want to disable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable-stk-grp-type-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>				

            <!-- Modal to display delete confirmation message -->
            <div id="myModal_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal_delete" data-backdrop="static" data-keyboard="true"></br>
                <div class="container-fluid">
                    <div class="navbar">
                        <div class="navbar-inner-custom">
                            Delete Confirmation
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <p> Are you sure that you want to delete the Stakeholder Type? </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_st_type();"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                </div>
            </div>

        </div>
    </div>  <!-- End of Span12 -->
</div> <!-- End of div class="row-fluid" -->