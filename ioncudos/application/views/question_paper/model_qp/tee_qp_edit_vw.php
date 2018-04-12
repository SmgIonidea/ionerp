<?php
/**
* Description	:	Add View for Program Outcomes(POs) Type Module.
* Created		:	03-04-2013. 
* Modification History:
* Date				Modified By				Description
* 03-09-2013		Abhinay B.Angadi        Added file headers, indentations.
* 03-09-2013		Abhinay B.Angadi		Variable naming, Function naming & Code cleaning.
--------------------------------------------------------------------------------------------------------
*/
?>

<!--head here -->
    <?php
    $this->load->view('includes/head');
    ?>
    <!--branding here-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.css'); ?>" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('twitterbootstrap/css/jquery.nailthumb.1.1.min.css'); ?>" media="screen" />
        <?php
        $this->load->view('includes/branding');
        ?>
        <!-- Navbar here -->
        <?php
        $this->load->view('includes/navbar');
        ?> 
        <div class="container-fluid">
            <div class="row-fluid">
                <!--sidenav.php-->
                <?php //$this->load->view('includes/sidenav_1'); ?>
                <div class="span12">
                    <!-- Contents
    ================================================== -->
                    <section id="contents">
                        <div class="bs-docs-example fixed-height">
                            <!--content goes here-->	
                            <div class="navbar">
                                <div class="navbar-inner-custom">
                                   <?php echo $title; ?>
                                </div>
                            </div>	
                            <br>
							<!--<form  class="" method="POST" id="add_form_id" name="add_form_id" action= "">
							<div class="control-group">
							<p class="control-label" for="qp_title">Question Paper Title :</p>
								<div class="controls">
									<textarea name="qp_title" id="qp_title" style="" rows="3" cols="20"></textarea>
								</div>
							</div>
							
                            </form>-->
							<div id="loading" class="ui-widget-overlay ui-front">
											<img style="" src="<?php echo base_url('twitterbootstrap/img/load.gif');?>" alt="loading" />
							</div>
							<form class="form-horizontal" method="POST" id="add_form_id" name="add_form_id" action= "<?php echo base_url('question_paper/manage_model_qp/add_qp_data/5'); ?>">
							  <div class="control-group">
								<p class="control-label" for="inputEmail">Question Paper Title <font class="font_color">*</font> :</p>
								<div class="controls">
								  <textarea class="required qpaper_title" name="qp_title" id="qp_title" style="margin: 0px; width: 889px; height: 60px;" rows="3" cols="20"><?php echo $meta_data[0]['qpd_title'];?></textarea>
								</div>
							  </div>
							  <div class="row-fluid">
								<div class="span12">
									
									<div class="span3">
										  <div class="control-group">
											<label class="control-label" for="inputPassword">Total Duration (H:M)<font class="font_color">*</font> :</label>
											<div class="controls">
											  <input type="text" id="total_duration" name="total_duration" class="input-mini" value="<?php echo $meta_data[0]['qpd_timing'];?>" class="input-mini required total_duration"/>
											</div>
										  </div>
									</div>
									
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" for="inputPassword">Course <font class="font_color">*</font> :</label>
											<div class="controls">
											  <input class="required course_name" type="text" id="course_name" name="course_name" value="<?php echo $meta_data[0]['crs_title'].'  -  ('.$meta_data[0]['crs_code'].')'; ?>" style="width:320px;" readonly />
											</div>
										  </div>
									</div>
									
									<div class="span3">
										  <div class="control-group">
											<label class="control-label" for="course_name">Maximum Marks <font class="font_color">*</font> :</label>
											<div class="controls">
											  <input type="text" id="max_marks" name="max_marks" class="input-mini required max_marks" value="<?php echo $meta_data[0]['qpd_max_marks'];?>"/>
											</div>
										  </div>
									</div>
								</div>
							  </div>
							<div class="control-group">
								<p class="control-label" for="inputEmail">Note <font class="font_color">*</font> :</p>
								<div class="controls">
								  <textarea class="required qp_notes" name="qp_notes" id="qp_notes" style="margin: 0px; width: 889px; height: 40px;" rows="3" cols="20"><?php echo $meta_data[0]['qpd_notes'];?></textarea>
								</div>
							  </div>
					<div id="qp_unit_table">
						 <?php 
						// var_dump($co_data);// Dynamic Loading of UNIT and Questions 
							$unit_counter = 1;
							foreach($unit_def_data as $unit_details){
							$counter = 'a';
						 ?>
							<div class="control-group">
								<div class="span12">
								<div class="controls">
								  <center> 
									  <h4><?php echo $unit_details['qp_unit_code']; ?></h4>
									  <input type="hidden" name="unit_code_<?php echo $unit_counter; ?>" id="unit_code_<?php echo $unit_counter; ?>" value="<?php echo $unit_details['qp_unit_code'];?>" />
									  <input type="hidden" name="unit_id_<?php echo $unit_counter; ?>" id="unit_id_<?php echo $unit_counter; ?>" value="<?php echo $unit_details['qpd_unitd_id'];?>" />
								  </center>
								  <center>
								  <input type="checkbox" name="unit_<?php echo $unit_counter; ?>" id="unit_<?php echo $unit_counter; ?>" class="main_unit" abbr="unit_<?php echo $unit_counter; ?>" value="1"></input> &nbsp; All Questions Mandatory</center>
								</div>
								</div>
							</div>
						
							<div class="row-fluid">
								<div class="span12">
									<div class="span6 ">
										  <div class="control-group pull-left">
											<label class="control-label" for="total_question">Total No. of Questions <font class="font_color">*</font> :</label>
											<div class="controls">
											  <input type="text" id="total_question_<?php echo $unit_counter; ?>" name="total_question_<?php echo $unit_counter; ?>" class="input-mini required total_question" value="<?php echo $unit_details['qp_total_unitquestion']; ?>"/>
											</div>
										  </div>
									</div>
									<div class="span6 ">
										  <div class="control-group pull-right">
											<label class="control-label" for="attempt_question">No. of Questions to Attempt <font class="font_color">*</font> :</label>
											<div class="controls">
											  <input type="text" id="attempt_question_<?php echo $unit_counter; ?>" name="attempt_question_<?php echo $unit_counter; ?>" value="<?php echo $unit_details['qp_attempt_unitquestion']; ?>" class="input-mini required attempt_question total_marks_validation">
											</div>
										  </div>
									</div>
								</div>
							  </div>
								
									<table class="table table-bordered qp_table" >
										<thead>
											<tr>
												<th style="white-space:nowrap;" ></th>
												<th>Question <font class="font_color">*</font></th>
												<th>Upload</th>
												<?php foreach($entity_list as $qp_config){ 
												if($qp_config['entity_id'] == 11 ){?>
												<th>CO <font class="font_color">*</font></th>
												<?php } ?>
												<?php
												if($qp_config['entity_id'] == 6){?>
												<th>PO <font class="font_color">*</font></th>
												<?php } ?>
												<?php
												if($qp_config['entity_id'] == 10){?>
												<th><?php echo $this->lang->line('entity_topic'); ?> <font class="font_color">*</font></th>
												<?php } ?>
												<?php  
												if($qp_config['entity_id'] == 23){?>
												<th>Level <font class="font_color">*</font></th>
												<?php } ?>
												<?php 
												if($qp_config['entity_id'] == 22){?>
												<th>PI Code <font class="font_color">*</font></th>
												<?php } 
												}?>
												<th>Marks <font class="font_color">*</font></th>
											</tr>
										</thead>
										
										<tbody>
						<?php //Generating Main Questions with reference to the QP framework
								$mq_counter = 0;
								$sub_counter = 1;
								$temp = '';
														
								foreach($main_qp_data as $mq_data){ 
								if($temp != $mq_data['qp_mq_code']){
								
										$temp = $mq_data['qp_mq_code'];
											
											$mq_counter++;
											$sub_counter = 1;
											$alpha_counter = 'a';
											$temp_alpha = $alpha_counter;
											$button = '<button class="btn btn-primary add_subque error_add" type="button" id="add_subque_'.$mq_counter.'_'.$sub_counter.'" name="add_subque_'.$mq_counter.'_'.$sub_counter.'" abbr="Q N '.$mq_counter.'-'.$alpha_counter.'"><i class="icon-plus-sign icon-white"></i></button>';
											$ref_div = '';
											
											if($mq_data['qp_mq_flag'] == 1){
											$chkd = 'checked="checked"';
											}else{
											$chkd = '';
											}
											
											$checkbox = '<input type="checkbox" name="unit_'.$unit_counter.'_'.$mq_counter.'" id="unit_'.$unit_counter.'_'.$mq_counter.'" class="unit_'.$unit_counter.'" value="1" '.$chkd.'></input>';
											
											}else{
											$temp_main = '';
											$sub_counter++;
											$alpha_counter++;
											$temp_alpha = $alpha_counter;
											
											$button = '<button class="btn btn-danger delete_subque error_add" type="button" id="delete_subque_'.$mq_counter.'_'.$sub_counter.'" name="delete_subque_'.$mq_counter.'_'.$sub_counter.'" abbr="QNO_'.$mq_counter.'_'.$sub_counter.'"><i class="icon-minus-sign icon-white"></i></button>';
											
											$checkbox = '&nbsp;&nbsp;&nbsp;&nbsp;';
											
											$script  =	'<script>';
											$script .=	'$(document).ready(function(){';
											$script .=	'var j;';
											$script .=	'var que_name = $("#ques_nme_'.$mq_counter.'_'.$sub_counter.'").val();';
											$script .=	'$("#add_subque_'.$mq_counter.'_1").attr("abbr",que_name);';
											$script .=	'var counter_val = "'.$mq_counter.'_'.$sub_counter.'" ; ';
											$script .= 'temp_array.push('.$mq_counter.'+"_"+'.$sub_counter.');';
											$script .= 'temp_val = $.unique(temp_array);';
											$script .= 'console.log(temp_val.length);';
											$script .=	'register_button(counter_val)';
											$script .=	'})';
											$script .=	'</script>';
											echo $script;
											}
											
								if($mq_data['qp_unitd_id'] == $unit_details['qpd_unitd_id'])
									{
									?>
											<tr id= "row_<?php echo $mq_counter; ?>_<?php echo $sub_counter; ?>">
												<td style="white-space:nowrap;" class="textwrap">
												<?php echo $checkbox;?>
													 &nbsp; <input type="text" name="ques_nme_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="ques_nme_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" value="<?php echo 'Q No '.$mq_counter.'-'.$alpha_counter; ?>" class="input-mini ques_nme_<?php echo $mq_counter;?>" readonly />
													 <input type="hidden" name="question_name_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="question_name_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" value="<?php echo $mq_data['qp_subq_code']; ?>" class="input-mini question_name_<?php echo $mq_counter;?>" readonly />
												</td>
												<td style="width: 43%;">
													<textarea class="required text_area" name="question_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="question_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" style="margin: 0px; width: 551px; height: 40px;" ><?php echo $mq_data['qp_content'];?></textarea>
													<input type="hidden" name="sub_que_count<?php echo $mq_counter;?>" id="sub_que_count<?php echo $mq_counter;?>" value="<?php echo $sub_counter;?>" class="sub_que_count<?php echo $mq_counter;?>"/>
													<!--<input type="text" name="sub_que_stack<?php //echo $mq_counter;?>" id="sub_que_stack<?php //echo $mq_counter;?>" value="1"/>-->
												</td>
												<td>
													<!--<button type="button" name="upload" id="upload"  class="btn btn-primary"><i class="icon-upload icon-white"></i></button>-->
													
														<button type="button" id="upload-btn<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" name="upload"  abbr="1" class=" btn btn-success btn-small clearfix test" value=""><i class="icon-upload icon-white"></i></button>
														<!--<div id="image_show_1" class="form-horizontal span12" style=" margin-left:90px;">		
														</div>-->	
														<!--<div id="image_append_1" class="controls span12" style=" margin-left:90px;"></div>-->
														<!--<div id="errormsg" class="clearfix redtext">
														</div>	              
														<div id="pic-progress-wrap" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;">
														</div>	
														
														<div id="picbox" class="clear" style="padding-top:0px;padding-bottom:10px;">
														</div>-->
								
												</td>
												<?php
												
												$mapping_data = explode(',',$mq_data['mapping_data']);
												if(isset($mapping_data[0])){
												$co_id = explode(':',$mapping_data[0]);
												}
												if(isset($mapping_data[1])){
												$topic_id = explode(':',$mapping_data[1]);
												}
												if(isset($mapping_data[2])){
												$bloom_id  = explode(':',$mapping_data[2]);
												}
												if(isset($mapping_data[3])){
												$po_id = explode(':',$mapping_data[3]);
												}
												if(isset($mapping_data[4])){
												$picode_data = explode(':',$mapping_data[4]);
												}
												
												foreach($entity_list as $qp_entity_config){ 
													if($qp_entity_config['entity_id'] == 11){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="co_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="co_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="input-small co_onchange required co_list_data">
																	<option value="" abbr="Select CO">Select</option>
																	<?php foreach($co_data as $co) {
																	?>
																	<option value="<?php echo $co['clo_id']; ?>" abbr="<?php echo $co['clo_statement']; ?>"
																	<?php if($co['clo_id'] == $co_id[1]){
																	echo "selected=selected"; } ?> >
																	<?php echo $co['clo_code']; ?>
																	</option>
																	<?php } ?>
															</select>
														</td>
												<?php } ?>
												
												<?php 
													if($qp_entity_config['entity_id'] == 6 ){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="po_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="po_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="input-small required po_list_data">
																	<option value="" abbr="Select CO">Select</option>
																	<?php foreach($co_data as $po_data) {
																	if($po_data['clo_id'] == $co_id[1]){
																	$co_id_data = explode('|',$po_data['po_data']);
																	$size = sizeof($co_id_data);
																	for($i = 0; $i< $size; $i++){
																	$po_val[$i] = explode(':',$co_id_data[$i] ) ;	
																	
																
																	?>
																	<option value="<?php echo $po_val[$i][0]; ?>" abbr="<?php echo $po_val[$i][2]; ?>"
																	<?php if($po_val[$i][0] == $po_id[1]){
																	echo "selected=selected"; } ?> >
																	<?php echo $po_val[$i][1]; ?>
																	</option>
																	<?php }
																	}
																		}?>
															</select>
														</td>
												<?php } ?>
												<?php 
													if($qp_entity_config['entity_id'] == 10 ){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="topic_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="topic_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="input-small topic_list_data required">
																	<option value="" abbr="Select <?php echo $this->lang->line('entity_topic'); ?>">Select</option>
																	<?php foreach($topic_data as $topic) {
																	?>
																	<option value="<?php echo $topic['topic_id']; ?>" 
																	<?php if($topic['topic_id'] == $topic_id[1]){
																	echo "selected=selected"; } ?>  abbr="<?php echo $topic['topic_title']; ?>">
																	<?php echo $topic['topic_title']; ?>
																	</option>
																	<?php } ?>
															</select>
														</td>
												<?php } ?>
												<?php if($qp_entity_config['entity_id'] == 23 ){ ?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="bloom_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="bloom_list_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="input-small required bloom_list_data">
																	<option value="" abbr="Select Level">Select</option>
																	<?php foreach($bloom_data as $blevel) {
																	?>
																	<option value="<?php echo $blevel['bloom_id']; ?>" 
																	<?php if($blevel['bloom_id'] == $bloom_id[1]){
																	echo "selected=selected"; } ?> abbr="<?php echo $blevel['bloom_actionverbs']; ?>">
																	<?php echo $blevel['level']; ?>
																	</option>
																	<?php } ?>
															</select>
														</td>
												<?php } ?>
												<?php if($qp_entity_config['entity_id'] == 22){?>
														<td>
															<select onmouseover="tool_tip_on(this.id);" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="" name="pi_code_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="pi_code_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="input-small pi_code_data">
																	<option value="" abbr="Select PI Code">Select</option>
																	<?php foreach($pi_list as $pi_data){
																	?>
																	<option value="<?php echo $pi_data['msr_id']; ?>" 
																	<?php if($pi_data['msr_id'] == $picode_data[1]){
																	echo "selected=selected"; } ?> abbr="<?php echo $pi_data['msr_statement']; ?>">
																	<?php echo $pi_data['pi_codes']; ?>
																	</option>
																	<?php } ?>
															</select>
														</td>
												<?php }
													}?>
												
												<td>
												<div class=" input-append">
													<input type="text" name="mq_marks_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" id="mq_marks_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" class="input-mini required mq_marks" value="<?php echo $mq_data['qp_subq_marks']; ?>"/>
													<?php echo $button; ?>
													
													</div>
												</td>
											</tr>
											<tr id="img_placing_row_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>">
												<td id=""></td>
												<td id="place_img_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" colspan="6">
												<?php 
												$img_temp = '';
												if($img_temp != $mq_data['qp_mq_code']){
												$img_temp = $mq_data['qp_mq_code'];
												if(isset($mq_data['image_name'])){
												$image_names = explode(",",$mq_data['image_name']);
												$img_size = sizeof($image_names);
												for($img = 0 ; $img < $img_size; $img++){
												$img_counter = $img;
												$img_counter++;
												
												$thumb_div  = '<div class="controls span1" id="img_thmb_'.$mq_counter.'_'.$sub_counter.'_'.$img_counter.'">';
												$thumb_div .= '<table class=""><tr><td><img src="'.base_url().'/uploads/'.$image_names[$img].'" class="img-rounded img-thumbnail" />';
												$thumb_div .= '<i id="romove_image'.$mq_counter.'_'.$sub_counter.'_'.$img_counter.'" class="icon-remove image_remove img_float_rght cursor_pointer">';
												$thumb_div .= '</i></td></tr></table></div><div class="img_margin"></div>';
												echo $thumb_div;
												}
												}
												}else{
												if(isset($mq_data['image_name'])){
												$image_names = explode(",",$mq_data['image_name']);
												$img_size = sizeof($image_names);
												for($img = 0 ; $img < $img_size; $img++){
												$img_counter = $img;
												$img_counter++;
												$thumb_div  = '<div class="controls span1" id="img_thmb_'.$mq_counter.'_'.$sub_counter.'_'.$img_counter.'">';
												$thumb_div .= '<table class=""><tr><td><img src="'.base_url().'/uploads/'.$image_names[$img].'" class="img-rounded img-thumbnail" />';
												$thumb_div .= '<i id="romove_image'.$mq_counter.'_'.$sub_counter.'_'.$img_counter.'" class="icon-remove image_remove img_float_rght cursor_pointer">';
												$thumb_div .= '</i></td></tr></table></div><div class="img_margin"></div>';
												echo $thumb_div;
												}
												}
												
												}
												?>
												</td>
											</tr>
											<tr id="img_name_text_fields_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>">
											<td id="placed_img_name_<?php echo $mq_counter;?>_<?php echo $sub_counter;?>" colspan="8">
											<?php 
											
											$img_nme_temp = '';
												if($img_nme_temp != $mq_data['qp_mq_code']){
												$img_nme_temp = $mq_data['qp_mq_code'];
												if(isset($mq_data['image_name'])){
												$image_names_array = explode(",",$mq_data['image_name']);
												$img_size = sizeof($image_names_array);
												for($k = 0 ; $k < $img_size; $k++){
												$img_nme_conter = $k;
												$img_nme_conter++;
												$image ='<input name="image_hidden_'.$mq_counter.'_'.$sub_counter.'[]" id="image_hidden_'.$mq_counter.'_'.$sub_counter.'_'.$img_nme_conter.'" type="hidden" class="input-small" value="'.$image_names_array[$k].'"/>';
												echo $image;
												}
												$image_count ='<input name="image_count_'.$mq_counter.'_'.$sub_counter.'" id="image_count_'.$mq_counter.'_'.$sub_counter.'" type="hidden" class="input-small" value="'.$k.'"/>';
												echo $image_count;
												}else{
												$image_count ='<input name="image_count_'.$mq_counter.'_'.$sub_counter.'" id="image_count_'.$mq_counter.'_'.$sub_counter.'" type="hidden" class="input-small" value="0"/>';
												echo $image_count;
												}
												}else{
												if(isset($mq_data['image_name'])){
												$image_names_array = explode(",",$mq_data['image_name']);
												$img_size = sizeof($image_names_array);
												for($k = 0 ; $k < $img_size; $k++){
												$img_nme_conter = $k;
												$img_nme_conter++;
												$image ='<input name="image_hidden_'.$mq_counter.'_'.$sub_counter.'[]" id="image_hidden_'.$mq_counter.'_'.$sub_counter.'_'.$img_nme_conter.'" type="hidden" class="input-small" value="'.$image_names_array[$k].'"/>';
												echo $image;
												}
												$image_count ='<input name="image_count_'.$mq_counter.'_'.$sub_counter.'" id="image_count_'.$mq_counter.'_'.$sub_counter.'" type="hidden" class="input-small" value="'.$k.'"/>';
												echo $image_count;
												}
												
												}
											?>
											</td>
											</tr>
											<tr id="sub_que_ref_div_<?php echo $mq_counter;?>"  class="sub_que_ref_div_<?php echo $mq_counter;?>"></tr>
											<?php 
											
											} 
											 //$mq_counter++; 
											 }?>
										
										</tbody>
									</table>
									
									<input type="hidden" name="unit_total_marks_<?php echo $unit_counter; ?>" id="unit_total_marks_<?php echo $unit_counter; ?>" value="<?php echo $unit_details['qp_utotal_marks'];?>"  class="input-mini"/>
						<?php
						 $counter++; 
						 $unit_counter++;
						 } ?>
					</div>	
					<input type="hidden" name="total_counter" id="total_counter" value="<?php echo $mq_counter;?>" />
					<input type="hidden" name="array_data" id="array_data" value="" />
					<input type="hidden" name="unit_counter" id="unit_counter" value="<?php echo --$unit_counter;?>" />
					<input type="hidden" name="qpf_id" id="qpf_id" value="<?php echo $meta_data[0]['qpf_id'];?>" />
					<input type="hidden" name="qpd_id" id="qpd_id" value="<?php echo $meta_data[0]['qpd_id'];?>" />
					<input type="hidden" name="crclm_id" id="crclm_id" value="<?php echo $crclm_id;?>" />
					<input type="hidden" name="term_id" id="term_id" value="<?php echo $term_id;?>" />
					<input type="hidden" name="crs_id" id="crs_id" value="<?php echo $crs_id;?>" />
					
					<div class="pan12">
					<div class="control-group">
					<div class=" controls">
					<div class="form-inline pull-right">
					<label for="qp_max_marks"><b>Grand Total Marks</b></label>
					<input type="text" id="qp_max_marks" name="qp_max_marks" class="input-mini required max_marks" value="<?php echo $qp_mq_data[0]['qpf_gt_marks'];?>" readonly />
					</div>		
					</div>		
					</div>		
					</div>
					
						<div id="button_div" class="pull-right">
						<button type="button" name="save_data" id="save_data" class="btn btn-primary"><i class="icon-ok icon-white"></i>Update</button>
						<a href= "<?php echo base_url('question_paper/tee_qp_list'); ?>"><b class="btn btn-danger"><i class="icon-remove icon-white"></i><span></span> Close</b></a>
						</div>
						<br>
						</form>
                    </div>
                        <!--Do not place contents below this line-->
				</section>
			</div>
                </div>
            </div>
        </div>
        <!---place footer.php here -->
        <?php $this->load->view('includes/footer'); ?> 
        <!---place js.php here -->
        <?php $this->load->view('includes/js'); ?>
		
		<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/SimpleAjaxUploader.min.js'); ?> "> </script>
	<script type="text/javascript" src = "<?php echo base_url('twitterbootstrap/js/custom/curriculum/jquery.nailthumb.1.1.js'); ?> "> </script>
		<script src="<?php echo base_url('twitterbootstrap/js/custom/question_paper/tee_qp_edit.js'); ?>" type="text/javascript"> </script>