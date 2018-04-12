<?php
/*
  ------------------------------------------------------------------------
*Description		: To display Criteria Range.
*Date			: 20/10/2016
*Author Name		: Mritunjay B S
*Modification History	:

*Date			Modified By 		Description
*
  ------------------------------------------------------------------------*/
?>

<?php

//$table = '<form id="save_rubrics_data">';
$table = '<table id="generate_row" border="0" cellpadding="0" class="table qp_table table-bordered dataTable">';
$table .= '<thead>';
$table .= '<tr>';
$table .= '<th></th>';
$table .= '<th></th>';
$table .= '<th colspan ='.$colspan_val.'><center>Scale of Assessment</center></th>';
$table .= '</tr>';
$table .= '<tr>';
$table .= '<th ><center style="margin-bottom:55px;">Criteria <font color="red"> * </font></center></th>';
$table .= '<th ><center style="margin-bottom:55px;">CO <font color="red"> * </font></center></th>';
if($ao_range_count != 0){
for($a=0;$a<$ao_range_count;$a++){
   $table .= "<td style='width:20%;'><center><input style='text-align: right' type=text name='range_".$a."[]' id=range_".$a." class='range_check onlyDigit loginRegex rangeFormat required input-mini range_box' value = ".$range_array_val[$a]['criteria_range']."   disabled/><font color='red'> * </font></center></td>";
}
}else{
    for($i=1;$i<=$range_count_val;$i++) {
    $table .= "<td style='width:20%;'>"
            . "<center>Scale: <input style='text-align: center' type=text name='range_name_".$i."[]' id=range_name_".$i." class='range_name input-medium range_name_box' placeholder='Ex:Good' /><br>"
            . "<center>Range<font color='red'> * </font>: <input style='text-align: right' type=text name='range_".$i."[]' id=range_".$i." class='range_check onlyDigit loginRegex rangeFormat required input-medium range_box' value = ".$range_array_val[$i-1]." /></center></center></td>";
    }
}
$table .= '</tr>';
$table .= '</thead>';
$table .= '<tbody>';
$table .= '<tr id="add_more_1">';
$table .= '<td id="criteria_id" style="border-top:1px solid #E6E6E6;"><center>';
if($rubrics_data == 'custom'){
$table .= '<textarea name="criteria_1" id="criteria_1" class="input-medium required" rows="3" cols="20"></textarea></td>'; 
}else{

$table .= '<select name="rubrics_criteria" id="rubrics_criteria" class="input-medium required" style="margin:0px;">';
$table .= '<option>Select '.$rubrics_data['type'].'</option>';
foreach($rubrics_data['data_list'] as $rubrics){
$table .= '<option id="'.$rubrics['id'].'">'.$rubrics['statement'].'</option>';   
}
 $table .= '</select>';

}
$table .= '</center></td>';
$table .= '<td style="border-top: 1px solid #E6E6E6;"><center>';
$table .= '<select name="co_id_val[]" id="co_id_val" class="input-small co_id_rubrics required" multiple="multiple">';
  foreach($co_list as $co_data){
$table .= '<option value="'.$co_data['clo_id'].'" title="'.$co_data['clo_statement'].'">'.$co_data['clo_code'].'</option>';  
   }
$table .= '</select> </center>';                       
$table .= '</td>';
if($ao_range_count != 0){
for($a=0;$a<$ao_range_count;$a++){
$table .= '<td style="border-top: 1px solid #E6E6E6;">';
$table .= '<center>';
$table .= '<textarea name="criteria_desc['.$a.']" id="c_stmt_'.$a.'" class="criteria_check input-medium required" rows="3" cols="20" ></textarea>';
$table .= '</center>';
$table .= '</td>';
}
}else{
for($i=1;$i<=$range_count_val;$i++) {
$table .= '<td style="border-top: 1px solid #E6E6E6;">';
$table .= '<center>';
$table .= '<textarea name="criteria_desc['.$i.']" id="c_stmt_'.$i.'" class="criteria_check  input-medium required" rows="3" cols="20" ></textarea>';
$table .= '</center>';
$table .= '</td>';
 }
}
$table .= '</tr>';
$table .= '</tbody>';
$table .= '</table> ';
//$table .= '</form>';

echo $table;	
?>

