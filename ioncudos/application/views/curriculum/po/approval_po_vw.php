<?php
/**
 * Description	:	To display, add and edit Program Outcomes

 * Created		:	March 30th, 2013

 * Author		:	 

 * Modification History:
 *   Date                Modified By                         Description
 * 23-10-2013		   Arihant Prasad			File header, function headers, indentation 
  and comments.
  ---------------------------------------------------------------------------------------------- */
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
	<?php //$this->load->view('includes/static_sidenav_2'); ?>
	<div class="span12">
	    <!-- Contents -->
	    <section id="contents">
		<div class="bs-docs-example">
		    <!--content goes here-->
		    <div class="navbar">
			<div class="navbar-inner-custom">
			    <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> Sent for Approval
			</div>
		    </div>

		    <div id="loading" class="ui-widget-overlay ui-front">
			<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif'); ?>" alt="loading" />
		    </div>

		    <input type="hidden" id="curriculum_list" value="<?php echo $crclm_id; ?>">

		    <div class="span12">
			<label> Curriculum:
			    <select name="curriculum_list" id="curriculum_list" disabled="disabled" value="<?php echo $crclm_id; ?>">
				<option value=""> <?php echo $crclm_name[0]['crclm_name']; ?> </option>
			    </select>
			</label>
		    </div>

		    <input type="hidden" name="crclm_id_rework" id="crclm_id_rework" value="<?php echo $crclm_name[0]['crclm_id']; ?>"/><br><br>
		    <div style="margin-bottom:95px">
			<table class="table table-bordered table-hover " >
			    <tbody role="alert" aria-live="polite" aria-relevant="all" id="list_the_curriculum">
			    <th class="header headerSortDown span1" role="columnheader" tabindex="0" aria-controls="example"> <?php echo $this->lang->line('so'); ?> Reference </th>
			    <th class="header headerSortDown span4" role="columnheader" tabindex="0" aria-controls="example" > <?php echo $this->lang->line('student_outcome_full'); ?>  Statements </th>
			    <th class="header headerSortDown span4" role="columnheader" tabindex="0" aria-controls="example" aria-sort="ascending"> <?php echo $this->lang->line('so'); ?> Type </th>
			    <th class="header span4" rowspan="1" colspan="1" style="width: 140px;" role="columnheader" tabindex="0" aria-controls="example"> Add review comments here </th>

			    <?php $count = 1;
			    foreach ($po_data as $po_list): {
				    ?>
				    <tr>
					<td aria-sort="ascending">
	<?php if ($po_list['pso_flag'] == 0) {
	    echo $po_list['po_reference'];
	} else { ?>  <font color="blue"> <?php echo $po_list['po_reference']; ?> </font> <?php } ?>
					</td>
					<td>
					    <?php if ($po_list['pso_flag'] == 0) {
						echo $po_list['po_statement'];
					    } else { ?>  <font color="blue"> <?php echo $po_list['po_statement']; ?> </font> <?php } ?>
					</td>
					<td>
					    <?php if ($po_list['pso_flag'] == 0) {
						echo $po_list['po_type_name'];
					    } else { ?>  <font color="blue"> <?php echo $po_list['po_type_name']; ?> </font> <?php } ?>
					</td>
					<td>
				    <center><textarea id="po_cmt_<?php echo $count; ?>" name="po_cmt_<?php echo $count; ?>" abbr="<?php echo $po_list['po_id']; ?>" rows="2" cols="5" class="required po_comment_enter" style="margin: 0px 0px 10px; width: 324px; height: 40px;"><?php
				    foreach ($po_comment_result as $po_comment) {
					if ($po_comment['po_id'] == $po_list['po_id']) {
					    echo $po_comment['cmt_statement'];
					} else {
					    
					}
				    }
				    ?></textarea>
					<!--<a href="#" class="icon-comment comment" rel="popover" data-content='
					   <form id="mainForm" name="mainForm">
					   <p>
						   <textarea id="po_cmt" name="po_cmt" rows="4" cols="5" class="required"></textarea>
						   <input type="hidden" name="po_id" id="po_id" value="<?php //echo $po_list['po_id'];  ?>"/>
						   
					   </p>
					   <p>
						   <div class="pull-right">
							   <a class="btn btn-primary comment_submit" href="#"><i class="icon-file icon-white"></i> Save </a>
							   <a class="btn btn-danger close_btn" href="#"><i class="icon-remove icon-white"></i> Close </a>
						   </div>
					   </p>
					   </form>' data-placement="top" data-original-title="Add Comments Here">
					</a>-->
				    </center>
				    </td>

    <?php } endforeach; ?>
			    </tr>
			    </tbody>
			</table>
			<!--Do not place contents below this line-->	
		    </div>

		    <div class="clear">
		    </div>

		    <div class="span11 pull-right" id="enableaddbutton" >
			<button id="bos_publish" onClick="" class="bos_publish_data btn btn-success pull-right"><i class="icon-ok icon-white"></i> Accept</button>
			<button id="rework" class="btn btn-danger pull-right" onClick="rework()" style="margin-right:2px;"><i class="icon-refresh icon-white"></i> Rework</button>
		    </div></br></br>

		    <!-- Modal to display publish confirmation message -->
		    <div id="myModal_publish" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_publish" data-backdrop="static" data-keyboard="false">
			<div class="modal-header">
			    <div class="navbar">
				<div class="navbar-inner-custom">
<?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> approval confirmation
				</div>
			    </div>
			</div>

			<div class="modal-body">
			    <p> <b> Current State: </b> Review of <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> has been completed.</p>
			    <p> <b> Next State: </b> Mapping between <?php echo $this->lang->line('sos'); ?> and PEOs. </p>
			    <p> An email will be sent to Chairman: <b id="chairman_user_accept" style="color:rgb(230, 122, 23);"></b> </p>

			    <h4><center> Current status of curriculum: <b id="crclm_name_accept" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
			    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/po_review_img.png'); ?>">
			</div>

			<div class="modal-align-left">
			    <p> Are you sure you want to approve the <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>? </p>
			</div>

			<div class="modal-footer">
			    <button class="btn btn-primary" data-dismiss="modal" onClick="publish_others();"><i class="icon-ok icon-white"></i> Ok</button> 
			    <button class="btn btn-danger" data-dismiss="modal" onClick=""><i class="icon-remove icon-white"></i> Cancel</button> 
			</div>
		    </div>

		    <!-- Modal to display rework confirmation message -->
		    <div id="myModal_rework" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;" data-controls-modal="myModal_rework" data-backdrop="static" data-keyboard="false">
			<div class="modal-header">
			    <div class="navbar">
				<div class="navbar-inner-custom">
				    Send for rework
				</div>
			    </div>
			</div>

			<div class="modal-body">
			    <p> <b> Current State: </b> Review of <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?> has been completed. </p>
			    <p> <b> Next State: </b> Rework of <?php echo $this->lang->line('student_outcomes_full'); ?> <?php echo $this->lang->line('student_outcomes'); ?>. </p>
			    <p> An email will be sent to Chairman: <b id="chairman_user_name" style="color:rgb(230, 122, 23);"></b> </p>

			    <h4><center> Current status of curriculum: <b id="crclm_name_workflow" style="color:rgb(230, 122, 23); text-decoration: underline;"></b> </center></h4>
			    <img src="<?php echo base_url('/twitterbootstrap/img/modal_workflow_img/po_review_img.png'); ?>">
			</div>

			<div class="modal-align-left">
			    <p> Are you sure you want to send it for rework ? </p>
			</div>

			<div class="modal-footer">
			    <button class="btn btn-primary" data-dismiss="modal" onClick="send_rework();"><i class="icon-ok icon-white"></i> Ok</button> 
			    <button class="btn btn-danger" data-dismiss="modal"><i class="icon-remove icon-white"></i> Cancel</button> 
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
<script src="<?php echo base_url('twitterbootstrap/js/custom/curriculum/po.js'); ?>" type="text/javascript"></script>
