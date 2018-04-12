<?php
/*
  ------------------------------------------------------------------------
 * Description		: To display the files uploaded in selected curriculum.
 * Date			: 1/07/2016
 * Author Name		: Bhagyalaxmi S Shivapuji
 * Modification History	:

 * Date			Modified By 		Description

  ------------------------------------------------------------------------ */
?>

<?php
$h = "";
$table = '<table class="table table-bordered char_font_size_12" id="res_guid">
		<thead>	
			<b> <span data-key="lg_crclm">' . $section_name . ' </span></b>  ' . $h . ' <span> <input type="hidden" name="fetch_approve" id="fetch_approve" value="" /></span>
	   		<tr>
				<th style="width:35px;"><span data-key="lg_slno"> Sl No.</span></th>
				<th><span data-key="lg_file_name"> File Name </span></th>
				<th><span data-key="lg_desc"> Description </span></th>
				<th><span data-key="lg_date"> Date </span></th>				
	   		</tr>
		</thead>
	  <tbody>';
$i = 0;

if (!empty($result)) {
    foreach ($result as $data) {

        $i++;
        $file_name = $data['file_name'];
        $new_name = substr($file_name, strpos($file_name, "dd_") + 3);
        $folder_name = $data['table_name'];
        $user = $data['user_id'];
        $my_id = $data['uload_id'];
        $date = date("d-m-Y", strtotime($data['actual_date']));
        if ($data['approved_file'] == '1') {
            $data_val = "checked";
        } else {
            $data_val = "";
        }

        $table .= "<tr id='test'>		   		
						<td><input name='my_id_data' id='my_id_data' type='hidden' value=" . $data['uload_id'] . ">" . $i . "</td>
						<td><a class='cursor_pointer' href=" . base_url() . "uploads/upload_faculty_contribution/" . $main_folder . "/" . $folder_name . "/" . $data['file_name'] . " target='_blank' >" . $new_name . "</a></td>
						<td> " . $data['description'] . "</td>
						<td> " . $date . " </td>					
					  </tr>";
    }
} else {
    $table .= "<tr><td></td><td> No Data To Display </td><td></td><td></td></tr>";
}
$table .= '</tbody></table>';
echo $table;
?>


<!--localization-->
<script src="<?php echo base_url('twitterbootstrap/js/custom/locale/locale_lang.js'); ?>" type="text/javascript"></script>

