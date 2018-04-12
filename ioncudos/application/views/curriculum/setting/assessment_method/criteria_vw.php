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
	$add='';$add1='';$add2='';$a='';$row_one_textarea='';$row_one='';
			$add.="<div class='' style='width:auto; overflow:auto;'>
				<form id='save_rubrics_data'><table border=0 cellpadding=0 id=generate_row>
				<!--<div class='navbar-inner-custom'>
							Criteria
				</div>-->
				
				<tr><td></td><td colspan=".$colspan_val."><center><b style=font-size:10pt>Scale of Assessment</b></center></td></tr>

				<div style='style='display: inline-block; white-space: nowrap; margin-left: auto; margin-right: auto;'>
				<tr><td class='span2' ><center><b style=font-size:10pt>Criteria : <font color='red'> * </font></b></center></td>";
				for($i=1;$i<=$range_count_val;$i++) {
				if(count($ao_range) != 0){
                                        if(@$range_array_val[$i-1]['criteria_range_name']){
                                            $add1 = "<td style='width:20%;'>"
                                                . "<center>Scale: "
                                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                                . "<input style='text-align:center;' type=text name='range[]' id=range_".$i." class='range_check input-medium range_box' value = '".$range_array_val[$i-1]['criteria_range_name']."'   disabled/></center>"
                                                . "<center>Range<font color='red'> * </font>: "
                                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                                . "<input style='text-align:right;' type=text name='range[]' id=range_".$i." class='range_check loginRegex rangeFormat required input-medium range_box' value = '".$range_array_val[$i-1]['criteria_range']."'   disabled/></center></td>";
                                            
                                        }else{
                                            $add1 = "<td style='width:20%;'>"
                                                //. "<center>Scale: "
                                               // . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                                //. "<input style='text-align:center;' type=text name='range[]' id=range_".$i." class='range_check input-medium range_box' value = '".$range_array_val[$i-1]['criteria_range_name']."'   disabled/></center>"
                                                . "<center>Range<font color='red'> * </font>: "
                                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                                . "<input style='text-align:right;' type=text name='range[]' id=range_".$i." class='range_check loginRegex rangeFormat required input-medium range_box' value = '".$range_array_val[$i-1]['criteria_range']."'   disabled/></center></td>";
                                            
                                        }
					
					$add .= $add1;
					$add1 = "";
					}else{
					$add1 = "<td style='width:20%;'>"
                                                . "<center>Scale: "
                                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                                . "<input style='text-align:center;' type=text name='range_name[]' id=range_name".$i." class='range_name input-medium range_name_box' value = '' /></center>"
                                                . "<center>Range<font color='red'> * </font>: "
                                                . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                                                . "<input style='text-align:right;' type=text name='range[]' id=range_".$i." class='range_check loginRegex rangeFormat required input-medium range_box' value = '".$range_array_val[$i-1]."' /></center></td>";
					$add .= $add1;
					$add1 = "";
					}
				}
					
		$add2.="</tr></div>
					<tr id='add_more_1'>					
					<td style='border-top: 1px solid #E6E6E6;'><textarea name=criteria_1 id=criteria_1 class=' input-medium' rows='3' cols='20'></textarea></td>";
					for($i=1;$i<=$range_count_val;$i++) {
						$row_one = "<td style='border-top: 1px solid #E6E6E6;'><center><textarea name='criteria_desc[]' id=c_stmt_1 class='criteria_check  input-medium' rows='3' cols='20' ></textarea></center></td>";
						$row_one_textarea .= $row_one;
						$row_one = "";
					}
		$b='';
		$b.="<td style='border-top: 1px solid #E6E6E6;'><center><a id=remove_criteria1 class='Delete' href=# tooltip=Delete rel=tooltip title=Delete ><i class=icon-remove></i></a></center>		
		</td></tr></table></form></div>";
		$a.="<input type='hidden' name='counter' id='counter' value='1' readonly>
					 <input type='hidden' name='ao_method_counter' id='ao_method_counter' value='1' readonly>
					<div id=insert_before></div> 
					 <br><div class='pull-right'>
						 <a id='add_more_criteria' class='btn btn-primary global' href='#'><i class='icon-plus-sign icon-white'></i> Add More Criteria </a>
					 </div>
					 <br>
					 <div id='duplicate_message' style='color:red;font-weight: bold;font-size: small;'></div>";
		$add.=$add2.$row_one_textarea;
		echo $add;
?>

