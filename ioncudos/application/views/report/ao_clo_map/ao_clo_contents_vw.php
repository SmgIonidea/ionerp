<?php
/**
* Description	:	View for Assessment Occasions to CO Mapping Report.
* Created		:	26-10-2015
* Date				Author				Description
* 26-10-2015		Abhinay B.Angadi    Added file headers, public function headers, indentations & comments.

-------------------------------------------------------------------------------------------------
*/
?>
<?php 
	$crs_code = $heading = $table_close = $ao_clo = $ao_clo_level = $prev_crs_code = '';
	$flag = 0;
	$ao_clo_level_array = array();
	$count = 0;
	if(empty($row_list_ao_clo_level)){
		echo '';
	}else{
		$columns_list_level_diff  = array_diff($columns_list_ao_clo_level,array('ao_id','crs_code'));
		foreach($columns_list_level_diff as $columns_list_data){
			$count++;
			$heading .= '<td class="orange">
							<h4 style="text-align: center" class="h4_margin font_h ul_class">'.$columns_list_data.'</h4>
						</td>';
		}
		$count++;
		$heading = '<tr>'.$heading.'</tr>';
		
		foreach($row_list_ao_clo_level as $row_list_args){
			foreach($row_list_args as $row_list_key => $row_list_value){
				if($row_list_key == 'ao_id'){
					continue;
				}else{
					if($row_list_key == 'crs_code'){
						if($crs_code != $row_list_value){
							$crs_code = $row_list_value;
							$ao_clo_level = $table_close.'<table class="table table-bordered table-nba">
														<tr><td gridSpan='.$count.'><h4 class="h4_margin font_h ul_class">Course Code: '.$crs_code.'</h4></td></tr>'.$heading.'<tr>';
						}else{
							$ao_clo_level .= '<tr>';
						}
						continue;
					}
					if($row_list_key == 'AO'){
						$ao_clo_level .= '<td width=300><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">'.$row_list_value.'</h4></td>';
					}else{
						$value = (round($row_list_value,2)) == 0?'':round($row_list_value,2).'%';
						$ao_clo_level .= '<td width=300><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">'.$value.'</h4></td>';
					}
				}
			}
			$ao_clo_level .= '</tr>';
			$table_close = '</table>';
			$ao_clo_level_array[$crs_code] = $ao_clo_level.$table_close;			
		}
	}
	$row_list_args = $crs_code = $row_list_key = $row_list_value = $table_close = $heading = '';
	$count = 0;
	if(empty($row_list_ao_clo)){
		echo '';
	}else{
		$columns_list_diff  = array_diff($columns_list_ao_clo,array('ao_id','crs_code'));
		foreach($columns_list_diff as $columns_list_data){
			$count++;
			$heading .= '<td class="orange">
							<h4 style="text-align: center" class="h4_margin font_h ul_class">'.$columns_list_data.'</h4>
						</td>';
		}
		$count++;
		$heading = '<tr>'.$heading.'</tr>';
		
		foreach($row_list_ao_clo as $row_list_args){
			foreach($row_list_args as $row_list_key => $row_list_value){
				if($row_list_key == 'ao_id'){
					continue;
				}else{
					if($row_list_key == 'crs_code'){
						if($crs_code != $row_list_value){
							$crs_code = $row_list_value;
							if($table_close != ''){
								$level = empty($ao_clo_level_array[$prev_crs_code])?'':$ao_clo_level_array[$prev_crs_code];
								$prev_crs_code = $crs_code;
								$ao_clo .= $table_close.$level.'<table class="table table-bordered table-nba">
														<tr><td gridSpan='.$count.'><h4 class="h4_margin font_h ul_class">Course : '.$crs_code.'</h4></td></tr>'.$heading.'<tr>';
							}else{
								$prev_crs_code = $crs_code;
								$ao_clo .= '<table class="table table-bordered table-nba">
														<tr><td gridSpan='.$count.'><h4 class="h4_margin font_h ul_class">Course : '.$crs_code.'</h4></td></tr>'.$heading.'<tr>';
							}
						}else{
							$ao_clo .= '<tr>';
						}
						continue;
					}
					if($row_list_key == 'AO'){
						if($row_list_value == 'TEE' && $flag != 1){
							$flag = 0;
						}else{
							$flag = 1;
						}
						$ao_clo .= '<td width=300><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">'.$row_list_value.'</h4></td>';
					}else{
						if(is_null($row_list_value)){
							$ao_clo .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class">&nbsp;</h4></td>';
						}else{
							$ao_clo .= '<td><h4 style="text-align: center" class="h4_weight h_class font_h ul_class"><img src="'.base_url('twitterbootstrap/img/checkmark.png').'"></h4></td>';
						}
					}
				}
			}
			$ao_clo .= '</tr>';
			$table_close = '</table>';
		}
		if($flag == 0){
			echo '';
		}else{
			$level = empty($ao_clo_level_array[$prev_crs_code])?'':$ao_clo_level_array[$prev_crs_code];
			echo $ao_clo.$table_close.$level;
		}
	}
?>