<?php

?>
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid"><!-- Span6 starts here-->
			<div class="row">
				<?php 
					echo anchor('survey/answer_templates/add_answer_template','<i class="icon-plus-sign icon-white"></i>Add',array('class'=>'btn btn-primary pull-right'));
				?>
			</div><br />
			<div id="loading" class="ui-widget-overlay ui-front">
	                <img style="" src="twitterbootstrap/img/load.gif" alt="loading">
	            </div>
			<div id="example_wrapper" class="dataTables_wrapper" role="grid" style="margin-bottom:88px">
				<table class="table table-bordered table-hover" id="example" aria-describedby="example_info">
					<thead>
						<tr role="row">
						<th class="header" role="columnheader" tabindex="0" aria-controls="example" >Survey Template Name</th>
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
	                                <td class="sorting_1"><?php echo $listData['name']; ?> </td>
	                                
	                                <td>
	                                	<center>
	                                        <?php echo anchor("survey/answer_templates/edit_answer_template/$listData[answer_template_id]", "<i class='icon-pencil'></i>"); ?>
	                                	</center>
	                                </td>
	                                <td>
	                                	<center>
	                                        <?php
	                                        echo ($listData['status'] == 0) ? anchor("#myModalenable", "<i class='icon-ok-circle'></i>","data-toggle='modal' class='modal_action_status' sts='1' title='Click to enable' id= modal_$listData[answer_template_id]") : anchor("#myModaldisable", "<i class='icon-ban-circle'></i>","data-toggle='modal' class='modal_action_status' sts='0' title='Click to disable' id= modal_$listData[answer_template_id]");                                        
	                                        ?>
	                                    </center>
	                                </td>
	                            </tr>
	                        <?php endforeach; ?>
	                        
					</tbody>
				</table>
			</div>
			
			<div class="row">
				<?php 
					echo anchor('survey/answer_templates/add_answer_template','<i class="icon-plus-sign icon-white"></i>Add',array('class'=>'btn btn-primary pull-right'));
				?>
			</div>
			
			<!-- Modal to confirm before enabling a user -->
            <div id="myModalenable" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-controls-modal="myModal1" data-backdrop="static" data-keyboard="false">
                <div class="modal-header">
                    <div class="navbar-inner-custom">
                        Enable Confirmation
                    </div>
                </div>
                <div class="modal-body">
                    <p> Are you sure you want to Enable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary enable-answer-template-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
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
                    <p> Are you sure you want to Disable? </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary disable-answer-template-ok" data-dismiss="modal" aria-hidden="true"><i class="icon-ok icon-white"></i> Ok</button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-white"></i> Cancel</button>
                </div>
            </div>
		</div>
	</div>
</div>