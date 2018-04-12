<?php
/*
  ------------------------------------------------------------------------
*Description		: To display the files uploaded in selected curriculum.
*Date			: 3/30/2016
*Author Name		: Bhagyalaxmi S Shivapuji
*Modification History	:

*Date			Modified By 		Description
*
  ------------------------------------------------------------------------*/
?>

<?php
//'.$specializatin[0]['specialization'].'
$table = '<table class="table table-bordered char_font_size_12" id="res_guid">
		<thead>	
			<b> <span data-key="lg_crclm">Specialization </span>:</b>
	   		<tr>
				<th><span data-key="lg_slno"> Sl.No </span></th>
				<th><span data-key="lg_file_name"> File Name </span></th>
				<th><span data-key="lg_desc"> Description </span></th>
				<th><span data-key="lg_date"> Date </span></th>
				<th><span data-key="lg_delete"> Delete </span></th>
	   		</tr>
		</thead>
	  <tbody>';
	 $i = 0;	 
	 foreach($result as $data) {
		   $i++;
		   $file_name = $data['file_name']; 
		   $new_name = substr($file_name, strpos($file_name, "dd_") + 3); 
			$date = date("d-m-Y",strtotime($data['actual_date']));
		   $my_id = $data['uload_id'];
		   //<input style='width:inherit;' type='date' class= 'actual_date_data_detl' name='actual_date[]' id='actual_date_data_detl' value=".$data['actual_date']." /></td>
		   $table .= "<tr>
					<td><input name='my_id_data' id='my_id_data' type='hidden' value=".$data['uload_id'].">".$i."</td>
			       	<td ><a class='cursor_pointer ' href=".base_url()."/uploads/stud_improvement_plan/".$data['file_name']." target='_blank' >".$new_name."</a></td>
					<td><textarea name='res_guid_description[]' id='res_detail_description' class='res_detail_description' row=2 col=5>".$data['description']."</textarea></td>
					<td >
						<div class=''>
							<div class=''>
								<div class='input-append '>									
									<input type='text' class=' datepicker required actual_date_data_detl actual_date_data_res_detl text-right ' name='actual_date[]' id='actual_date_data_detl'  style='width:80px;' value=".$date." readonly>
									<span class='add-on' id='actual_btn'  name='actual_btn' style='cursor:pointer;'><i class='icon-calendar'></i></span>
								</div>
							</div>
						</div>
						
					<td onclick = ''><input name='save_form_data[]' class='save_form_data cursor_pointer' type='hidden' value=".$data['uload_id'].">					
					<a role='button' class='cursor_pointer delete_file' data-id=".$data['uload_id']."  data-sip_id=".$data['sip_id']." ><i class='icon-remove icon-black'></i></a></td>
			      </tr>";
	 }
	$table .= '</tbody></table>';
	echo $table;

?>

<table class="table table-bordered char_font_size_12" >
	<thead>
		<td>
			<b><span data-key="lg_note">Note</span> : </b><span data-key="lg_note_three">  Files allowed are .doc, docx, xls, xlsx, jpg, png, txt, ppt, pptx, pdf, odt, rtf.</span><br>
 				     <?php echo str_repeat("&nbsp;", 10); ?> <span data-key="lg_note_four">Maximum file size allowed is 10MB. </span>	
		</td>
	</thead>
</table>

<!--localization-->
	<script src="<?php echo base_url('twitterbootstrap/js/custom/locale/locale_lang.js'); ?>" type="text/javascript"></script>

