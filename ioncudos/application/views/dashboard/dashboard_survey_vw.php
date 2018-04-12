
<style>
<!-- .test {	padding : 0px;}
.navbar_custom { background-color: #1a1a1a;} 
.right {
     background-color: #fff;
    margin-left: 30px;
    overflow: hidden;
}-->
</style>
   
<?php

//style='border: gray 1px dashed; border-bottom: black 1px solid; border-top-style: ridge;'

	$red_imag=$orange_imag=$green_imag='';$topics_title='';$tlo_data_list='';$list= $topic_count=$topics_title =$tlo_data_list = '';$j=0;$topic_count_list=$ls_list=0;$ctlo=0;
	$red_imag="<img src='". base_url('twitterbootstrap/img/red_dot.png')."' width='15' height='15' alt=''/>" ;
	$orange_imag="<img src='". base_url('twitterbootstrap/img/orange_dot.png')."' width='20' height='20' alt=''/>" ;
	$green_imag="<img src='". base_url('twitterbootstrap/img/green_dot.png')."' width='13' height='13' alt=''/>" ;
	$count = (count($terms_data));
	$table_creation 	= 	"";	
	$table_creation		.= "<div  id='export_pdf_data' name='export_pdf_data'>";
	$table_creation		.= "<div class='navbar-inner-custom  slide1 ' style='100%;'>&nbsp;Curriculum - Term wise Survey Status </div>";
	for($i=0; $i<$count; $i++)
	{		
			$table_creation		.= "<div id='Survey' style='page-break-after:always;'>";
			$table_creation		.= '<b>'.($terms_data[$i]['term_name']).'</b>';			
			$table_creation		.= "<table style='border:1x;' class='table table-bordered table-hover' id='example' name='example' aria-describedby='example_info'>";
			$table_creation		.= "<thead><tr role='row'>
							<th  style='color: #8E2727;width:40px;' class=' sl_no header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Sl.No</th>
							<th  style='color: #8E2727; width:400px;' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Curriculum Name</th>
							<th  style='color: #8E2727;' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Curriculum  Owner</th>
							<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Survey Name</th>
							<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Survey Definition </th>
							<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Survey Completion</th>
						</tr></thead>";
			$table_creation		.= "<tbody id ='' role='alert' aria-live='polite' aria-relevant='all'>";
			if(!empty($survey_data[$i])){
				foreach($survey_data[$i]['co'] as $survey){
					$table_creation		.= "<tr><td style='text-align: '>".$survey['Sl_no']."</td><td>".$survey['crs_name']."</td><td>".$survey['crs_own_name']."</td><td>".$survey['Survey_name']."</td><td>".$survey['not_created']."</td><td>".$survey['created']."</td></tr>";
				
				}
			}else{$table_creation		.= "<tr><td style='text-align: '></td><td>No data to display</td><td></td><td></td><td></td><td></td></tr>";}
			$table_creation		.= "</tbody></table></div>";	 
	}
	$table_creation .= "</div>";
		
			$table_creation		.= "<div id='Survey_PEO'  style='page-break-after:always;'>";
			$table_creation		.= "<div class='navbar-inner-custom  slide1 ' style='100%;'>&nbsp;Curriculum PEO & PO Survey Status</div>";
			$table_creation		.= "<div id='colapse_da' class=''>";
			$table_creation		.= "<table  style='border:1x;' class='table table-bordered table-hover' aria-describedby='example_info' id='example_peo_po' name='example'>";
			$table_creation		.= "<thead><tr role='row'>";
			$table_creation		.= "<th style='color: #8E2727;width:40px;' class=' sl_no ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Sl.No</th>";
			$table_creation		.= "<th  style='color: #8E2727; width:400px;' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Curriculum Name</th>";
			$table_creation		.= "<th  style='color: #8E2727;' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Curriculum Owner</th>";
			$table_creation		.= "<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Survey Owner</th>";
			$table_creation		.= "<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'> Survey Defined</th>";
			$table_creation		.= "<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Survey Completion</th>";	
			$table_creation		.= "<th  style='color: #8E2727' class='header ' role='columnheader' tabindex='0' aria-controls='example' aria-sort='ascending'>Survey Hosted</th>";			
			$table_creation		.= "</tr></thead>";
			$table_creation		.= "<tbody id ='' role='alert' aria-live='polite' aria-relevant='all'>";
			if(!empty($peo_po)){
				foreach($peo_po['peo'] as $peo){
				$table_creation		.= "<tr><td style='text-align: right'>".$peo['Sl_no']."</td><td>".$peo['crclm_name']."</td><td>".$peo['crclm_owner_name']."</td><td>".$peo['Entity']."</td><td>".$peo['survey_name']."</td><td>".$peo['survey_created']."</td><td>".$peo['survey_hosted']."</td></tr>";

				}
			}else{
				$table_creation		.= "<tr><td>1</td><td>".$peo['crclm_name']."</td><td>".$peo['crclm_owner_name']."</td><td>".$peo['Entity']."</td><td>".$peo['survey_name']."</td><td>".$peo['survey_created']."</td><td>".$peo['survey_hosted']."</td></tr>";
			}
			$table_creation		.= "</tbody>";
			$table_creation		.= "</table></div></div>";
	echo $table_creation;			
	echo "\r\n\n\n\n";

?>


		
		
		