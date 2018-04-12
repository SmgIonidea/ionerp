<?php
/**
 * Description          :	View for NBA SAR Report - Section 2.1.2 (TIER 1) -  Table of the Curriculum Structure.
 * Created              :	3-8-2015
 * Author               :       
 * Modification History :
 * Date	                        Modified by                      Description
 * 3-8-2015                     Jevi V. G.              Added file headers, public function headers, indentations & comments.
 * 13-8-2016                    Arihant Prasad		Rework, indentation and code cleanup 
  --------------------------------------------------------------------------------------------------------------- */
?>
<?php
$term_name = $header = $crs = $final_string = $table_close = '';

$header = '<table class="table table-bordered table-nba" aria-describedby="example_info">		
			<tr>
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class">Course Code </h4>
				</th> 
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class">Course Title  </h4>
				</th>
				<th class="orange background-nba" colspan="4" gridSpan=5 align="center"><h4 class="h4_margin font_h ul_class"><center>Total Number of contact hours  </center></h4>
				</th> 
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class">Credits </h4>
				</th>			
			<tr>
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class"> </h4>
				</th> 	
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class"> </h4>
				</th> 
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class"><center>Lecture ( L )</center></h4>
				</th>
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class"><center>Tutorial ( T )</center></h4>
				</th>
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class"><center>Practical# ( P )</center></h4>
				</th>
				<th class="orange background-nba"><h4 class="font_h ul_class"><center>Total Hours </center></h4>
				</th>
				<th class="orange background-nba"><h4 class="h4_margin font_h ul_class"> </h4>
				</th> 
			</tr>
			</tr>
		';
$final_string = $final_string . $header;

foreach ($curriculum_structure_detail as $structure) {

        if ($structure['term_name'] != $term_name) {
                //display term name
                $final_string.= '<tr>
                                        <td colspan=7 gridSpan=8>
                                                <h4 class="h4_margin font_h ul_class" colspan="7">' . $structure['term_name'] . '</h4>
                                        </td>
                                </tr>';
        }

        //display course details
        $final_string.= '<tr>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class">' . $structure['Course Code'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" >' . $structure['Course Title'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $structure['Lecture Credits (L)'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $structure['Tutorial (T)'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $structure['Practical# (P)'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $structure['Total Hours'] . '</h4>
                                </td>
                                <td>
                                        <h4 class="h4_weight h_class font_h ul_class" align="right">' . $structure['Credits'] . '</h4>
                                </td>
                        </tr>';

        $term_name = $structure['term_name'];
}

$table_close = '</table># Seminars, project works may be considered as practical.';
echo $final_string . $table_close;
?>
<!-- End of file t1ug_c2_1_2_curriculum_structure_contents_vw.php 
        Location: .nba_sar/ug/tier1/criterion_2/t1ug_c2_1_2_curriculum_structure_contents_vw.php -->