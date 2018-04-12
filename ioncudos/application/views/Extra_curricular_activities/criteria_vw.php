<?php
$code="<table border=0 cellpadding=0 id=generate_row>
        	<tr>
                    <td></td>
                    <td colspan=" . $colspan_val . "><center><b style=font-size:10pt>Scale of Assessment</b>
                        <input type='hidden' name='activity_id' id='activity_id' value='$activity_id'>
                        <input type='hidden' name='rubrics_range_flag' id='rubrics_range_flag' value='$rubrics_range_flag'></center>
                     </td>
                </tr>
                <tr>
                    <td class='span1' ><center><b style=font-size:10pt>Criteria : <font color='red'> * </font></b></center></td>
                    <td class='span2' ><center><b style=font-size:10pt>".$this->lang->line('so')." : <font color='red'> * </font></b></center></td>";

                for ($i = 1; $i <= $range_count_val; $i++) {
                    if ($rubrics_range_flag){
                        if(@$range_array_val[$i - 1]['criteria_range_name']){
                            $code.= "<td style='width:20%;'>"
                                . "<center>Scale: "
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                . "<input style='text-align:center;' type=text name='range_name[]' id='range_name[]' class='range_name loginRegex rangeFormat input-medium range_name_box' value = '" . $range_array_val[$i - 1]['criteria_range_name'] . "'   disabled/><br>"                        
                                . "<center>Range<font color='red'> * </font>: "
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                . "<input style='text-align:right;' type=text name='range[]' id='range[]' class='range_check loginRegex rangeFormat input-medium range_box' value = '" . $range_array_val[$i - 1]['criteria_range'] . "'   disabled/></center></center>"
                                . "</td>"; 
                        }else{
                            $code.= "<td style='width:20%;'>"
                                //. "<center>Scale: "
                               // . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                               // . "<input style='text-align:center;' type=text name='range_name[]' id='range_name[]' class='range_name loginRegex rangeFormat input-medium range_name_box' value = '" . $range_array_val[$i - 1]['criteria_range_name'] . "'   disabled/><br>"                        
                                . "<center>Range<font color='red'> * </font>: "
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                . "<input style='text-align:right;' type=text name='range[]' id='range[]' class='range_check loginRegex rangeFormat input-medium range_box' value = '" . $range_array_val[$i - 1]['criteria_range'] . "'   disabled/></center></center>"
                                . "</td>"; 
                        }
                                               
                    } else {
                        $code.= "<td style='width:20%;'>"
                                . "<center>Scale: "
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                . "<input style='text-align:center;' type=text name='range_name[]' id='range_name[]' class='range_name input-medium range_name_box' placeholder='Ex:Good' /><br>"                        
                                . "<center>Range<font color='red'> * </font>: "
                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                . "<input style='text-align:right;' type=text name='range[]' id='range[]' class='range_check loginRegex rangeFormat input-medium range_box' value = '" . $range_array_val[$i - 1] . "' required /></center></center>"
                                . "</td>";                        
                    }
                }

        $code.="</tr>";      
            $code.="<tr id='add_more_1'>";
           if(isset($rubrics_criteria)){
              
                $code.="<td style='border-top: 1px solid #E6E6E6;'>";
                    $code.="<center><textarea name='criteria_1' id='criteria_1' class='input-medium' rows='3' cols='20' required='required'>".$rubrics_criteria[0]['criteria']."</textarea></center>";
                    $code.="<input type='hidden' name='criteria_id' value='".$rubrics_criteria[0]['rubrics_criteria_id']."'>";
                $code.="</td>";
               
                 $code.="<td style='border-top: 1px solid #E6E6E6; vertical-align:top;'><center>";
                         $code.="<select multiple name='outcome[]' id='outcome' class='outcome_list form-control' >";                                
                             foreach ($outcome as $key => $rec) {
                                 $selected='';
                                 if(in_array($key, $criteria_pos)){
                                     $selected="selected='selected'";
                                 }
                             $code.="<option value='$key' $selected>".$rec['ref']."</option>";
                             }                    
                         $code.="</select>";                    
                 $code.="</td>";
                foreach($rubrics_criteria as $indx=> $criteria_data){
                    $code.= "<td style='border-top: 1px solid #E6E6E6;'><center>";
                    $code.="<input type='hidden' name='criteria_desc_id[".($indx)."]' value='".$criteria_data['criteria_description_id']."'>";
                    $code.="<textarea name='criteria_desc[".($indx)."]' id='criteria_desc[".($indx)."]' required class='criteria_check  input-medium' rows='3' cols='20'>".$criteria_data['criteria_description']."</textarea>";
                    $code.="</center></td>";
                }
           }else{
               
                $code.="<td style='border-top: 1px solid #E6E6E6;'>"
                        . "<center><textarea name='criteria_1' id='criteria_1' class='input-medium' rows='3' cols='20' required='required'></textarea></center>"
                        . "</td>";
                $code.="<td style='border-top: 1px solid #E6E6E6; vertical-align:top;'><center>";
                        $code.="<select multiple name='outcome[]' id='outcome' class='outcome_list form-control' >";                                
                            foreach ($outcome as $key => $rec) {
                            $code.="<option value='$key' title='".$rec['ref'].' - '.$rec['title']."'>".$rec['ref']."</option>";
                            }                    
                        $code.="</select>";                    
                $code.="</td>";
                for ($i = 1; $i <= $range_count_val; $i++) {
                    $code.= "<td style='border-top: 1px solid #E6E6E6;'><center>";                        
                        $code.="<textarea name='criteria_desc[".($i-1)."]' id='criteria_desc[".($i-1)."]' required class='criteria_check  input-medium' rows='3' cols='20'></textarea>";
                    $code.="</center></td>";                        
                }                 
           }
           $code.="</tr>";
        $code.="</table>";
echo $code;
?>

