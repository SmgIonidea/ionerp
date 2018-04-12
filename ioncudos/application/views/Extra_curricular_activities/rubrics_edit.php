<?php
echo '<pre>';print_r($data);echo '</pre>';

$code.="<div class='' style='width:auto; overflow:auto;'>
        
            <table border=0 cellpadding=0 id=generate_row>
        	<tr>
                    <td></td>
                    <td colspan=" . $colspan_val . "><center><b style=font-size:10pt>Scale of Assessment</b>
                        <input type='hidden' name='activity_id' id='activity_id' value='$activity_id'>
                        <input type='hidden' name='rubrics_range_flag' id='rubrics_range_flag' value='$rubrics_range_flag'></center>
                     </td>
                </tr>
		<div style='style='display: inline-block; white-space: nowrap; margin-left: auto; margin-right: auto;'>
                <tr>
                    <td class='span2' ><center><b style=font-size:10pt>Criteria : <font color='red'> * </font></b></center></td>
                    <td class='span2' ><center><b style=font-size:10pt>PO : <font color='red'> * </font></b></center></td>";

                for ($i = 1; $i <= $range_count_val; $i++) {
                    if ($rubrics_range_flag){
                        $add1 = "<td style='width:20%;'><center><input type=text name='range[]' id=range_" . $i . " class='range_check loginRegex rangeFormat input-mini range_box' value = " . $range_array_val[$i - 1]['criteria_range'] . "   disabled/><font color='red'> * </font></center></td>";
                        $add .= $add1;
                        $add1 = "";
                    } else {
                        $add1 = "<td style='width:20%;'><center><input type=text name='range[]' id=range_" . $i . " class='range_check loginRegex rangeFormat input-mini range_box' value = " . $range_array_val[$i - 1] . " required /><font color='red'> * </font></center></td>";
                        $add .= $add1;
                        $add1 = "";
                    }
                }

        $add2.="</tr>
            </div>
                <tr id='add_more_1'>					
                    <td style='border-top: 1px solid #E6E6E6;'><textarea name='criteria_1' id='criteria_1' class='input-medium' rows='3' cols='20' required='required'></textarea></td>";
                    $add2.="<td style='border-top: 1px solid #E6E6E6; vertical-align:top;'><center>";
                            $add2.="<select name='outcome' id='outcome' class='outcome_list form-control' multiple='multiple'>";                                
                                foreach ($outcome as $key => $rec) {
                                $add2.="<option value='$key'>".$rec['ref']."</option>";
                                }                    
                            $add2.="</select>";
                    $add2.="</center><input type='hidden' name='po_lists' id='po_lists' value='' required></td>";
                    
                    for ($i = 1; $i <= $range_count_val; $i++) {
                        $row_one = "<td style='border-top: 1px solid #E6E6E6;'><center><textarea name='criteria_desc[]' id='criteria_desc[]' required class='criteria_check  input-medium' rows='3' cols='20'></textarea></center></td>";
                        $row_one_textarea .= $row_one;
                        $row_one = "";
                    }
                    $b = '';
                    $b.="<td style='border-top: 1px solid #E6E6E6;'><center><a id=remove_criteria1 class='Delete' href=# tooltip=Delete rel=tooltip title=Delete ><i class=icon-remove></i></a></center></td>";
           $b.="</tr>"
        . "</table></div>";   
$add.=$add2 . $row_one_textarea;
echo $add;
?>

