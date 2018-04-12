<?php
/*
  ------------------------------------------------------------------------
 * Description		: 
 * Date			: 
 * Author Name		: 
 * Modification History	:
 * Date			Modified By 		Description
 *
  ------------------------------------------------------------------------ */
?>

<?php
$output = '<table class="table table-bordered char_font_size_12">
		<thead>	
			<b> Societies / Chapters Name :</b>  ' . $company[0]['prof_name'] . '
	   		<tr>
				<th class="span_textbox"> Sl No. </th>
				<th> File Name</th>
				<th> Description </th>
				<th class="span_textbox"> Date </th>
				<th> Delete</th>
	   		</tr>
		</thead>
	  <tbody>';
$i = 0;
foreach ($files as $file) {
    $i++;
    $date = $file['created_date'];
    $changedate = date("d-m-Y", strtotime($date));
    $file_name = $file['upload_file_name'];
    $new_name = substr($file_name, strpos($file_name, "dd_") + 3);
    $file_value = $file['prof_id'];
    $name = "professional_chapter";
    $folder_name = $file_value . '_' . $name;
    $date = explode("-", $file['upload_actual_date']);
    $date = $date[2] . '-' . $date[1] . '-' . $date[0];
    $output .= '<tr>
		    <td style="text-align:right;"><input name="company_id" type="hidden" value="' . $file['prof_id'] . '">' . $i . '</td>
		    <td><a href="' . base_url() . 'uploads/prof_societies_chapter_uploads/' . $folder_name . '/' . $file['upload_file_name'] . '" target="_blank" >' . $new_name . '</a></td>
		    <td><textarea name="description[]" row=2 col=5>' . $file['upload_desc'] . '</textarea></td>
		    <td>
		        <div class="input-append">
			    <input type="text" class="span5 datepicker required std_date" name="actual_date[]" id="actual_date"  style="width:100px;" value="' . $date . '" readonly>
			    <button type="button" class="add-on std_date_1" id="btn" style="cursor:pointer; height: 30px;"><i class="icon-calendar"></i></button>
			</div>	
		    </td>
		    <td><input name="save_form_data[]" class="save_form_data" type="hidden" value="' . $file['upload_id'] . '"><a class="delete_file" data-id="' . $file['upload_id'] . '"><center><i class="icon-remove icon-black"></i><center></a></td>
		</tr>';
}
$output .= '</tbody></table>';
echo $output;
?>

<table class="table table-bordered char_font_size_12" >
    <thead>
    <td>
        <b>Note : </b> Files allowed are .doc, docx, xls, xlsx, ods, jpg, png, txt, ppt, pptx, pdf, odt, rtf.<br>
        <?php echo str_repeat("&nbsp;", 10); ?> Maximum file size allowed is 10MB. 	
    </td>
</thead>
</table>
<!-- End of file companies_visited_upload_file_vw.php 
Location: .configuration/companies_visited/companies_visited_upload_file_vw.php  -->