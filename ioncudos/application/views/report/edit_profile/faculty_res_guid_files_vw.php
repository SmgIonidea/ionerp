<?php
/*
  ------------------------------------------------------------------------
*Description		: To display the files uploaded in selected curriculum.
*Date			: 3/30/2016
*Author Name		: Bhagyalaxmi S Shivapuji
*Modification History	:

*Date			Modified By 		Description
* ------------------------------------------------------------------------$*/
?>

<?php
$h="";
$table = '<table class="table table-bordered char_font_size_12" id="res_guid">
		<thead>	
			<b> <span data-key="lg_crclm">'.$section_name.' </span></b>  '.$h.' <span> <input type="hidden" name="fetch_approve" id="fetch_approve" value="" /></span>
	   		<tr>
				<th class="nowrap">  Select </th>
				<th><span data-key="lg_slno"> Sl No. </span></th>
				<th><span data-key="lg_file_name"> File Name </span></th>
				<th><span data-key="lg_desc"> Description </span></th>
				<th><span data-key="lg_date"> Date </span></th>
				<th><span data-key="lg_delete"> Delete </span></th>
	   		</tr>
		</thead>
	  <tbody>';
	 $i = 0;
	if(!empty($result)){
	 foreach($result as $data) {

		   $i++;
		   $file_name = $data['file_name']; 
		   $new_name = substr($file_name, strpos($file_name, "dd_") + 3); 
		   $folder_name = $data['table_name'];
		   $user = $data['user_id']; 
		   $my_id = $data['uload_id'];
		   $date = date("d-m-Y",strtotime($data['actual_date']));
		   if($data['approved_file'] == '1'){$data_val = "checked";}else{$data_val = "";}
		   
		   $table .= "<tr id='test'>
		   			<td><input type='checkbox' name='approve_file[]' id='approve_file' value='' ".$data_val." /></td>
					<td><input name='my_id_data' id='my_id_data' type='hidden' value=".$data['uload_id'].">".$i."</td>
			       	<td><a href=". base_url() ."uploads/upload_faculty_contribution/".$main_folder."/".$folder_name."/".$file_name." target='_blank' >".$new_name."</a></td>				
					<td><textarea name='res_guid_description[]' id='res_description' class='res_description_data' row=2 col=5>".$data['description']."</textarea></td>
					<td>
						<div class='control-group'>
							<div class='controls'>
								<div class='input-append '>									
									<input type='text' class='datepicker required actual_date_data_fg' name='actual_date[]' id='actual_date'  style='width:100px;' value=".$date." readonly>
									<span class='add-on actual_date_data_fg' id='actual_btn' style='cursor:pointer;'><i class='icon-calendar'></i></span>
								</div>
							</div>
						</div>
					</td>
					<td onclick = 'delete_file($user,$my_id)' ><input name='save_form_data[]' class='save_form_data cursor_pointer' type='hidden' value=".$data['uload_id'].">
					<input type='hidden' name='user_id_res' id='user_id_res' value='".$user."'/>
					<a role='button' class='cursor_pointer delete_file' data-id=".$data['uload_id']." data-user_id=".$data['user_id']." ><i class='icon-remove icon-black'></i></a></td>
			      </tr>";
	 }
	}else{
	$table .= '<tr>
				<td class="nowrap"> </td>
				<td><span data-key="lg_slno"> No Data to Display </span></td>
				<td><span data-key="lg_file_name"> </span></td>
				<td><span data-key="lg_desc"> </span></td>
				<td><span data-key="lg_date"> </span></td>
				<td><span data-key="lg_delete"> </span></td>
	   		</tr>';
	}
	$table .= '</tbody></table>';
	$section =  $section_name;
	echo $table;
	
?>
<?php if(($upload_note) != '') { ?><b><span data-key="lg_note">Upload Note</span> : </b> <?php echo $upload_note; ?> <?php } ?>
<table class="table table-bordered char_font_size_12" >
	<thead>
		<td>
			<b><span data-key="lg_note">Note</span> : </b><span data-key="lg_note_three">  Files allowed are .doc, docx, xls, xlsx, jpg, png, txt, ppt, pptx, pdf, odt, rtf.</span><br>
 				     <?php echo str_repeat("&nbsp;", 10); ?> <span data-key="lg_note_four">Maximum file size allowed is 10MB. </span>	
		</td>
	</thead>
</table>

<!--localization-->