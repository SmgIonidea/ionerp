<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if($view_data['tab'] === 'tab_1') {

    extract($view_data['selected_param']);
    
    $params_details = '<table class="table table-bordered" style="width:100%;">';
    $params_details.= '<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">' . $crclm_name . '</b></td>';
    $params_details.= '<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term :</b><b needAlt=1 class="font_h ul_class">' . $term_name. '</b></td></tr>';
    $params_details.='<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">' . $crs_title . '(' . $crs_code . ')</b></td>';
    $params_details.= '</table>';

    echo $params_details.'<br>';
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">Course - '.$this->lang->line('entity_cie').' Attainment List (Section wise)</h4><br> '.$view_data['section_table'].'<br>'.$view_data['section_table_note'];

} 
else if ($view_data['tab'] === 'tab_2') {
    extract($view_data['selected_param']);
    
    $params_details = '<table class="table table-bordered" style="width:100%;">';
    $params_details.= '<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Curriculum : </b><b needAlt=1 class="font_h ul_class">' . $crclm_name . '</b></td>';
    $params_details.= '<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Term :</b><b needAlt=1 class="font_h ul_class">' . $term_name. '</b></td></tr>';
    $params_details.= '<tr><td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Course : </b><b needAlt=1 class="font_h ul_class">' . $crs_title . '(' . $crs_code. ')</b></td>';
    $params_details.= '<td width=350><b needAlt=1 class="h_class font_h ul_class" style="font-weight:normal;">Type :</b><b needAlt=1 class="font_h ul_class">'.$type.'</b> </td></tr>';
    $params_details.= '</table>';

    echo $params_details.'<br>';
    
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">Course Outcome(COs) Attainment</h4><br>';
    
    echo "<img src='".$view_data['image_location']."' width='680' height='330' /><br>";
    
    echo $view_data['all_section_table'].'<br>'.$view_data['all_section_table_note'].'<br><br>';
    
    if($view_data['finalized_data_tbl'] != '') {
        echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">Overall Course Outcomes ('.$this->lang->line('entity_clo').' Attainments are Finalized </h4><br>'.$view_data['finalized_data_tbl'].'<br>';
    }
    
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">Course - '.$this->lang->line('entity_clo_full').' '.$this->lang->line('entity_clo') .' to '.$this->lang->line('student_outcomes_full').' '.$this->lang->line('sos').' Attainment Matrix</h4><br>';
    
    echo $view_data['po_attainment_mapping_tbl'].'<br>';
    
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">Map Level Weightage</h4><br>'.$view_data['display_map_level_weightage'].'<br>';
    
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">'.$this->lang->line('student_outcomes_full'). $this->lang->line('sos').'Attainment by the Course</h4><br>';
    
    echo $view_data['po_attainment_tbl'].$view_data['po_attainment_tbl_note'];
    
    //echo $view_data['display_map_level_weightage'];
    
} else {
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;>No data for display</h4>';
}

