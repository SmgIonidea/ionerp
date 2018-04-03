import { RequestOptions, Http } from '@angular/http';
import { Subject } from 'rxjs';
import { DataTableDirective } from 'angular-datatables';
import { ToasterConfig } from 'angular2-toaster';
import { FormGroup, FormControl, Validators, ValidationErrors } from '@angular/forms';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import { ToastService } from './../../common/toast.service';
import { PostService } from './../../services/post.service';
import { Title } from '@angular/platform-browser';
import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { TinymceComponent } from '../../thirdparty/tinymce/tinymce.component';

@Component({
  selector: 'app-take-activity',
  templateUrl: './take-activity.component.html',
  styleUrls: ['./take-activity.component.css']
})
export class TakeActivityComponent implements OnInit {

  constructor(
    public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private http: Http) {
      router.events.subscribe(event => {
        if(event instanceof NavigationEnd) {
          // To get data from router
          var snapshot = activatedRoute.snapshot;
          this.mainTitle = snapshot.data.title;
        }
      });

      this.userId = localStorage.getItem('id');
  }

  /* Global variables declaration */
  private mainTitle: any;
  private userId: any;
  private curriculumType: Array<string> = [];
  private termType: Array<string> = [];
  private courseType: Array<string> = [];
  private crclmId: any = 0;
  private termId: any = 0;
  private crsId: any = 0;
  private title: string;
  private  type: string;
  //disable radio button
  private radioButtonDisable = false;
  private tosterconfig: any;
  private stuActivityList: Array<any>;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  // dtInstance:DataTables.Api;
  dtTrigger = new Subject();
  private clearValue: string = '';
  @ViewChild('fileInput') inputEl: ElementRef; //file upload
  private size: number; //file size
  private clickAns: boolean = false;
  private activityHead: any;
  public takeActivityTextValue; // To get tinymce text
  private studentActId: number;
  private colspanScaleAssessment: number = 0;
  private rubricsRange: number;
  private rangeExist: number = 0;
  private criteriaData = [];
  private curriculumName: any;
  private termName: any;
  private courseName: any;
  private activityDet: any;
  private securedTotalMarks: number;
  private totalMarks: number;


  // Function called on initialization
  ngOnInit() {
    this.titleService.setTitle('Student Activity | IONCUDOS');
    this.title = "Activity";
    
    this.service.subUrl = 'activity/student/ActivityStudent/index';
    this.service.createPost(this.userId).subscribe(response1 => {
      this.curriculumType = response1.json();
      if(localStorage.getItem('currDropdownId') != null && localStorage.getItem('currDropdownId') != '0') {
        this.curriculumType.forEach(termElement => {
          if(termElement['crclm_id'] == localStorage.getItem('currDropdownId')) {
            this.crclmId = termElement['crclm_id'];
            this.curriculumName = termElement['crclm_name'];
            let postData = {
              'userId': this.userId,
              'curDrop': this.crclmId
            };
          this.service.subUrl = 'activity/student/ActivityStudent/getTerm';
          this.service.createPost(postData).subscribe(response2 => {
            this.termType = response2.json();
            if(localStorage.getItem('termDropdownId') != null && localStorage.getItem('termDropdownId') != '0') {
              this.termType.forEach(termElement => {
                if(termElement['crclm_term_id'] == localStorage.getItem('termDropdownId')) {
                  this.termId = termElement['crclm_term_id'];
                  this.termName = termElement['term_name'];
                  let postData = {
                    'userId': this.userId,
                    'termDrop': this.termId
                  };
                  this.service.subUrl = 'activity/student/ActivityStudent/getCourse';
                  this.service.createPost(postData).subscribe(response3 => {
                    this.courseType = response3.json();
                    if(localStorage.getItem('courseDropdownId') != null && localStorage.getItem('courseDropdownId') != '0') {
                      this.courseType.forEach(termElement => {
                        if(termElement['crs_id'] == localStorage.getItem('courseDropdownId')) {
                          this.crsId = termElement['crs_id'];
                          this.courseName = termElement['crs_title'];
                            this.getActivity();
                          }
                        });
                      }
                    });
                  }
                });
              }
            });
          }
        });
      }
    });
  }


  /**
   * To destroy datatable before rendering table data
   * Params: 
   * Return: 
   */

  tableRerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
    });
  }
  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  /**
   * Function to load term dropdown data
   * Params: selected curriculum id
   * Return: term list
   */

   term(event) {
    // if(this.stuActivityList.length > 0) {
      this.stuActivityList = [];
      this.tableRerender();
      this.dtTrigger.next();
    // }
    localStorage.removeItem('termDropdownId');
    localStorage.removeItem('courseDropdownId');
    this.termType = [];
    this.courseType = [];
    this.termId = 0;
    this.crsId = 0;

    this.curriculumType.forEach(curriculumElement => {
      if(curriculumElement['crclm_id'] == event) {
        localStorage.setItem('currDropdown', curriculumElement['crclm_name']);
        localStorage.setItem('currDropdownId', curriculumElement['crclm_id']);
      }
    });
    this.curriculumName = localStorage.getItem('currDropdown');

    let postData = {
      'userId': this.userId,
      'curDrop': event
    };
    this.service.subUrl = 'activity/student/ActivityStudent/getTerm';
    this.service.createPost(postData).subscribe(response => {
      this.termType = response.json();
    });
  }

  
  /**
   * Function to load course dropdown data
   * Params: selected term id
   * Return: course list
   */

  course(event) {
    // if(this.stuActivityList.length > 0) {
      this.stuActivityList = [];
      this.tableRerender();
      this.dtTrigger.next();
    // }
    localStorage.removeItem('courseDropdownId');
    this.courseType = [];
    this.crsId = 0;

    this.termType.forEach(termElement => {
      if(termElement['crclm_term_id'] == event) {
        localStorage.setItem('termDropdown', termElement['term_name']);
        localStorage.setItem('termDropdownId', termElement['crclm_term_id']);
      }
    });
    this.termName = localStorage.getItem('termDropdown');

    let postData = {
      'userId': this.userId,
      'termDrop': event
    };
    this.service.subUrl = 'activity/student/ActivityStudent/getCourse';
    this.service.createPost(postData).subscribe(response => {
      this.courseType = response.json();
    });
  }

  
  // Form group declaration with predefined validators
  private addActivityAnsForm = new FormGroup({
    addActivityAnswer: new FormControl('', [
    ]),

    addActivityUpload: new FormControl('', [
      Validators.required
    ]),
    fileAdditionalInfo: new FormControl('',[
      Validators.maxLength(1000)
    ]),

    addActivityUrl: new FormControl('', [
      Validators.required
    ]),
    urlAdditionalInfo: new FormControl('',[
      Validators.maxLength(1000)
    ]),

  });


  // Property to access the formGroup controles, which are used to validate the form
  get addActivityAnswer() {
    return this.addActivityAnsForm.get('addActivityAnswer');
  }
  get addActivityUpload() {
    return this.addActivityAnsForm.get('addActivityUpload');
  }
  get fileAdditionalInfo() {
    return this.addActivityAnsForm.get('fileAdditionalInfo');
  }
  get addActivityUrl() {
    return this.addActivityAnsForm.get('addActivityUrl');
  }
  get urlAdditionalInfo() {
    return this.addActivityAnsForm.get('urlAdditionalInfo');
  }


  // To clear the input field
  clearInputField() {
    this.clearValue = '';
  }


  /**
   * Function to get student activity list
   * Params: 
   * Return: 
   */
  
  getActivity() {
    this.courseType.forEach(courseElement => {
      if(courseElement['crs_id'] == this.crsId) {
        localStorage.setItem('courseDropdown', courseElement['crs_title']);
        localStorage.setItem('courseDropdownId', courseElement['crs_id']);
      }
    });
    this.courseName = localStorage.getItem('courseDropdown');

    let postData = {
      'userId': this.userId,
      'crsDrop': this.crsId
    };
    
    this.service.subUrl = 'activity/student/ActivityStudent/getActivity';
    this.service.createPost(postData).subscribe(response => {
      this.stuActivityList = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table
    });
  }


  /**
   * Function to show toast message
   * Params: toast message type, title and body
   * Return: pop up toast
   */

   toasterMsg(type, title, body) {
    this.toast.toastType = type;
    this.toast.toastTitle = title;
    this.toast.toastBody = body;
    this.tosterconfig = new ToasterConfig({
      positionClass: 'toast-bottom-right',
      tapToDismiss: true,
      timeout: 1000,
      mouseoverTimerStop: true
    });
    this.toast.toastMsg;
  }

  /**
   * Function to save(draft) student actiivity answer
   * Params: 
   * Return: 
   */

  saveTinymceAnswer() {
    var content = tinymce.activeEditor.getContent();
    if(content === "" || content === null) {
      $("#error_tinymce").text("This field is required.");
    } else {
      $("#error_tinymce").empty();     
      this.takeActivityTextValue = tinymce.get('my-ans-editor').getContent();
      
      let postData = {
        'userId': this.userId,
        'actId': this.studentActId,
        'ans_content': this.takeActivityTextValue
      }

      this.service.subUrl = 'activity/student/ActivityStudent/saveStudentActivityAnswer';
      this.service.createPost(postData).subscribe(response => {
        if(response.json().status == 'ok') {
          let type = 'success';
          let title = 'Save Success';
          let body = 'Answer saved successfully.';
          this.toasterMsg(type, title, body);
        } else {
          let type = 'info';
          let title = 'Answer Not Updated';
          let body = 'Please, update answer and then save.';
          this.toasterMsg(type, title, body);
        }
        this.getActivity();
        tinymce.activeEditor.setContent('');
        this.clickAns = false;
      });
    }
  }


  /**
   * Function to submit student actiivity answer
   * Params: 
   * Return: 
   */

  submitTinymceAnswer() {
    var content = tinymce.activeEditor.getContent();
    if(content === "" || content === null) {
      $("#error_tinymce").text("This field is required.");
    } else {
      $("#error_tinymce").empty();     
      this.takeActivityTextValue = tinymce.get('my-ans-editor').getContent();

      let postData = {
        'userId': this.userId,
        'actId': this.studentActId,
        'ans_content': this.takeActivityTextValue
      }

      this.service.subUrl = 'activity/student/ActivityStudent/submitStudentActivityAnswer';
      this.service.createPost(postData).subscribe(response => {
        if(response.json().status == 'ok') {
          let type = 'success';
          let title = 'Submit Success';
          let body = 'Answer submitted successfully.';
          this.toasterMsg(type, title, body);
        } else {
          let type = 'error';
          let title = 'Submit Fail';
          let body = 'Answer submit failed. Please, try again.';
          this.toasterMsg(type, title, body);
        }
        this.getActivity();
        tinymce.activeEditor.setContent('');
        this.clickAns = false;
      });
    }
  }


  /**
   * Function to upload a file to folder and then to save filename
   * Params: file additional information
   * Return: a success toast message
   */

  uploadFile(info) {
    let inputEl: HTMLInputElement = this.inputEl.nativeElement;
    let fileCount: number = inputEl.files.length;
    
    if(fileCount > 0) {
      let formData: FormData = new FormData();
      formData.append('activityDoc', inputEl.files.item(0));
      // To get file name
      var fileName = inputEl.files.item(0).name;
      
      let headers = new Headers();
      // No need to include Content-Type in Angular 4 /
      headers.append('Content-Type', 'multipart/form-data');
      headers.append('Accept', 'application/json');
      
      this.http.post(this.service.baseUrl + 'activity/student/ActivityStudent/upload/', formData).subscribe(
        response => {          
          if(response.json().status == 'ok') {
            let postData = {
              'fileName': fileName,
              'info': info.value,
              'userId': this.userId,
              'actId': this.studentActId
            }

            this.service.subUrl = 'activity/student/ActivityStudent/submitStudentActivityFile';
            this.service.createPost(postData).subscribe(response => {
              if(response.json().status == 'ok') {
                let type = 'success';
                let title = 'File Upload Success';
                let body = 'File uploaded successfully.';
                this.toasterMsg(type, title, body);
                this.addActivityUpload.reset();
                this.fileAdditionalInfo.reset();
              } else {
                let type = 'error';
                let title = 'File Upload Fail';
                let body = 'File upload failed. Please, try again.';
                this.toasterMsg(type, title, body);
                this.addActivityUpload.reset();
                this.fileAdditionalInfo.reset();
              }
              this.clickAns = false;
              this.getActivity();
            });
          } else {
            let type = 'error';
            let title = 'File Upload Fail';
            let body = 'File upload failed. Please, try again.';
            this.toasterMsg(type, title, body);
            this.addActivityUpload.reset();
            this.fileAdditionalInfo.reset();
          }
          this.clickAns = false;
          this.getActivity();
      });
    }
  }


  /**
   * Function to save url
   * Params: url, url additional information
   * Return: a success toast message
   */

   saveUrl(url, info) {
    let postData = {
      'url': url.value,
      'info': info.value,
      'userId': this.userId,
      'actId': this.studentActId
    }

    this.service.subUrl = 'activity/student/ActivityStudent/submitStudentActivityUrl';
    this.service.createPost(postData).subscribe(response => {
      if(response.json().status == 'ok') {
        let type = 'success';
        let title = 'Submit Success';
        let body = 'Url submitted successfully.';
        this.toasterMsg(type, title, body);
        this.addActivityUrl.reset();
        this.urlAdditionalInfo.reset();
        this.clickAns = false;
      } else {
        let type = 'error';
        let title = 'Submit Fail';
        let body = 'Url submit failed. Please, try again.';
        this.toasterMsg(type, title, body);
        this.addActivityUrl.reset();
        this.urlAdditionalInfo.reset();
      }
      this.getActivity();
    });    
  }


  /**
   * Function called when student clicks to answer
   * Params: a row of activity details
   * Return: displays answer section
   */

  clickToAnswer(data) {
    this.clickAns = true;
    this.radioButtonDisable = false;
    this.activityHead = data.ao_method_name;
    this.type = 'answer';
    this.studentActId = data.ao_method_id;
    // tinymce.get('my-ans-editor').setContent('');
  }

  // Hides answer section
  cancelActAns() {
    this.clickAns = false;
  }


  /**
   * Function to validate uploaded file size
   * Params: uploaded file
   * Return: set filename to input field
   */

  onChange(event) {
    this.addActivityUpload.reset();
    let inputEl: HTMLInputElement = this.inputEl.nativeElement;
    //get the total amount of files attached to the file input
    let fileCount: number = inputEl.files.length;

    if(fileCount > 0) {
      this.size = inputEl.files.item(0).size;
      if(this.size > 2000000) {
          let type = 'warning';
          let title = 'Upload Warning';
          let body = 'File size should be less than 2 MB';
          this.toasterMsg(type, title, body);
      } else {
        var file = event.srcElement.files;
        var fileName = file[0]['name'];

        this.addActivityUpload.setValue(fileName);
      }
    }
  }


  /**
   * Function called when student clicks In-progress link
   * Params: a row of activity details
   * Return: displays answer section with draft saved data
   */

  inProgress(data) {
    this.clickAns = true;
    this.activityHead = data.ao_method_name;
    this.radioButtonDisable = true;
    this.type = 'answer';
    this.studentActId = data.ao_method_id;

    let postData = {
      'userId': this.userId,
      'actId': this.studentActId
    }

    this.service.subUrl = 'activity/student/ActivityStudent/getAnsStatus';
    this.service.createPost(postData).subscribe(response => {
      if(response.json()[0]['ans_status'] == 1) {
        this.service.subUrl = 'activity/student/ActivityStudent/fetchAnswerContent';
        this.service.createPost(postData).subscribe(response => {
          if(response.json()[0]['ans_content'] !== '') {
            tinymce.activeEditor.setContent(response.json()[0]['ans_content']);
          }
        });
      }
    });
  }


  /**
   * Function called when student clicks View Rubrics link
   * Params: a row of activity details
   * Return: displays rubrics list modal
   */

  viewRubrics(data) {
    this.studentActId = data.ao_method_id;

    // To get activity details to show at the top of rubrics list modal
    this.service.subUrl = 'activity/student/ActivityStudent/getActivityDetails';
    this.service.createPost(this.studentActId).subscribe(response => {
      this.activityDet = response.json();
    });

    // To list Rubrics criteria
    this.service.subUrl = 'activity/student/ActivityStudent/listCriteria';
    this.service.createPost(this.studentActId).subscribe(response => {
      this.criteriaData = response.json();
    });
    
    // To get Rubrics criteria range & scale
    this.service.subUrl = 'activity/student/ActivityStudent/getCriteriaRange';
    this.service.createPost(this.studentActId).subscribe(response => {
      this.rubricsRange = response.json().rubrics_data;
      this.colspanScaleAssessment = response.json().num_rows;
    });

    (<any>jQuery('#view_rubrics')).modal('show');
  }


  /**
   * Function to export rubrics list report to .pdf
   * Params: 
   * Return: 
   */

  exportRubricsListPdf() {
    let postData = {
      'curriculumName': this.curriculumName,
      'termName': this.termName,
      'courseName': this.courseName,
      'activityDetails': this.activityDet,
      'criteriaData': this.criteriaData,
      'colspanScaleAssessment': this.colspanScaleAssessment,
      'rubricsRange': this.rubricsRange
    };

    this.service.subUrl = 'activity/student/ActivityStudent/exportRubricsListPdf';
    this.service.createPost(postData).subscribe(response => {
      if(response.json().status == 'ok') {
        (<any>jQuery('#view_rubrics')).modal('hide');
        let type = 'success';
        let title = 'Pdf Save';
        let body = 'Pdf saved successfully in iondeliveryServer/application/third_party.';
        this.toasterMsg(type, title, body);
      }
    });
  }


  /**
   * Function called when student clicks Detailed Marks link
   * Params: a row of activity details
   * Return: displays rubrics list modal
   */

  detailedMarks(data) {
    this.studentActId = data.ao_method_id;
    this.securedTotalMarks = 0;
    this.totalMarks = 0;

    // To get activity details to show at the top of rubrics list modal
    this.service.subUrl = 'activity/student/ActivityStudent/getActivityDetails';
    this.service.createPost(this.studentActId).subscribe(response => {
      this.activityDet = response.json();
    });

    // User Id is required to fetch student assessment marks
    let postData = {
      'userId': this.userId,
      'actId': this.studentActId
    }

    // To list Rubrics criteria
    this.service.subUrl = 'activity/student/ActivityStudent/listCriteriaMarks';
    this.service.createPost(postData).subscribe(response => {
      this.criteriaData = response.json();      
      
      var length = this.criteriaData.length;
      for(var i = 0; i < length; i++) {
        this.securedTotalMarks += JSON.parse(this.criteriaData[i]['secured_marks']);
        this.totalMarks += JSON.parse(this.criteriaData[i]['total_marks']);
      }
    });    
    
    // To get Rubrics criteria range & scale
    this.service.subUrl = 'activity/student/ActivityStudent/getCriteriaRange';
    this.service.createPost(this.studentActId).subscribe(response => {
      this.rubricsRange = response.json().rubrics_data;
      this.colspanScaleAssessment = response.json().num_rows;
    });

    (<any>jQuery('#detailed_marks')).modal('show');
  }


  /**
   * Function to export detailed marks report to .pdf
   * Params: 
   * Return: 
   */

  exportDetailedMarksPdf() {
    let postData = {
      'curriculumName': this.curriculumName,
      'termName': this.termName,
      'courseName': this.courseName,
      'activityDetails': this.activityDet,
      'criteriaData': this.criteriaData,
      'colspanScaleAssessment': this.colspanScaleAssessment,
      'rubricsRange': this.rubricsRange,
      'securedTotalMarks': this.securedTotalMarks,
      'totalMarks': this.totalMarks
    };

    this.service.subUrl = 'activity/student/ActivityStudent/exportDetailedMarksPdf';
    this.service.createPost(postData).subscribe(response => {
      if(response.json().status == 'ok') {
        (<any>jQuery('#detailed_marks')).modal('hide');
        let type = 'success';
        let title = 'Pdf Save';
        let body = 'Pdf saved successfully in iondeliveryServer/application/third_party.';
        this.toasterMsg(type, title, body);
      }
    });
  }

}