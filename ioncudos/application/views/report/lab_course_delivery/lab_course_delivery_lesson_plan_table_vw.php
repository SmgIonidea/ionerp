<?php
/**
 * Description	:	Generates Laboratory Course Delivery Report

 * Created		:	June 27th, 2015

 * Author		:	Arihant Prasad D

 * Modification History:
 *   Date                Modified By                         Description
 * 
  ------------------------------------------------------------------------ */
?>
<div id="gene_table">
	<h3 style="text-align:center; font-size: 18px;"> Laboratory Plan </h3>
	<h4 class="h_class" style="font-weight:normal; text-align:center;"> Laboratory Course Plan: <?php echo $lab_curriculum_detail[0]['crclm_name']; ?><span id="curriculum_year"> </span></h4>
	<table id="lab_plan" name="lab_plan" class="table table-bordered table-hover" style="width:100%">
		<tbody>
			<?php foreach ($lab_course_details as $lab_details) { ?>
				<tr>
					<td width=400>
						<b class="h_class" style="font-weight:normal;">Laboratory Title: </b><b class="s_class" style="font-weight:bold;"><?php echo $lab_details['crs_title']; ?></b>
					</td>
					<td width=300>
						<b class="h_class" style="font-weight:normal;">Lab. Code: </b><b class="s_class" style="font-weight:bold;"><?php echo $lab_details['crs_code']; ?></b>
					</td>
				</tr>
				<tr>
					<td width=400>
						<b class="h_class" style="font-weight:normal;">Total Hours: </b><b class="s_class" style="font-weight:bold;"><?php echo $lab_details['contact_hours']; ?></b>
					</td>
					<td width=300>
						Duration of SEE Hours: <?php echo $lab_details['see_duration']; ?>
					</td>
				</tr>
				<tr>
					<td width=400>
						<b class="h_class" style="font-weight:normal;">SEE Marks: </b><b class="s_class" style="font-weight:bold;"><?php echo $lab_details['see_marks']; ?></b>
					</td>
					<td width=300>
						<b class="h_class" style="font-weight:normal;">CIE Marks: </b><b class="s_class" style="font-weight:bold;"><?php echo $lab_details['cie_marks']; ?></b>
					</td>
				</tr>
				<?php foreach ($lab_course_owner as $course_owner) { ?>
					<tr>
						<td width=400>
							Lab. Plan Author: <?php echo $course_owner['title'].''.ucfirst($course_owner['first_name']).' '.ucfirst($course_owner['last_name']); ?>
						</td>
						<td width=300>
							Date: <?php echo date('d-m-Y', strtotime($lab_details['create_date'])); ?>
						</td>
					</tr>
				<?php }
				foreach ($lab_course_validator as $course_validator) { ?>
					<tr>
						<td width=400>
							Checked By: <?php echo $course_validator['title'].''.ucfirst($course_validator['first_name']).' '.ucfirst($course_validator['last_name']); ?>
						</td>
						<td width=300>
							Date: <?php echo date('d-m-Y', strtotime($course_validator['last_date'])); ?>
						</td>
					</tr>
				<?php }
			} ?>
		</tbody>
	</table>
	<?php /* course outcomes */ ?>
	<?php if(!empty($lab_course_learning_objectives)) { ?>
	<h4 style="color:green;">Course Outcomes (COs): </h4>
	<span>At the end of the course the student should be able to:</span>
	<ul class="ul_class" style="list-style-type: none;">
		<?php foreach ($lab_course_learning_objectives as $lab_co) { ?>
			<li class="ul_class"><?php echo $lab_co['clo_statement']; ?></li>
		<?php } ?>
	</ul>
	<br breakIt=1 />
	<?php } ?>
	<?php /* co to po mapping */ ?>
	<?php if(!empty($lab_clo_list)) { ?>
	<h4 class="breakt" style="text-align:center;"> Course Articulation Matrix: Mapping of Course Outcomes (CO) with <?php echo $this->lang->line('student_outcomes_full'); ?></h4>
	<table class="table table-bordered table-hover" style="width:100%">
		<tbody>
			<tr>
				<td width=400>
					Course Title: <span style="font-weight:bold;"><?php echo $lab_details['crs_title']; ?></span>
				</td>
				<td width=300>
					Semester: <span style="font-weight:bold;"><?php if(!empty($lab_course_learning_objectives)) { echo $lab_course_learning_objectives[0]['term_name']; } ?></span>
				</td>
			</tr>
			<tr>
				<td width=400>
					Course Code: <span style="font-weight:bold;"><?php echo $lab_details['crs_code']; ?></span>
				</td>
				<td width=300>
					Year: 
				</td>
			</tr>
		</tbody>
	</table>
	<table id="lab_table_view_clo_po" name="lab_table_view_clo_po" class="table table-bordered table-hover" style="width:100%">
		<tbody>
			<tr>
				<th class="sorting1" rowspan="1" colspan="2" width="950" style="width:50%;"><font color="#8E2727">Course Outcomes / <?php echo $this->lang->line('student_outcomes_full'); ?> </font>
				</th>
				<?php foreach ($lab_po_list as $po) { ?>
					<th class="sorting1" rowspan="1" colspan="1" width="200" style="width: 10px; text-align:center;" id="po_stmt"> <font class="ul_class" color="#8E2727"><?php echo $po['po_reference']; ?></font>
					</th>
				<?php } ?>
			</tr>
			<?php foreach ($lab_course_list as $current_course) { ?>
				<?php foreach ($lab_clo_list as $clo) { ?>
					<?php if ($current_course['crs_id'] == $clo['crs_id']) { ?>
						<tr>
							<th width="950" colspan="2" style="width:50%;">
								<h5 class="h_class ul_class" style="font-weight:normal; font-size:12px;"><?php echo trim($clo['clo_statement']); ?></h5>
							</th>
							<?php foreach ($lab_po_list as $po) { ?>
								<th width="200" colspan="1" style="text-align:center">
										<?php $temp = '';
										foreach ($lab_clo_po_map_details as $clo_po_data) {
											if ($clo_po_data['clo_id'] == $clo['clo_id'] && $clo_po_data['po_id'] == $po['po_id']) {
												if ($temp != $clo_po_data['map_level']) {
													$temp = $clo_po_data['map_level'];
													$map_level = $clo_po_data['map_level'];

													switch ($map_level) {
														case 2: ?><?php echo "2"; ?>
															<?php break;

														case 3: ?><?php echo "3"; ?>
														<?php break;

														default: ?><?php echo "1"; ?>
														<?php break;
													}
												}
											}
										} ?>
								</th>
							<?php } ?>
						</tr>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
	<!--<h4 class="h_class" style="text-align:left; font-weight:normal;">Degree of compliance &nbsp;&nbsp;&nbsp; <span class="s_class" style="font-weight:bold">L</span>: Low  &nbsp;&nbsp;&nbsp; <span class="s_class" style="font-weight:bold">M</span>: Medium &nbsp;&nbsp;&nbsp; <span class="s_class" style="font-weight:bold">H</span>: High</h4>-->
	<br breakIt=1 />
	<?php } ?>
	<?php /* OE & PI Title of the Page */ ?>
	<?php if(!empty($lab_oe_pi[0])) { ?>
		<br/><h4 class="ul_class" style="text-align:center;"><?php echo $this->lang->line('outcome_element_sing_full'); ?> addressed in the Course and corresponding Performance Indicators </h4>
		<table class="table table-bordered" style="width:100%">
			<tbody>
				<?php $temp = 0;
				foreach($lab_oe_pi as $data) {
					if($temp != $data['pi_id']) {
						$temp = $data['pi_id'];
						?>
						<tr style="background:#C2C2C2;">
							<td class="row_color" width="280" style="width:15%;">
								<h4 class="font_h ul_class"><?php echo $this->lang->line('outcome_element_sing_full'); ?>:
								<?php echo substr($data['pi_codes'], 0, -2); ?></h4>
							</td>
							<td class="row_color" width="950">
								<h4 class="font_h h_class ul_class" style="font-weight: normal;"><?php echo $data['pi_statement']; ?></h4>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<td width="280" style="width:15%;"><?php echo 'PI Code: ' . $data['pi_codes'] ; ?></td>
						<td width="950"><h4 class="font_h h_class ul_class" style="font-weight: normal;"><?php echo $data['msr_statement'] ; ?></h4></td>					
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<br breakIt=1 />
	<?php } ?>
	<?php if(!empty($category)) { ?>
		<h4 style="text-align:center;"><u> Experiment wise Plan </u></h4>
		<h4 style="text-align:center;"> List of experiments/jobs planned to meet the requirements of the course.</h4>
		<table id="lab_course_delivery_course_plan_table_view" name="lab_course_delivery_course_plan_table_view" class="table table-bordered lab_plan">
			<?php 
			$temp_category = 0;
			$temp_expt_num = 0;
			$index = 0;
			
			foreach($category as $cat) { ?>
				<?php 
				if($cat['mt_details_id'] != $temp_category) {
					$temp_category = $cat['mt_details_id'];
				?>
					<tr style="background:#C2C2C2;">
						<th class="row_color" style="width:33%;" colspan=2 width="200">
							<h4 class="font_h ul_class">Category: <?php echo ' ' . $cat['mt_details_name']; ?></h4>
						</th>
						<th class="row_color" style="width:33%;" colspan=2 width="200">
							<h4 class="font_h ul_class">Total Weightage: <?php echo ' ' . $total_weightage_session[$index]['marks_expt']; ?></h4>
						</th>
						<th class="row_color" width="200" style="width:33%;">
							<h4 class="font_h ul_class">No. of lab sessions: <?php echo ' ' . $total_weightage_session[$index++]['num_of_sessions']; ?></h4>
						</th>
					</tr>
					<tr>
						<th width=50><h4 class="font_h ul_class">Expt./ Job No.</th></h4>
						<th width=150 style="white-space:nowrap;"><h4 class="font_h ul_class">Experiment / Job Details</h4></th>
						<th width=150 ><h4 class="font_h ul_class">No. of Lab Session(s) per batch (estimate)</h4></th>
						<th width="50"><h4 class="font_h ul_class">Marks / Experiment</h4></th>
						<th width=200 style="white-space:nowrap;"><h4 class="font_h ul_class">Correlation of Experiment with the theory</h4></th>
					</tr>
				<?php } ?>

				<?php if($cat['topic_title'] != $temp_expt_num) {
					$temp_expt_num = $cat['topic_title'];
				?>
					<tr>
						<td rowspan=2 width=50><h4 class="h_class font_h ul_class" style="font-weight: normal;"><?php echo $cat['topic_title']; ?></h4></td>
						<td width=150 ><h4 class="h_class font_h ul_class" style="font-weight: normal;"><?php echo $cat['topic_content']; ?></h4></td>
						<td width=150><h4 class="h_class font_h ul_class" style="font-weight: normal;"><?php echo $cat['num_of_sessions']; ?></h4></td>
						<td width="50"><h4 class="h_class font_h ul_class" style="font-weight: normal;"><?php echo $cat['marks_expt']; ?></h4></td>
						<td width=200></td>
					</tr>				
				<?php } ?>
				
				<tr>
					<td style="display:none" width="50"></td>
					<td gridSpan=4 colspan=3 width="350">
						<ul class="ul_class" style="list-style-type:none; margin-left:0px;">
							<p>Learning Outcomes: </p>
							<p class="ul_class">The students should be able to: </p>
						</ul>
						<ol class="ul_class">
							<?php 
								$tlo_stmt = explode('||', $cat['gc_tlo_stmt']);
								$tlo_stmt_size = sizeof($tlo_stmt);
								
								for($i = 0; $i < $tlo_stmt_size; $i++) { ?>
									<li class="ul_class">
										<?php echo $tlo_stmt[$i]; ?>
									</li>
								<?php } ?> 
						</ol>
					</td>
					<td width="200"><?php echo $cat['correlation_with_theory']; ?></td>
				</tr>
			<?php } ?>
		</table>
	<?php } ?>
</div>