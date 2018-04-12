<?php ?>
<div class="row-fluid">
    <div class="span12">
        <div class="row-fluid">
            <!-- Span6 starts here-->
            <div class="row">
                <?php echo anchor('survey/stakeholders/add_stakeholder_group_type', "<i class='icon-plus-sign icon-white'></i>Add", array('class' => 'btn btn-primary pull-right'));?>
            </div>

            <br>										
            <div id="loading" class="ui-widget-overlay ui-front">
                <img style="" src="twitterbootstrap/img/load.gif" alt="loading" />
            </div>

            <div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
                <table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
                    <thead>
                        <tr role="row">
                            <th class="header headerSortDown" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending" >Sl No.</th>
                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >StakeholderGroup Title</th>
                            <th class="header" role="columnheader" tabindex="0" aria-controls="example" >Description</th>
                            <th class="header span1">Edit</th>

                            <th class="header" style="width:150px;">Status</th>
                        </tr>
                    </thead>

                    <tbody role="alert" aria-live="polite" aria-relevant="all">

                        <?php
                        @$sino = 1;
                        $stakeholderType_list_result = array();
                        foreach ($stakeholderType_list_result as $stakeholder_list):
                            ?>
                            <tr class="gradeU even">
                                <td class="sorting_1"><?php echo @$sino++; ?> </td>
                                <td class="sorting_1"><?php echo @$stakeholder_list['type']; ?> </td>
                                <td class="sorting_1"><?php
                                    if (@$stakeholder_list['description'] != "")
                                        echo @$stakeholder_list['description'];
                                    else
                                        echo "No description provided.";
                                    ?>
                                </td>			
                                <td>
                                    <center>
                                        <?php echo anchor("survey/stakeholders/add_stakeholder_type/@$stakeholder_list[st_type_id]", "<i class='icon-pencil'></i>");?>                                        
                                    </center>
                                </td>	
                                <td>
                                    <center>
                                        <?php 
                                            ($stakeholder_list['status']==0)
                                            ?"<a data-toggle='moda' href='#myModalenable' class='get_id'    id='".@$stakeholder_list['st_type_id']."'><i class='icon-ok-circle'></i> </a>"
                                            :"<a data-toggle='moda' href='#myModaldisable' class='get_id' id='".@$stakeholder_list['st_type_id']."'><i class='icon-ban-circle'></i> </a>";
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
                <?php echo anchor('survey/stakeholders/add_stakeholder_group_type', "<i class='icon-plus-sign icon-white'></i>Add", array('class' => 'btn btn-primary pull-right'));?>
            </div>            

            <!-- Modal to confirm before enabling a user -->
            <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Enable Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure  you want to enable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
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
                    <p> Are you sure  you want to disable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
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
                    <p> Are you sure you want to delete the Stakeholder Type? </p>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" Onclick="javascript:delete_st_type();"> <i class="icon-ok icon-white"></i> Ok</button>
                    <button type="reset" class="cancel btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"> </i> Cancel</button>
                </div>
            </div>

        </div>
    </div>  <!-- End of Span12 -->
</div> <!-- End of div class="row-fluid" -->