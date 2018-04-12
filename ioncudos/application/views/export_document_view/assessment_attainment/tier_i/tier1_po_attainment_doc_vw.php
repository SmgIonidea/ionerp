<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if($view_data['tab'] === 'tab_1') {
echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">'.$this->lang->line('student_outcome_full').'  '.$this->lang->line('student_outcome').'  Attainment '. $this->lang->line('entity_cie').' & '. $this->lang->line('entity_see').'</h4><br>';    
    
echo '<table class="table table-bordered" style="width:100%;"><tr><td width=350>Curriculum : <b needAlt=1 class="font_h ul_class">'. $view_data['crclm_name'] . '</b></td><td width=350>';
    
echo 'Term : <b needAlt=1 class="font_h ul_class">'. $view_data['terms'] . '</b></tr></table><br><br>';
    
echo "<img src='".$view_data['image_location']."' width='680' height='330' /><br>";

echo $view_data['direct_attainment_table'].'<br>';

echo $view_data['direct_attainment_table_note'];

} else if($view_data['tab'] === 'tab_2') {
    echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">'.$this->lang->line('student_outcome_full').'  '.$this->lang->line('student_outcome').'  Attainment '. $this->lang->line('entity_cie').' & '. $this->lang->line('entity_see').'</h4><br>';    
    
    echo '<table class="table table-bordered" style="width:100%;"><tr><td width=350>Curriculum : <b needAlt=1 class="font_h ul_class">'. $view_data['crclm_name'] . '</b></td><td width=350>';
    
    echo 'Term : <b needAlt=1 class="font_h ul_class">'. $view_data['terms'] . '</b></tr>';
    
    echo '<tr><td width=350> Extracurricular / Co-curricular Activity  : <b needAlt=1 class="font_h ul_class">'. $view_data['activity_name'] . '</b></td></tr></table><br><br>';
    
    echo "<img src='".$view_data['image_location']."' width='680' height='330' /><br>";
    
    echo $view_data['extra_curricular_attainment_table'];
    
    
}else if($view_data['tab'] === 'tab_3') {
echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">'.$this->lang->line('student_outcome_full').'  '.$this->lang->line('student_outcome').'  Attainment '. $this->lang->line('entity_cie').' & '. $this->lang->line('entity_see').'</h4><br>';    
    
echo '<table class="table table-bordered" style="width:100%;"><tr><td width=350>Curriculum : <b needAlt=1 class="font_h ul_class">'. $view_data['crclm_name'] . '</b></td><td width=350>';
    
echo 'Survey : <b needAlt=1 class="font_h ul_class">'. $view_data['survey_name'] . '</b></tr></table><br><br>';

echo '<h4 class="ul_class" style="text-align:center; font-size:14px;">'.$this->lang->line('student_outcome_full').'  '.$this->lang->line('student_outcome').' Indirect Attainment Analysis</h4><br>';    

echo "<img src='".$view_data['image_location']."' width='680' height='330' /><br>";

echo $view_data['indirect_attainment_table'].'<br>';
}