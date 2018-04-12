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
	$table = "";$cn="";
	if($range_count){	$table .= "<b>Rubrics Definition :</b>";			
						$table .=  "<table id='example_rubrics' class='table table-bordered' border='1'><tr class=active><td><center><b>Criteria :</b></td>";
						
						for($k = 1;$k <= count($range_data1); $k++) {
                                                    if(@$range_data1[$k-1]['criteria_range_name']){
                                                        $table .=  "<th><center>".$range_data1[$k-1]['criteria_range_name'].' :- '.$range_data1[$k-1]['criteria_range']."</center></th>";
                                                        
                                                    }else{
                                                        $table .=  "<th><center>".$range_data1[$k-1]['criteria_range']."</center></th>";
                                                    }
							
							
						}
						$table .=  "<th >Edit</th>";
						$table .=  "<th >Delete</th>";
						$table .=  "</tr>";						
						
						for($i = 0;$i < $criteria_count; $i++) {
							$j=$i+1;		
							$table .=   "<tr onblur='te()' class='te'><td><center><b>".$criteria_data[$i]['criteria']."</b></center></td>";
							$c_id = $criteria_data[$i]['rubrics_criteria_id'];
						for($k = 1;$k <= $range_count; $k++) {
								$r_id = $range_data[$k-1]['rubrics_range_id'];
								for($l = 0; $l < count($criteria_description_data); $l++ ) {
									if($criteria_description_data[$l]['rubrics_range_id'] == $r_id &&
										$criteria_description_data[$l]['rubrics_criteria_id'] == $c_id ) {	$cn++;						$exist=1;
										$table .=  "<td><center>".$criteria_description_data[$l]['criteria_description']."</center></td>";											
											$criteria_description_id = $criteria_description_data[$l]['criteria_description_id'];
									}
									
								}	if($exist == 0){$table .=  "<td><center></center></td>";		 }									
							} 					
							$rubrics_criteria_id = $criteria_data[$i]['rubrics_criteria_id'];
							$table .=  "<td onclick='edit_assessment_data($rubrics_criteria_id,$ao_id,$criteria_description_id)' ><center><i class='icon-pencil cursor_pointer' ></i></center></td>";
							$table .=  "<td><a href='#' onclick='return code_to_run($rubrics_criteria_id,$ao_id)' role='button' class='delete_criteria'><center><i class='icon-remove cursor_pointer ' ></i></center></a></td>";								
							$table .=  "</tr>";				
						}																									
						$table .= "</table>";
						echo $table;
						
					} else {
						echo "<b style='color:red;'>Rubrics not defined.</b>";
					}
?>