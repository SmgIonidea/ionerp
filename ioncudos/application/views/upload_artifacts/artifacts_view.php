<?php
/*
  ------------------------------------------------------------------------
 * Description		: To display the files uploaded in selected curriculum.
 * Date			: 05-10-2015
 * Author Name		: Neha Kulkarni
 * Modification History	:

 * Date			Modified By 		Description
 *
  ------------------------------------------------------------------------ */
?>

<?php
$output = '<table class="table table-bordered char_font_size_12">
		<thead>	
			<b> <span data-key="lg_crclm">Curriculum </span>:</b>  ' . $curriculum[0]['crclm_name'] . '
	   		<tr>
				<th class="span_textbox"><span data-key="lg_slno"> Sl.No </span></th>
				<th><span data-key="lg_file_name"> File Name </span></th>
				<th><span data-key="lg_desc"> Description </span></th>
				<th class="span_textbox"><span data-key="lg_date"> Date </span></th>
				<th><span data-key="lg_delete"> Delete </span></th>
	   		</tr>
		</thead>
	  <tbody>';
$i = 0;
foreach ($result as $crclm) {
    $i++;
    $date = $crclm['create_date'];
    $changedate = date("d-m-Y", strtotime($date));
    $file_name = $crclm['af_file_name'];
    $new_name = substr($file_name, strpos($file_name, "dd_") + 3);
    $crclm_value = $crclm['crclm_id'];
    $name = "curriculum";
    $folder_name = $crclm_value . '_' . $name;
	
	if($crclm['af_actual_date'] != '0000-00-00') { $date = $crclm['af_actual_date'];} else {$date = '';}
    $output .= '<tr>
		    <td><input name="crclm_id" type="hidden" value="' . $crclm['crclm_id'] . '">' . $i . '</td>
		    <td><a href="' . base_url() . 'uploads/upload_artifacts_file/' . $folder_name . '/' . $crclm['af_file_name'] . '" target="_blank" >' . $new_name . '</a></td>
		    <td><textarea name="af_description[]" row=2 col=5>' . $crclm['af_description'] . '</textarea></td>
		    <td>
		        <div class="input-append">
			    <input type="text" class="span5 datepicker required std_date" name="af_actual_date[]" id="af_actual_date"  style="width:100px;" value="' . $date   . '" readonly>
			    <button type="button" class="add-on std_date_1" id="btn" style="cursor:pointer; height: 30px;"><i class="icon-calendar"></i></button>
			</div>	
		    </td>
		    <td><input name="save_form_data[]" class="save_form_data" type="hidden" value="' . $crclm['af_id'] . '"><a class="artifact_entity" data-id="' . $crclm['af_id'] . '"><i class="icon-remove icon-black"></i></a></td>
		</tr>';
}
$output .= '</tbody></table>';
echo $output;
?>

<table class="table table-bordered char_font_size_12" >
    <thead>
    <td>
	<b><span data-key="lg_note">Note</span> : </b><span data-key="lg_note_three">  Files allowed are .doc, docx, xls, xlsx, jpg, png, txt, ppt, pptx, pdf, odt, rtf.</span><br>
	<?php echo str_repeat("&nbsp;", 10); ?> <span data-key="lg_note_four">Maximum file size allowed is 10MB. </span>	
    </td>
    </thead>
</table>

