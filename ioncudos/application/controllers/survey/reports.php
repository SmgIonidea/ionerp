<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reports extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model('/survey/Survey');
        $this->load->model('/survey/Survey_User');
        $this->load->model('/survey/Survey_Response');
        $this->load->model('/survey/host_Survey_model');
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        }
        if($this->ion_auth->in_group('admin') || $this->ion_auth->in_group('Chairman') || $this->ion_auth->in_group('Program Owner') || $this->ion_auth->in_group('Course Owner')){
            
        }else{
            redirect('configuration/users/blank', 'refresh');
        }
    }
    
    public function hostedSurvey(){
        $logged_in_user_dept_id = $this->ion_auth->user()->row()->user_dept_id;
        //echo 'Logged In';
        $data=array();
        $date = date('Y-m-d');    
			$user_id = $this->ion_auth->user()->row()->id;
			$dept_id = $this->ion_auth->user()->row()->user_dept_id;			
			$crclmList = $this->Survey->crclm_list($user_id, $dept_id);
			$data['crclm_list'][] = 'Select Curriculum';
			foreach ($crclmList as $crclm) {				
				$data['crclm_list'][$crclm['crclm_id']] = $crclm['crclm_name'];
			}
			$survey_for=$this->Survey->getMasters('survey_for','survey for');
			$survey_for=$this->Survey->getMasters('survey_for','survey for');
			$data['survey_for'][] = 'Select Survey';

			foreach ($survey_for as $key => $survey_name) {
				$data['survey_for'][$key] =$survey_name;
			}

       // $data['survey_for']=array('Select Survey for');
        $data['survey_type']=$this->Survey->getMasters('survey_type','survey type');
		
        $data['list']=$this->Survey->surveyList(null,array('su_survey.status !='=>'0'));
        $this->layout->navBarTitle='Hosted Survey Report';
        $data['content'] = $this->load->view('survey/reports/hosted_survey',$data,true);
        $this->load->view('survey/layout/default', $data);
    }
	
	public function get_not_responded_users(){
		$survey_id = $this->input->post('survey_id');
		$list_not_res_users = $this->Survey->fetch_survey_details($survey_id);
		$non_responded = $list_not_res_users['non_responded_users'];
		$survey_data['survey_name'] =  $list_not_res_users['survey_name'];
		
		$data  = '<b><a data-toggle="collapse" href="#non_responded_users" aria-expanded="false" aria-controls="non_responded_users"><i class=" icon-play"></i> List of Stakeholders who have not responded</a></b>';
		$data .= '<div class="collapse" id="non_responded_users">';
		$data .= '<div class="well">';
		$data .= '<table class="table table_qp">';
		$data .= '<thead>';
		//$data .= '<tr><th colspan="12"><center>Non Responded users for this survey</center></th></tr>';
		$data .= '<tr>';
		$data .= '<th>Name</th>';
		$data .= '<th>Email</th>';
		$data .= '<th>Contact No.</th>';
		$data .= '</tr>';
		$data .= '</thead>';
		$data .= '<tbody>';
		$j=1;
		foreach($non_responded as $non_resp_users){
		$data .= '<tr>';
		$data .= '<td>'.$j.'. '.$non_resp_users['first_name'].' '.$non_resp_users['last_name'].'</td>';
		$data .= '<td>'.$non_resp_users['email'].'</td>';
		$data .= '<td>'.$non_resp_users['contact'].'</td>';
		$data .= '</tr>';
		$j++;
		}
		$data .= '</tbody>';
		$data .= '</table>';
		$data .= '</div>';
		$data .= '</div><br>';
		
		$survey_data['not_resp_user_list'] = $data;
		echo json_encode($survey_data);
		
        
	}
    
    function intimate(){
        
        if ($this->input->is_ajax_request()) {
            $survey_id=$this->input->post('survey_id');
            
            if($survey_id==null){
                return false;
            }else{
                $surveyUserList = $this->Survey_User->surveyUsersById($survey_id,array('status != '=>'2'));
                $survey_name=$this->Survey->getSurveyName($survey_id);
				$survey_for = $this->Survey->getSurveyFor($surveyId); //Added by Shivaraj B 12-11-2015
                foreach($surveyUserList as $surveyUserDetails){
                   
                    $key = $surveyUserDetails['link_key'];
                    $user = $this->Survey_User->fetchUserDetailsByKey($key);                    
                    $to = $user[0]['email'];
                    $name =  $user[0]['first_name']." ".$user[0]['last_name'];
					//Edited by shivaraj B 12-11-2015
                    if($this->Survey->send_survey_mail($to,$key,$name,$survey_name,'survey_reminder',$survey_id,$survey_for)){
                        echo "Email successfylly sent..";
                    }
                }
            }
        } else {
            echo 'Sorry, It\'s not an ajax request';
        }
        exit();        
    }
    
    function hostedSurveyList(){
            $postData=$this->input->post();
			
            $programId=$departmentId=$surveyTypeId=$crclmId=$surveyId=$crclm_term_id='';
			
            if(is_array($postData) && array_key_exists('dept_id', $postData)){
                $departmentId=$this->input->post('dept_id');
            }

            if(is_array($postData) && array_key_exists('prgm_id', $postData)){
                $programId=$this->input->post('prgm_id');
            }
			
			if(is_array($postData) && array_key_exists('crclm_id', $postData)){
                $crclmId=$this->input->post('crclm_id');
            }
			
			if(is_array($postData) && array_key_exists('survey_for', $postData)){
                $surveyId=$this->input->post('survey_for');
            }

            if(is_array($postData) && array_key_exists('survey_type', $postData)){
                $surveyTypeId=$this->input->post('survey_type');
            }  
			if(is_array($postData) && array_key_exists('crclm_term_id', $postData)){
                $crclm_term_id = $this->input->post('crclm_term_id');
            }

            if($programId=='' && $departmentId=='' && $crclmId == '' && $surveyTypeId==''){     
                @$rw="<tr class='gradeU even'><td class='sorting_1 success' colspan=7><div class='text-center'>Select Program</div></td></tr>";
                //exit();
            }else{
                $condition=array('su_survey.status !='=> '0');

                if($programId){
                    $condition['su_survey.pgm_id']=$programId;
                }
				
				if($crclmId){
					$condition['su_survey.crclm_id']=$crclmId;
				}
				
				if($surveyId){
					$condition['su_survey.su_for']=$surveyId;
					$for = '1';
				}
				
                if($departmentId){
                    $condition['su_survey.dept_id']=$departmentId;
                }
                if($surveyTypeId){
                    $condition['su_survey.su_type_id']=$surveyTypeId;
                }  
				if($crclm_term_id){
                    $condition['su_survey.crclm_term_id']=$crclm_term_id;
                }
                $surveyList=$this->Survey->surveyList(null,$condition);
				//var_dump($surveyList);
				/* $stake_holder_count = $this->Survey->user_count($crclm_id=NULL,$survey_id,$crs_id=NULL,$su_for=NUll);
				if($stake_holder_count['user_count'] != 0){
					
				} */
				if(count($surveyList)>0){

                $rw="";
		
                foreach ($surveyList as $surKy => $listData){ 
					if($surveyList[$surKy]['mt'] == 'CO'){ $crs_detal = "[" .$listData['crs_title'] ."(". $listData['crs_code'] .")]"; }else {$crs_detal = " ";}
					if($listData['section_name'] !=""){ $surveyList[$surKy]['section_name']  = $listData['section_name'];}else{ $surveyList[$surKy]['section_name'] = " ALL" ;}
                    $rw.="<tr class='gradeU even odd'>"; 
					if($listData['user_count'] != 0 ){
						$rw.="<td class='sorting_1'>";
                              if($listData['section_name'] !=""){  $surveyList[$surKy]['name_survey']="<a href='survey/surveys/view_survey/$listData[survey_id]' id='view_survey_3'>$listData[name] $crs_detal </a>";}else{
							  $surveyList[$surKy]['name_survey']="<a href='survey/surveys/view_survey/$listData[survey_id]' id='view_survey_3'>$listData[name]</a>";
							  }
                        $rw.="</td>";
						
					}else{
						if($listData['user_count'] == 0 && !(empty($listData['crs_id']))){
						$rw.="<td class='sorting_1'>";
                                $surveyList[$surKy]['name_survey']="<a href='survey/surveys/indirect_attainment_report/$listData[crclm_id]/$listData[survey_id]/$listData[su_for]/$listData[crs_id]' id='view_survey_3'>$listData[name]</a>";
                        $rw.="</td>";
					}else{
						$listData['crs_id']=0;
						$rw.="<td class='sorting_1'>";
                                $surveyList[$surKy]['name_survey']="<a href='survey/surveys/indirect_attainment_report/$listData[crclm_id]/$listData[survey_id]/$listData[su_for]/$listData[crs_id]' id='view_survey_3'>$listData[name]</a>";
                        $rw.="</td>";
					}
					}
			if($listData['user_count'] != 0 ){
                                        $surveyList[$surKy]['report_link']="<a href='survey/surveys/view_survey/$listData[survey_id]' id='view_survey_3'>View Reports</a>";
                        }else{
                                if($listData['user_count'] == 0 && !(empty($listData['crs_id']))){
                                        $surveyList[$surKy]['report_link']="<a href='survey/surveys/indirect_attainment_report/$listData[crclm_id]/$listData[survey_id]/$listData[su_for]/$listData[crs_id]' id='view_survey_3'>View Reports</a>";
                                }else{
                                        $listData['crs_id']=0;
                                        $surveyList[$surKy]['report_link']="<a href='survey/surveys/indirect_attainment_report/$listData[crclm_id]/$listData[survey_id]/$listData[su_for]/$listData[crs_id]' id='view_survey_3'>View Reports</a>";
                                }
                            
                        }		
                        //$surveyList[$surKy]['report_link'] = "<a href='survey/surveys/indirect_attainment_report/$listData[crclm_id]/$listData[survey_id]/$listData[su_for]/$listData[crs_id]' id='view_survey_3'>View Reports</a>";
                       /* $rw.="<td class=''>$listData[dept_name]</td>";
                        $rw.="<td class=''>$listData[pgm_title]</td>";*/
                        $rw.="<td class=''>$listData[mdSuType_mt_details_name]</td>";
                        $rw.="<td class=''>$listData[start_date]</td>";
                        $rw.="<td class=''>$listData[end_date]</td>";
                        $rw.="<td class=''>".$surveyList[$surKy]['report_link']."</td>";
                        /*$rw.="<td class=''>";
                            $rw.="<center>";
                                $rw.="<a href='survey/surveys/edit_survey/$listData[survey_id]'><i class='icon-pencil'></i></a>";
                            $rw.="</center>";
                        $rw.="</td>";*/
                        $text=$status=$cls='';
                        if(($listData['status'] == 0) ){
                            $status=1;
                            $btn="<button class='btn btn-warning'>Initiate</button>";
                            $text='';
                        }else if(($listData['status'] == 1) ){
                            $status=2;                                    
                            $btn="<button class='btn btn-success'>Close</button>";
                        }else{
                            $status=3;                                    
                            $btn='<p class="btn btn-danger">Closed</p>';
                        }
                        //$surveyList[$surKy]['sts_survey'] = $btn;
                        $surveyList[$surKy]['sts_survey'] = "<a href='#' class='myModal_initiate_perform' sts='$status' onclick='return false;' id='modal_$listData[survey_id]'>$btn</a><a href='#myModal_initiate' data-toggle='modal' class='hidden' id='modal_action_click'><button class=''></button></a>";
                        $rw.="<td class=''>";
                            $rw.="<center>";
                                $rw.="<a href='#' class='myModal_initiate_perform' sts='$status' onclick='return false;' id='modal_$listData[survey_id]'>$btn</a>";
                                $rw.="<a href='#myModal_initiate' data-toggle='modal' class='hidden' id='modal_action_click'><button class=''></button></a>";
                            $rw.="</center>";
                        $rw.="</td>";
                        $surveyList[$surKy]['progress_survey'] = "<a data-toggle='modal' href='#' name='progress_button' onclick='display_progress($listData[survey_id]);'> Progress </a>";
                        $rw.="<td ><a data-toggle='modal' href='#' name='progress_button' onclick='display_progress($listData[survey_id]);'> Progress </a></td><td>";
                        if($listData['status']==2){
                            $btn2 ="<p class='btn btn-danger'>Closed</p>";
                        }else{
                            $btn2 =anchor("#myModalenableReminder", "<button class='btn btn-warning btn-remind'>Remind</button>", "data-toggle='modal' sid=$listData[survey_id] class='survey_remind_action_click' id= survey_remind_action_click_$listData[survey_id]");
                        }
                        $surveyList[$surKy]['remind_survey'] = $btn2;
                     $rw.="</td></tr>";                          
                }
				
                echo json_encode($surveyList);
            }else{
				$surveyList = array();
				echo json_encode($surveyList);
                @$rw="<tr class='gradeU even'><td class='sorting_1 success' colspan=7><div class='text-center'>No record found.</div></td></tr>";
            }
				
            
        }   

        //echo @$rw;
        exit();
    }
    
    function indirectAttainment(){
        $cond = array('su_for'=>'6','su_survey.crclm_id'=>'5');
        $surveyList = $this->Survey->surveyList(null,$cond);
        $peoList = array();
        $peoList = $this->Survey->surveyForList('6','5');
        
        $peoVal = "";
        
        foreach ($surveyList as $key=>$survey){
            
            $i = 1;
            $surveyData[$key] = $this->Survey->surveyData($survey['survey_id']);
            foreach ($surveyData[$key]['su_survey_questions'] as $key1 => $val1){
                if($i==1){
                $peoVal[$surveyData[$key]['su_survey_questions'][$key1]['peo']] = '';
                $peoVal[$surveyData[$key]['su_survey_questions'][$key1]['peo']]['val'] = '';
                $peoVal[$surveyData[$key]['su_survey_questions'][$key1]['peo']]['max'] = '';
                }
                
                $surveyData[$key]['su_survey_questions'][$key1]['respVal'] = "";
                //$peoVal[$surveyData[$key]['su_survey_questions'][$key1]['peo']] = "";
                
                $surveyData[$key]['su_survey_questions'][$key1]['respQuestions'] = $this->Survey_Response->questionResponseCount($key1);
                foreach($surveyData[$key]['su_survey_qstn_options'][$key1] as $key2 => $val2){
                    
                    $surveyData[$key]['su_survey_qstn_options'][$key1][$key2]['respOptions'] = $this->Survey_Response->optionResponseCount($val2['survey_qstn_option_id']);
                   // echo $surveyData[$key]['su_survey_questions'][$key1]['peo'];
                    $surveyData[$key]['su_survey_questions'][$key1]['respVal']+=(int)$surveyData[$key]['su_survey_qstn_options'][$key1][$key2]['respOptions']*(int)$surveyData[$key]['su_survey_qstn_options'][$key1][$key2]['option_val'];
                    (int)$peoVal[$surveyData[$key]['su_survey_questions'][$key1]['peo']]['val']+= (int)$surveyData[$key]['su_survey_qstn_options'][$key1][$key2]['respOptions']*(int)$surveyData[$key]['su_survey_qstn_options'][$key1][$key2]['option_val'];
                    
                    (int)$peoVal[$surveyData[$key]['su_survey_questions'][$key1]['peo']]['max']+= 4;
                    
                }
               $i++;
            }
            
        }
        
        
        $data['surveyData'] = $surveyData;
        $data['peoVal'] = $peoVal;
        $data['peoList'] = $peoList;
        $this->layout->navBarTitle='Attainment Reports';
        $data['content'] = $this->load->view('survey/reports/indirect_attainment',$data,true);
        $this->load->view('survey/layout/default', $data);
    }
}
?>