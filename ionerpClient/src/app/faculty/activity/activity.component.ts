import { forEach } from '@angular/router/src/utils/collection';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import { PostService } from './../../services/post.service';
import { IMultiSelectOption, IMultiSelectSettings, IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { IMyDpOptions, IMyDate, IMyDateModel } from 'mydatepicker';
import { FormGroup, Validators, FormControl, AbstractControl, FormGroupDirective } from '@angular/forms';
import { Component, OnInit, ViewChild, Injectable, Input, AfterViewInit } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { CharctersOnlyValidation } from './../../custom.validators';
import { ToastService } from "./../../common/toast.service";
import { ToasterConfig } from 'angular2-toaster';
import { DataTableDirective } from "angular-datatables";
import { Subject } from "rxjs";
import * as $ from 'jquery';

@Component({
  selector: 'app-activity',
  templateUrl: './activity.component.html',
  styleUrls: ['./activity.component.css']
})
@Injectable()
export class ActivityComponent implements OnInit, AfterViewInit {

  constructor(
    public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router,) {
      let d: Date = new Date();
      this.selDate = {
        year: d.getFullYear(),
        month: d.getMonth() + 1,
        day: d.getDate()
      };

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
  private tosterconfig: any;
  private title: string;
  private isSaveHide: boolean;
  private isUpdateHide: boolean = true;
  private selDate: IMyDate = { year: 0, month: 0, day: 0 };
  private topicOptions: IMultiSelectOption[];
  private activityList: Array<any> = [];
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  // dtInstance:DataTables.Api;
  dtTrigger = new Subject();
  @ViewChild(FormGroupDirective)
  formGroupDirective: FormGroupDirective;
  private setActivityId: any; // activity id used to update the details
  private editedIds: number[] = []; //
  @Input('activityId') delActivityId; // Input binding used to place activity id in hidden text box to delete the activity. this is one more way of input binding.
  private disabledChecked = [];
  private stu_usn = [];
  private stu_name: Array<any>;
  private id: number;
  private first_table = [];
  private second_table = [];
  private third_table = [];
  private curriculumName: any;
  private termName: any;
  private courseName: any;
  private sectionName: any;
  private sub: any;
  private activityDet: any;
  public currentPageVal: any;
  private studentIds = [];
  private columnShow = {};
  private assignedStuList: any;
  private stuSubmit = [];
  private stuNotSubmit = [];
  private percentage: number;
  private curriculumType: Array<string> = [];
  private termType: Array<string> = [];
  private courseType: Array<string> = [];
  private sectionType: IMultiSelectOption[];
  private crclmId: any = 0;
  private termId: any = 0;
  private crsId: any = 0;
  private sectionId: any = 0;
  private changedTopic: number[] = [];
  private topicType: IMultiSelectOption[];

  topicMultSettings1 : IMultiSelectSettings = {
    dynamicTitleMaxItems: 0,
    displayAllSelectedText: true
  }
  topicMultSettings2 : IMultiSelectSettings = {
    dynamicTitleMaxItems: 1,
    displayAllSelectedText: true
  }
  myTexts1: IMultiSelectTexts = {
    checked: 'item selected',
    checkedPlural: 'items selected',
    defaultTitle: 'Select Topic',
    allSelected: 'All selected'
  };
  myTexts2: IMultiSelectTexts = {
    checkedPlural: 'items selected',
    defaultTitle: 'Select Topic',
    allSelected: 'All selected'
  };

  
  // Function get called on initialization
  ngOnInit() {
    this.titleService.setTitle('Activity | IONCUDOS');
    this.title = "Add Activity";
    this.isSaveHide = false;
    this.isUpdateHide = true; 
    this.currentPageVal = 'activity';
    
    //sort according to 2nd column, by default dt sorts by 1st column
    /* this.dtOptions = {
      "order": [[1, 'asc']]
    }; */

    // To show the columns based on iondelivery_config value
    // this.service.subUrl = 'activity/faculty/ActivityFaculty/getDeliveryConfig';
    // this.service.getData().subscribe(response => {
    //   for (var i = 0; i <= 2; i++) {
    //     this.columnShow[response.json()[i]['entity_id']] = response.json()[i]['iondelivery_config'];
    //     this.tableRerender();
    //     this.dtTrigger.next();
    //   }      
    // });

    this.service.subUrl = 'activity/faculty/ActivityFaculty/getCurriculum';
    this.service.createPost(this.userId).subscribe(response1 => {
      this.curriculumType = response1.json();
      if (localStorage.getItem('currDropdownId') != null && localStorage.getItem('currDropdownId') != '0') {
        this.curriculumType.forEach(termElement => {
          if (termElement['crclm_id'] == localStorage.getItem('currDropdownId')) {
            this.crclmId = termElement['crclm_id'];
            this.curriculumName = termElement['crclm_name'];
            let postData = {
              'userId': this.userId,
              'curDrop': this.crclmId
            };
            this.service.subUrl = 'activity/faculty/ActivityFaculty/getTerm';
            this.service.createPost(postData).subscribe(response2 => {
              this.termType = response2.json();
              if (localStorage.getItem('termDropdownId') != null && localStorage.getItem('termDropdownId') != '0') {
                this.termType.forEach(termElement => {
                  if (termElement['crclm_term_id'] == localStorage.getItem('termDropdownId')) {
                    this.termId = termElement['crclm_term_id'];
                    this.termName = termElement['term_name'];
                    let postData = {
                      'userId': this.userId,
                      'termDrop': this.termId
                    };
                    this.service.subUrl = 'activity/faculty/ActivityFaculty/getCourse';
                    this.service.createPost(postData).subscribe(response3 => {
                      this.courseType = response3.json();
                      if (localStorage.getItem('courseDropdownId') != null && localStorage.getItem('courseDropdownId') != '0') {
                        this.courseType.forEach(termElement => {
                          if (termElement['crs_id'] == localStorage.getItem('courseDropdownId')) {
                            this.crsId = termElement['crs_id'];
                            this.courseName = termElement['crs_title'];
                            let postData = {
                              'userId': this.userId,
                              'crsDrop': this.crsId
                            };
                            this.service.subUrl = 'activity/faculty/ActivityFaculty/getSection';
                            this.service.createPost(postData).subscribe(response4 => {
                              this.sectionType = response4.json();
                              if (localStorage.getItem('sectionDropdownId') != null && localStorage.getItem('sectionDropdownId') != '0') {
                                this.sectionType.forEach(termElement => {
                                  if (termElement['id'] == localStorage.getItem('sectionDropdownId')) {
                                    this.sectionId = termElement['id'];
                                    this.getActivityList();
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
        });
      }
    });
  }

  
  /**
   * Function to load term dropdown data
   * Params: selected curriculum id
   * Return: term list
   */

  term(event) {
    // if(this.activityList.length > 0) {
      this.activityList = [];
      this.tableRerender();
      this.dtTrigger.next();
    // }
    this.sectionType = [];
    localStorage.removeItem('termDropdownId');
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');

    this.termType = [];
    this.courseType = [];
    this.sectionType = [];
    this.termId = 0;
    this.crsId = 0;
    this.sectionId = 0;

    this.curriculumType.forEach(curriculumElement => {
      if (curriculumElement['crclm_id'] == event) {
        localStorage.setItem('currDropdown', curriculumElement['crclm_name']);
        localStorage.setItem('currDropdownId', curriculumElement['crclm_id']);
      }
    });

    this.curriculumName = localStorage.getItem('currDropdown');

    let postData = {
      'userId': this.userId,
      'curDrop': event
    };

    this.service.subUrl = 'activity/faculty/ActivityFaculty/getTerm';
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
    // if(this.activityList.length > 0) {
      this.activityList = [];
      this.tableRerender();
      this.dtTrigger.next();
    // }
    this.sectionType = [];
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');

    this.courseType = [];
    this.sectionType = [];
    this.crsId = 0;
    this.sectionId = 0;

    this.termType.forEach(termElement => {
      if (termElement['crclm_term_id'] == event) {
        localStorage.setItem('termDropdown', termElement['term_name']);
        localStorage.setItem('termDropdownId', termElement['crclm_term_id']);
      }
    });

    this.termName = localStorage.getItem('termDropdown');

    let postData = {
      'userId': this.userId,
      'termDrop': event
    };

    this.service.subUrl = 'activity/faculty/ActivityFaculty/getCourse';
    this.service.createPost(postData).subscribe(response => {
      this.courseType = response.json();
    });
  }


  /**
   * Function to load section dropdown data
   * Params: selected course id
   * Return: section list
   */

  section(event) {
    // if(this.activityList.length > 0) {
      this.activityList = [];
      this.tableRerender();
      this.dtTrigger.next();
    // }
    localStorage.removeItem('sectionDropdownId');

    this.sectionType = [];
    this.sectionId = 0;

    this.courseType.forEach(courseElement => {
      if (courseElement['crs_id'] == event) {
        localStorage.setItem('courseDropdown', courseElement['crs_title']);
        localStorage.setItem('courseDropdownId', courseElement['crs_id']);
      }
    });

    this.courseName = localStorage.getItem('courseDropdown');

    let postData = {
      'userId': this.userId,
      'crsDrop': event
    };

    this.service.subUrl = 'activity/faculty/ActivityFaculty/getSection';
    this.service.createPost(postData).subscribe(response => {
      this.sectionType = response.json();
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


  // to get date in this format
  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };
  public myDatePickerOptions1: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true,
  };
  // variables to get specific date
  public model: any;
  public model1: any;


  // Form group declaration with predefined validators
  activityForm = new FormGroup({
    activityName: new FormControl('', [
      Validators.required
    ]),
    activityDetails: new FormControl('',[
      Validators.maxLength(1000)
    ]),
    addTopics: new FormControl(),
    initialDate: new FormControl('', Validators.required),
    endDate: new FormControl('', Validators.required)
  });


  // Property to access the formGroup controles, which are used to validate the form
  get activityName() {
    return this.activityForm.get('activityName');
  }

  get activityDetails() {
    return this.activityForm.get('activityDetails');
  }

  get addTopics() {
    return this.activityForm.get('addTopics');
  }

  get initialDate() {
    return this.activityForm.get('initialDate');
  }

  get endDate() {
    return this.activityForm.get('endDate');
  }

  
  // Hide update button
  cancelUpdate() {
    this.activityForm.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.title = "Add Activity";
  }


  /**
   * Function to save activity
   * Params: form data inputs
   * Return: activity list
   */
  
  saveActivity(Form) {
    if (this.crclmId != null && this.crclmId != '0' && this.termId != null && this.termId != '0' && this.crsId != null && this.crsId != '0' && 
      this.sectionId != null && this.sectionId != '0') {
        
        this.service.subUrl = 'activity/faculty/ActivityFaculty/createActivity';
        let activityDetails = Form.value; // Text Field/Form Data in Json Format
        let postData = {
          'curclmDrop': this.crclmId,
          'termDrop': this.termId,
          'courseDrop': this.crsId,
          'activityDetails': activityDetails,
          'sectionDrop': this.sectionId,
          'allTopics': this.topicOptions
        };

        this.service.createPost(postData).subscribe(response1 => {
          if (response1.json().status == 'ok') {
            this.service.subUrl = 'activity/faculty/ActivityFaculty/index';
            this.service.createPost(postData).subscribe(response2 => {
              this.activityList = response2.json();
              this.tableRerender();
              this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
            });
            let type = 'success';
            let title = 'Add Success';
            let body = 'New Activity Added Successfully.';
            this.toasterMsg(type, title, body);
            this.formGroupDirective.resetForm();
            this.getActivityList();
          } else {
            let type = 'error';
            let title = 'Add Fail';
            let body = 'New Activity Add Failed, Please Try Again.';
            this.toasterMsg(type, title, body);
            this.formGroupDirective.resetForm();
            this.getActivityList();
          }
        }); 
    } else {
      let type = 'warning';
      let title = 'Warning';
      let body = 'Please, Select all the drop-downs with red mark.';
      this.toasterMsg(type, title, body);
    }
  }


  /**
   * Function to get data for edit fields
   * Params: data
   * Return: 
   */

  editActivity(data) {
    this.title = "Edit Activity";
    var topicIdMultiValue = [];
    topicIdMultiValue = data.topic[0]['actual_id'].split(',');
    topicIdMultiValue.forEach(element => {
      this.editedIds;
    });

    let activityInitialDate = data.initiate_date;
    let year = activityInitialDate.substring(0, 4);
    let month = activityInitialDate.substring(5, 7);
    let day = activityInitialDate.substring(8, 10);
    this.model = { date: { year: year, month: month, day: day } };

    let activityEndDate = data.end_date;
    let year1 = activityEndDate.substring(0, 4);
    let month1 = activityEndDate.substring(5, 7);
    let day1 = activityEndDate.substring(8, 10);
    this.model1 = { date: { year: year1, month: month1, day: day1 } };

    this.activityName.setValue(data.ao_method_name);
    this.initialDate.setValue(this.model);
    this.endDate.setValue(this.model1);
    this.activityDetails.setValue(data.ao_method_description);
    this.setActivityId = data.ao_method_id;
    this.addTopics.setValue(topicIdMultiValue);
    this.isSaveHide = true;
    this.isUpdateHide = false;
  }


  /**
   * Function to update activity
   * Params: updated data
   * Return: activity list
   */
  
  updatePost(updateForm) {
    this.service.subUrl = 'activity/faculty/ActivityFaculty/updateActivity';
    let activityDetails = updateForm.value; // Text Field/Form Data in Json Format

    let postData = {
      'activityId' : this.setActivityId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'activityDetails': activityDetails,
      'sectionDrop': this.sectionId
    };

    this.service.createPost(postData).subscribe(response1 => {
      if (response1.json().status == 'ok') {
        this.service.subUrl = 'activity/faculty/ActivityFaculty/index';
        this.service.createPost(postData).subscribe(response2 => {
          this.activityList = response2.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Activity Updated Successfully.';
        this.toasterMsg(type, title, body);
        this.getActivityList();
        this.activityForm.reset();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Activity Update Failed, Please Try Again.';
        this.toasterMsg(type, title, body);
        this.getActivityList();
        this.activityForm.reset();        
      }
    });
    this.title = "Add Activity";
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }


  /**
   * Function to filter list page data whenever topic dropdown gets changed
   * Params: topics id's
   * Return: activity list based on topic id's
   */
  
  filterActivity(topics) {
    if(topics.length !== 0) {
      let postData = {
        'curclmDrop': this.crclmId,
        'termDrop': this.termId,
        'courseDrop': this.crsId,
        'sectionDrop': this.sectionId,
        'topics': topics
      };

      this.service.subUrl = 'activity/faculty/ActivityFaculty/filterActivity';
      this.service.createPost(postData).subscribe(response => {
        this.activityList = response.json();
        this.tableRerender();
        this.dtTrigger.next(); // Calling the DT trigger to manually render the table    
      });
    } else {
      let postData = {
        'curclmDrop': this.crclmId,
        'termDrop': this.termId,
        'courseDrop': this.crsId,
        'sectionDrop': this.sectionId
      };
            
      this.service.subUrl = 'activity/faculty/ActivityFaculty/index';
      this.service.createPost(postData).subscribe(response => {
        this.activityList = response.json();
        this.tableRerender();
        this.dtTrigger.next(); // Calling the DT trigger to manually render the table    
      });
    }
  }


  // Function to show an alert message to delete record
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let activityId = deleteElement.getAttribute('activityId');
    this.delActivityId = activityId;

    (<any>jQuery('#activityDeleteModal')).modal('show');
  }


  /**
   * Function to delete an activity
   * Params: activity id
   * Return: activity list
   */
  
  deleteActivityData(activityIdInput: HTMLInputElement) {
    let postData = {
      'activityId': activityIdInput.value,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'sectionDrop': this.sectionId
    };
    
    this.service.subUrl = 'activity/faculty/ActivityFaculty/deleteActivity';
    this.service.deletePost(postData).subscribe(response1 => {
      
      if (response1.json().status == 'ok') {
        this.service.subUrl = 'activity/faculty/ActivityFaculty/index';
        this.service.createPost(postData).subscribe(response2 => {
          this.activityList = response2.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Activity Deleted Successfully.'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#activityDeleteModal')).modal('hide');
        this.activityForm.reset();
        this.getActivityList();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Activity Delete Failed, Please Try Again.'
        this.toasterMsg(type, title, body);
        this.activityForm.reset();
        this.getActivityList();
      }
    });
    this.title = "Add Activity";
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }


  // Function to get selected individual student in Manage Students Module
  checkIfSelected(data: HTMLElement) {
    var idVal = data.attributes.getNamedItem('id').value;
    var val = data.attributes.getNamedItem('value').value;

    if($('#' + idVal).is(":checked")) {
      this.studentIds.push(parseInt(val));
    } else {
      this.studentIds.splice($.inArray(parseInt(val), this.studentIds), 1);
    }

    if(this.studentIds.length > 0) {
      $("#submit_stu").removeAttr('disabled');
    } else {
      $("#submit_stu").attr('disabled', 'disabled');
    }
  }

  // Function to check all checkboxes
  checkAll(data: HTMLElement) {
    var className = data.attributes.getNamedItem('data-className').value;
    var idVal = data.attributes.getNamedItem('id').value;
    var studentIdAdd;
    var studentIdRemove;

    if($('#' + idVal).is(":checked")) {
      var len = document.getElementsByClassName(className).length;
      for(let i = 0; i < len; i++) {
        $('#' + className + i).prop('checked',true);
        studentIdAdd = $('#' + className + i).val();
        if($('#' + className + i).is(":disabled") == false) {
          if(!this.studentIds.includes(parseInt(studentIdAdd))) {
            this.studentIds.push(parseInt(studentIdAdd));
          }
        }
      }
    } else {
      var len = document.getElementsByClassName(className).length;
      for(let i = 0; i < len; i++) {
        if($('#' + className + i).is(":disabled") !== true) {
          $('#' + className + i).prop('checked',false);
          studentIdRemove = $('#' + className + i).val();
          this.studentIds.splice($.inArray(parseInt(studentIdRemove),this.studentIds),1);
        }
      }
    }
    
    if(this.studentIds.length > 0) {
      $("#submit_stu").removeAttr('disabled');
    } else {
      $("#submit_stu").attr('disabled', 'disabled');
    }
  }
  
  // Function to uncheck all checkboxes
  uncheckAll() {
    $('input[type="checkbox"]:checked').prop('checked', false);
    this.studentIds = [];
    this.stu_usn = [];
    $("#submit_stu").attr('disabled', 'disabled');
  }


  /**
   * Function to save students of Manage Students
   * Params: 
   * Return: success toast message
   */
  
  saveStudents() {
    this.service.subUrl = 'activity/faculty/ActivityFaculty/getStudentUsn';
    this.service.createPost(this.studentIds).subscribe(response1 => {
      this.stu_usn = response1.json();
    
      let postData = {
        'curclmDrop': this.crclmId,
        'termDrop': this.termId,
        'courseDrop': this.crsId,
        'sectionDrop': this.sectionId,
        'activityId': this.id,
        'checkedId': this.studentIds,
        'checkedUsn': this.stu_usn
      };

      this.service.subUrl = 'activity/faculty/ActivityFaculty/saveStudent';
      this.service.createPost(postData).subscribe(response2 => {
        if(response2.json().status == 'ok') {
          let type = 'success';
          let title = 'Host Success';
          let body = 'Activity Hosted Successfully.';
          this.toasterMsg(type, title, body);
          this.getActivityList();
          this.studentIds = [];
          this.stu_usn = [];
          $("#submit_stu").attr('disabled', 'disabled');
        } else {
          let type = 'error';
          let title = 'Host Fail';
          let body = 'Activity host Failed, Please Try Again.';
          this.toasterMsg(type, title, body);
          this.getActivityList();
        }
      });
    });
    $("#submit_stu").attr('disabled', 'disabled');
    (<any>jQuery('#manage_students')).modal('hide'); 
  }


  /**
   * Function to be called when manage students link gets clicked
   * Params: activity id
   * Return: disables checkboxes
   */
  
  manageStudents(activityId) {
    this.id = activityId;

    // To get Rubrics Finalize status
    this.service.subUrl = 'activity/faculty/ActivityFaculty/getRubricsFinalizeStatus';
    this.service.createPost(this.id).subscribe(response1 => {
      if (response1.json()[0]['dlvry_finalize_status'] == '1') {

        let postData = {
          'curclmDrop': this.crclmId,
          'secDrop': this.sectionId
        };

        // To fetch students
        this.first_table = [];
        this.second_table = [];
        this.third_table = [];
        this.service.subUrl = 'activity/faculty/ActivityFaculty/getStudentNames';
        this.service.createPost(postData).subscribe(response2 => {
          this.stu_name = response2.json();
          var length = this.stu_name.length;
          
          for(var i = 0; i < length; i++) {
            let first = Math.ceil(length / 3);
            let second = Math.ceil((length - first) / 2);
            if(i < first) {
              this.first_table.push({
                'ssd_id': this.stu_name[i].ssd_id,
                'student_usn': this.stu_name[i].student_usn,
                'first_name': this.stu_name[i].first_name
              });
            } else if(i < first + second) {
              this.second_table.push({
                'ssd_id': this.stu_name[i].ssd_id,
                'student_usn': this.stu_name[i].student_usn,
                'first_name': this.stu_name[i].first_name
              });
            } else {
              this.third_table.push({
                'ssd_id': this.stu_name[i].ssd_id,
                'student_usn': this.stu_name[i].student_usn,
                'first_name': this.stu_name[i].first_name
              });
            }
          }
        });

        // To get activity details to show at the top of manage students modal
        this.service.subUrl = 'activity/faculty/ActivityFaculty/getActivityDetails';
        this.service.createPost(this.id).subscribe(response3 => {
          this.activityDet = response3.json();
        });

        // To get delivery status
        this.service.subUrl = 'activity/faculty/ActivityFaculty/getDeliveryFlag';
        this.service.createPost(this.id).subscribe(response4 => {
          if(response4.json()[0]['dlvry_flag'] == 1 || response4.json()[0]['dlvry_flag'] == 2) {
            // To get student id to disable
            this.service.subUrl = 'activity/faculty/ActivityFaculty/getStudentIdDisable';
            this.service.createPost(this.id).subscribe(response5 => {
              this.disabledChecked = response5.json();
              this.disabledChecked.forEach(element => {
                $("input[value='" + element.student_id + "']").prop('checked', true);
                $("input[value='" + element.student_id + "']").prop('disabled', true);
              });
            });
          }
        });

        (<any>jQuery('#manage_students')).modal('show');
      } else {
        let type = 'info';
        let title = 'Finalize Rubrics';
        let body = 'Please, define and finalize rubrics.';
        this.toasterMsg(type, title, body);
      }
    });    
  }

  // When topic dropdown gets clicked, add css to avoid scroll bar
  increaseHeight() {
    document.getElementById("edit_page").style.marginBottom="80px";
  }

  // When initial date selected, < due date will be disabled
  onDateChanged(event: IMyDateModel) {
    this.myDatePickerOptions1.disableUntil.day = event.date.day - 1;
    this.myDatePickerOptions1.disableUntil.month = event.date.month;
    this.myDatePickerOptions1.disableUntil.year = event.date.year;
    this.selDate = event.date;
  }


  /**
   * Function to be called when view progress link gets clicked, to get %
   * Params: activity id
   * Return: % in progress bar
   */
  
  viewProgress(activityId) {
    this.assignedStuList = [];
    this.stuSubmit = [];
    this.stuNotSubmit = [];
    this.id = activityId;

    // To get delivery status
    this.service.subUrl = 'activity/faculty/ActivityFaculty/getDeliveryFlag';
    this.service.createPost(this.id).subscribe(response1 => {
      if(response1.json()[0]['dlvry_flag'] == 1 || response1.json()[0]['dlvry_flag'] == 2) {
        // To get activity details to show at the top of view progress modal
        this.service.subUrl = 'activity/faculty/ActivityFaculty/getActivityDetails';
        this.service.createPost(this.id).subscribe(response2 => {
          this.activityDet = response2.json();
        });
          
        this.service.subUrl = 'activity/faculty/ActivityFaculty/getStudentsToGetProgress';
        this.service.createPost(this.id).subscribe(response3 => {
          this.assignedStuList = response3.json();
          
          var assignedStuCount = this.assignedStuList.length;
          var stuSubmitCount = 0;

          for(var i=0; i < assignedStuCount; i++) {
            if(this.assignedStuList[i]['ans_status']==2) {
              this.stuSubmit.push({
                'student_usn': this.assignedStuList[i]['student_usn'],
                'first_name': this.assignedStuList[i]['first_name']
              });
              stuSubmitCount++;
            } else {
              this.stuNotSubmit.push({
                'student_usn': this.assignedStuList[i]['student_usn'],
                'first_name': this.assignedStuList[i]['first_name']
              });
            }
          }

          //to get % of no of students submitted out of no of students assigned
          this.percentage = (stuSubmitCount / assignedStuCount) * 100;
          // if percentage is Not a number, return 0 else return percentage
          if (isNaN(this.percentage)) {
            return this.percentage = 0;
          } else {            
            return this.percentage;
          }
        });

        (<any>jQuery('#view_progress')).modal('show');
      } else {
        let type = 'info';
        let title = 'Assign Students';
        let body = 'Students are not yet assigned.';
        this.toasterMsg(type, title, body);
      }
    });
  }


  /**
   * Function to list activities
   * Params: 
   * Return: 
   */

   getActivityList() {
    this.sectionType.forEach(sectionElement => {
      if (sectionElement['id'] == this.sectionId) {
        localStorage.setItem('sectionDropdown', sectionElement['name']);
        localStorage.setItem('sectionDropdownId', sectionElement['id']);
      }
    });
    this.sectionName = localStorage.getItem('sectionDropdown');

    let postData = {
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'sectionDrop': this.sectionId,
      'userId': this.userId
    };

    if (this.sectionId != '0' && this.sectionId != null) {
      this.service.subUrl = 'activity/faculty/ActivityFaculty/index';
      this.service.createPost(postData).subscribe(response1 => {
        this.activityList = response1.json();
        this.tableRerender();
        this.dtTrigger.next(); // Calling the DT trigger to manually render the table    
      });

      this.service.subUrl = 'activity/faculty/ActivityFaculty/getTopic';
      this.service.createPost(postData).subscribe(response2 => {
        this.topicOptions = this.topicType = response2.json();
      });
    } else {
      this.topicOptions = this.topicType = [];
      this.activityList = [];
      this.tableRerender();
      this.dtTrigger.next();
    }
  }


  /**
   * Function to be called when review activity link gets clicked
   * Params: activity id
   * Return: navigates review activity page
   */

  reviewActivity(activityId) {
    // To get delivery status
    this.service.subUrl = 'activity/faculty/ActivityFaculty/getDeliveryFlag';
    this.service.createPost(activityId).subscribe(response => {
      if(response.json()[0]['dlvry_flag'] == 1 && response.json()[0]['dlvry_finalize_status'] == 0){
        let type = 'info';
        let title = 'Finalize Rubrics';
        let body = 'Please, define and finalize rubrics.';
        this.toasterMsg(type, title, body);
      }else if(response.json()[0]['dlvry_flag'] == 1 || response.json()[0]['dlvry_flag'] == 2) {
        this.router.navigate(['/content',{outlets:{appCommon:['activity','reviewactivity']}}], {queryParams: {id: activityId}});
      } else {
        let type = 'info';
        let title = 'Assign Students';
        let body = 'Students are not yet assigned.';
        this.toasterMsg(type, title, body);
      }
    });
  }

}