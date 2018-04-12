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
//var_dump($guideline[0]['entity_data']);exit;
$output = '<table class="table table-bordered char_font_size_12">
		<thead>	
			<b> <span data-key="lg_crclm">Guidelines for establishing</span>  ' . $guideline[0]['entity_data'] . '</b>
	   		<tr>
				<th class="span_textbox" style="width: 30px;"><span data-key="lg_slno"> Sl No. </span></th>
				<th style="width: 600px;"><span data-key="lg_file_name"> File Name </span></th>
				<th><span data-key="lg_delete"> Delete </span></th>
	   		</tr>
		</thead>
	  <tbody>';
$i = 0;
foreach ($result as $crclm) {  //var_dump($result);
    $i++;
    $date = $crclm['upload_date'];
    $changedate = date("d-m-Y", strtotime($date));
    $file_name = $crclm['file_path'];
    $new_name = substr($file_name, strpos($file_name, "dd_") + 3);
    $crclm_value = $crclm['help_entity_id'];
    $name = "guidelines";
    $folder_name = $crclm_value . '_' . $name;

    //if($crclm['af_actual_date'] != '0000-00-00') { $date = $crclm['af_actual_date'];} else {$date = '';}
    $output .= '<tr>
		    <td><input name="crclm_id" type="hidden" value="' . $crclm['help_entity_id'] . '">' . $i . '</td>
		    <td><a href="' . base_url() . 'uploads/' . $file_name . '" target="_blank" >' . $file_name . '</a></td>
		    <td><input name="save_form_data[]" class="save_form_data" type="hidden" value="' . $crclm['upload_id'] . '"><a class="help_entity" data-id="' . $crclm['upload_id'] . '"><i class="icon-remove icon-black"></i></a></td>
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

