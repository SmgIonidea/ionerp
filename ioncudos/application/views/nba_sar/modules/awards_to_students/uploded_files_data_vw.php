<?php
/*
  ------------------------------------------------------------------------
*Description		: To display the files uploaded in selected curriculum.
*Date			: 3/30/2016
*Author Name		: Bhagyalaxmi S Shivapuji
*Modification History	:

*Date			Modified By 		Description
*
  ------------------------------------------------------------------------$specializatin[0]['specialization']*/
?>

<?php
$h="";
$table = '<table class="table table-bordered char_font_size_12" id="res_guid">
		<thead>	
			<b> <span data-key="lg_crclm">Specialization </span>:</b>  '.$h.'
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
	 
	 /* <div class='input-append'>	<input style='width:inherit;' type='text' class= 'actual_date_data_fg span12 datepicker' readonly name='actual_date[]' id='actual_date' value=".$data['actual_date']." />
					<span class='add-on actual_date_data' id='actual_btn' style='cursor:pointer;height:150px;'><i class='icon-calendar'></i></span></div>
	*/
		// var_dump($result);
	 foreach($result as $data) {

		   $i++;
		   $file_name = $data['file_name']; 
		   $new_name = substr($file_name, strpos($file_name, "dd_") + 3); 
		   $folder_name = $data['table_name'];
		   $date = date("d-m-Y",strtotime($data['actual_date']));	
		   $my_id = $data['uload_id'];
		   $table .= "<tr>
					<td><input name='my_id_data' id='my_id_data' type='hidden' value=".$data['uload_id'].">".$i."</td>
			       	<td><a class='cursor_pointer' href=".base_url()."uploads/".$folder_name."/".$data['file_name']." target='_blank' >".$new_name."</a></td>
					<td><textarea name='res_guid_description[]' id='res_description' class='res_description_data' row=2 col=5>".$data['description']."</textarea></td>
					<td>
						<div class='control-group'>
							<div class='controls'>
								<div class='input-append '>									
									<input type='text' class='span5 datepicker required actual_date_data_fg' name='actual_date[]' id='actual_date'  style='width:100px;' value=".$date." readonly>
									<span class='add-on actual_date_data_fg' id='actual_btn' style='cursor:pointer;'><i class='icon-calendar'></i></span>
								</div>
							</div>
						</div>
				</td>
					<td onclick = 'delete_file($my_id)' ><input name='save_form_data[]' class='save_form_data cursor_pointer' type='hidden' value=".$data['uload_id'].">
					
					<a role='button' class='cursor_pointer delete_file' data-id=".$data['uload_id']." ><i class='icon-remove icon-black'></i></a></td>
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

