import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { Component, OnInit, Input, ViewChild, AfterViewInit } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { Router, ActivatedRoute, NavigationEnd, Event } from '@angular/router';
import { PostService } from '../../services/post.service';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { IMultiSelectSettings } from 'angular-2-dropdown-multiselect';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ContentWrapperComponent } from '../../content-wrapper/content-wrapper.component';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { CharctersOnlyValidation } from '../../custom.validators';

@Component({
  selector: 'app-lesson-schedule',
  templateUrl: './lesson-schedule.component.html',
  styleUrls: ['./lesson-schedule.component.css']
})
export class LessonScheduleComponent implements OnInit, AfterViewInit {
  private selDate: IMyDate = { year: 0, month: 0, day: 0 };
  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true,

    // allowSelectionOnlyInCurrentMonth:true
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
    openSelectorOnInputClick: true
  };
  // variables to get specific date
  public model: any;
  public model1: any;

  tosterconfig;
  courseId;
  posts: Array<any>;
  // posts = [];
  curriculumType: Array<string>;
  termType: Array<string> = [];
  courseType: Array<string> = [];
  sectionType: Array<string> = [];
  topicType: Array<string> = [];
  // sectionType: IMultiSelectOption[];
  // instructorID = 475;
  instructorID = localStorage.getItem('id')
  currDropdown: string;
  termDropdown: string;
  courseDropdown: string;
  topicDropdown: string;
  sectionDropdown: string;
  optionsModel: number[];
  lessonScheduleEditData: Array<any>;
  setLessonScheduleId: any;
  bloom_update: Array<any> = [];
  mtd_id_update: Array<any> = [];
  public currentPageVal;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();
  title: any;
  bloomType: IMultiSelectOption[];
  DeliverMethodType: IMultiSelectOption[];
  myDeliveryMethod: IMultiSelectTexts = {};
  myBlooms: IMultiSelectTexts = {};
  slno;
  status;
  title1: any;
  crclmId: any = 0;
  termId: any = 0;
  crsId: any = 0;
  secID: any = 0;
  topID: any = 0;
  crsvalue
  lessonScheduleEditstatus: Array<any>;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  @Input('reId') reopenId;
  @Input('lessonschId') delLessonId; // Input binding used to place Lesson id in hidden text box to delete the Lesson Schedule. this is one more way of input binding.


  mybloom: IMultiSelectTexts = {};
  mydlvrmethod: IMultiSelectTexts = {};
  optionsBloom: number[];
  optionsdlvrmethod: number[];

  constructor(public titleService: Title, private toast: ToastService, router: Router, private service: PostService,
    private activatedRoute: ActivatedRoute) {
    router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.title = this.getTitle(router.routerState, router.routerState.root).join('-');
      }
    });

    let d: Date = new Date();
    this.selDate = {
      year: d.getFullYear(),
      month: d.getMonth() + 1,
      day: d.getDate()
    };
  }
  ngOnInit() {
    this.currentPageVal = 'lessonschedule';
    this.title = 'Lesson Schedule';
    this.title1 = "Add Portion to be Covered";
    this.titleService.setTitle('Lesson Schedule | IONCUDOS');

    var userId = localStorage.getItem('id');
    this.service.subUrl = 'lesson_schedule/LessonSchedule/getcurriculum';
    this.service.createPost(this.instructorID).subscribe(response => {
      this.curriculumType = response.json();
      if (localStorage.getItem('currDropdownId') != null && localStorage.getItem('currDropdownId') != '0') {
        this.curriculumType.forEach(termElement => {
          if (termElement['crclm_id'] == localStorage.getItem('currDropdownId')) {
            this.crclmId = termElement['crclm_id'];
            this.getDeliveryMethod(this.crclmId);
            let postData = {
              'userId': userId,
              'curDrop': this.crclmId,
            };
            this.service.subUrl = 'configuration/Master/getTerm';
            this.service.createPost(postData).subscribe(response => {
              this.termType = response.json();
              if (localStorage.getItem('termDropdownId') != null && localStorage.getItem('termDropdownId') != '0') {
                this.termType.forEach(termElement => {
                  if (termElement['crclm_term_id'] == localStorage.getItem('termDropdownId')) {
                    this.termId = termElement['crclm_term_id'];
                    let postData = {
                      'userId': userId,
                      'termDrop': this.termId,
                    };
                    this.service.subUrl = 'configuration/Master/getCourse';
                    this.service.createPost(postData).subscribe(response => {
                      this.courseType = response.json();
                      if (localStorage.getItem('courseDropdownId') != null && localStorage.getItem('courseDropdownId') != '0') {
                        this.courseType.forEach(termElement => {
                          if (termElement['crs_id'] == localStorage.getItem('courseDropdownId')) {
                            this.crsId = termElement['crs_id'];
                            this.getBloomType(this.crsId);
                            let postData = {
                              'userId': userId,
                              'crsDrop': this.crsId,
                            };
                            this.service.subUrl = 'configuration/Master/getSection';
                            this.service.createPost(postData).subscribe(response => {
                              this.sectionType = response.json();
                              if (localStorage.getItem('sectionDropdownId') != null && localStorage.getItem('sectionDropdownId') != '0') {
                                this.sectionType.forEach(termElement => {
                                  if (termElement['id'] == localStorage.getItem('sectionDropdownId')) {
                                    this.secID = termElement['id'];
                                    let postData = {
                                      'userId': userId,
                                      'crsDrop': this.crsId,
                                      'secDrop': this.secID
                                    };
                                    this.service.subUrl = 'configuration/Master/getTopics';
                                    this.service.createPost(postData).subscribe(response => {
                                      this.topicType = response.json();
                                      if (localStorage.getItem('topicDropdownId') != null && localStorage.getItem('topicDropdownId') != '0') {
                                        this.topicType.forEach(termElement => {
                                          if (termElement['topic_id'] == localStorage.getItem('topicDropdownId')) {
                                            this.topID = termElement['topic_id'];
                                            this.gettabledata();
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
        });
      }

    });
    // this.getDeliveryMethod(this.crclmId);
    // this.getBloomType(this.crsId);
    this.mybloom = {
      defaultTitle: 'Select Blooms Level'
    };

    this.mydlvrmethod = {
      defaultTitle: 'Select Delivery Methods'
    };


    this.isSaveHide = false;
    this.isUpdateHide = true;
    
    this.myDeliveryMethod={
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Delivery Method',   
      allSelected: 'All selected',
    }

    this.myBlooms={
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: "Select Bloom's Level",
      allSelected: 'All selected',
    }
  }
  // validation for form
  private lessonForm = new FormGroup({
    lesson: new FormControl('', [
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ]),
    deliveryMethod: new FormControl('', [
      //  Validators.required,
    ]),
    bloom: new FormControl('', [
      //Validators.required
    ]),
    actualStartDate: new FormControl('', [
      // Validators.required

    ]),
    completionDate: new FormControl('', [
      // Validators.required
    ]),
    portion: new FormControl('', [
      Validators.required
    ])
  });
  get lesson() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.lessonForm.get('lesson');
  }
  get deliveryMethod() {
    //   /* property to access the 
    //   formGroup Controles. which is used to validate the form */
    return this.lessonForm.get('deliveryMethod');
  }
  get bloom() {
    //   /* property to access the 
    //   formGroup Controles. which is used to validate the form */
    return this.lessonForm.get('bloom');
  }
  get actualStartDate() {
    //   /* property to access the 
    //   formGroup Controles. which is used to validate the form */
    return this.lessonForm.get('actualStartDate');
  }
  get completionDate() {
    //   /* property to access the 
    //   formGroup Controles. which is used to validate the form */
    return this.lessonForm.get('completionDate');
  }
  get portion() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.lessonForm.get('portion');
  }

  // function load derivery method dropdown values
  getDeliveryMethod(event) {
    let postData = {
      'crclm_id': event
    }
    this.service.subUrl = 'lesson_schedule/LessonSchedule/getdeliveryMethodDrop';
    this.service.createPost(postData).subscribe(response => {
      this.DeliverMethodType = response.json();
    });
  }
  // function load bloom level dropdown values
  getBloomType(event) {
    let postData = {
      'courseValue': event
    }
    this.service.subUrl = 'lesson_schedule/LessonSchedule/getBloomDropdown';
    this.service.createPost(postData).subscribe(response => {
      this.bloomType = response.json();
    });

  }
  // function to load term dropdown
  term(event) {
    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('termDropdownId');
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');
    localStorage.removeItem('topicDropdownId')
    this.termType = [];
    this.courseType = [];
    this.sectionType = [];
    this.topicType = [];
    this.termId = 0;
    this.crsId = 0;
    this.secID = 0;
    this.topID = 0;

    this.curriculumType.forEach(curriculumElement => {
      if (curriculumElement['crclm_id'] == event) {
        localStorage.setItem('currDropdown', curriculumElement['crclm_name']);
        localStorage.setItem('currDropdownId', curriculumElement['crclm_id']);
      }

    });
    var userId = localStorage.getItem('id');
    let postData = {
      'userId': userId,
      'curDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getTerm';
    this.service.createPost(postData).subscribe(response => {
      this.termType = response.json();
    });
  }
  // function to load course dropdown
  course(event) {
    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');
    localStorage.removeItem('topicDropdownId')
    this.courseType = [];
    this.sectionType = [];
    this.topicType = [];
    this.crsId = 0;
    this.secID = 0;
    this.topID = 0;

    this.termType.forEach(termElement => {
      if (termElement['crclm_term_id'] == event) {
        localStorage.setItem('termDropdown', termElement['term_name']);
        localStorage.setItem('termDropdownId', termElement['crclm_term_id']);
      }
    });
    var userId = localStorage.getItem('id');
    let postData = {
      'userId': userId,
      'termDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getCourse';
    this.service.createPost(postData).subscribe(response => {
      this.courseType = response.json();
    });
  }
  section(event) {
    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('sectionDropdownId');
    localStorage.removeItem('topicDropdownId')
    localStorage.removeItem('')
    this.sectionType = [];
    this.topicType = [];
    this.secID = 0;
    this.topID = 0;
    this.crsvalue = event;


    this.courseType.forEach(courseElement => {
      if (courseElement['crs_id'] == event) {
        localStorage.setItem('courseDropdown', courseElement['crs_title']);
        // localStorage.setItem('courseCodeDropdown', courseElement['crs_code']);
        localStorage.setItem('courseDropdownId', courseElement['crs_id']);
      }
    });
    var userId = localStorage.getItem('id');
    let postData = {
      'userId': userId,
      'crsDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getSection';
    this.service.createPost(postData).subscribe(response => {
      this.sectionType = response.json();
    });
  }
  topics(event) {
    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('topicDropdownId')
    this.topicType = [];
    this.topID = 0;

    this.sectionType.forEach(sectionElement => {
      if (sectionElement['id'] == event) {
        localStorage.setItem('sectionDropdown', sectionElement['name']);
        // localStorage.setItem('courseCodeDropdown', courseElement['crs_code']);
        localStorage.setItem('sectionDropdownId', sectionElement['id']);
      }

    });
    var userId = localStorage.getItem('id');
    if(!this.crsvalue){
      this.crsvalue = localStorage.getItem('courseDropdownId');
    }

    let postData = {
      'userId': userId,
      'crsDrop': this.crsvalue,
      'secDrop': event
    };
    this.service.subUrl = 'configuration/Master/getTopics';
    this.service.createPost(postData).subscribe(response => {
      this.topicType = response.json();
      localStorage.setItem('topicType', JSON.stringify(this.topicType));
    });

  }

  checkDate(initialdate) {

    let initialDate = initialdate.split('-');
    let year = initialDate[0];
    let month = initialDate[1];
    let day = initialDate[2];
    this.myDatePickerOptions1.disableUntil.day = day - 1;
    this.myDatePickerOptions1.disableUntil.month = month;
    this.myDatePickerOptions1.disableUntil.year = year;

  }


  //function to disable the date untill selected start date
  onDateChanged(event: IMyDateModel) {

    //console.log('onDateChanged(): ', event.date, ' - jsdate: ', new Date(event.jsdate).toLocaleDateString(), ' - formatted: ', event.formatted, ' - epoc timestamp: ', event.epoc);
    //let startDate = this.lessonForm.controls['actualStartDate'].value;
    if (event.formatted == "") {
      this.myDatePickerOptions1.disableUntil.day = 0;
      this.myDatePickerOptions1.disableUntil.month = 0;
      this.myDatePickerOptions1.disableUntil.year = 0;
      // this.selDate = event.date;
    } else {
      //  if(event == )
      this.myDatePickerOptions1.disableUntil.day = event.date.day - 1;
      this.myDatePickerOptions1.disableUntil.month = event.date.month;
      this.myDatePickerOptions1.disableUntil.year = event.date.year;
      this.selDate = event.date;
    }
    // event properties are: event.date, event.jsdate, event.formatted and event.epoc
  }

  // function to get lesson schedule list
  gettabledata() {
    this.topicType.forEach(topicElement => {
      if (topicElement['topic_id'] == this.topID) {
        localStorage.setItem('topicDropdown', topicElement['topic_title']);
        localStorage.setItem('topicDropdownId', topicElement['topic_id']);
      }
    });

    var inId = this.instructorID;
    let postData = {
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': inId,
    };

    this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
    this.service.createPost(postData).subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });

    this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
    this.service.createPost(postData).subscribe(response => {

      this.slno = response.json();
      this.lesson.setValue(this.slno);
    });

  }
  // function to change less schedule status
  inprogress(editElement: HTMLElement) {
    let lessonscheduleId = editElement.getAttribute('lessonscheduleId');
    this.service.subUrl = 'lesson_schedule/LessonSchedule/inprogress';
    // var curriculumValue = localStorage.getItem('currDropdownId');
    // var termValue = localStorage.getItem('termDropdownId');
    // var courseValue = localStorage.getItem('courseDropdownId');
    // var topicValue = localStorage.getItem('topicDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');

    // let postData = {
    //   // 'pgmDrop': programValue,
    //   'curclmDrop': curriculumValue,
    //   'termDrop': termValue,
    //   'courseDrop': courseValue,
    //   'topicDrop': topicValue,
    //   'secDrop': sectionValue,
    //   'id': this.instructorID
    //   // 'crclmName' : curriculumName
    // };
    let postData = {
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': this.instructorID,
    };
    this.service.createPost(lessonscheduleId).subscribe(response => {
      if (response.json().status == 'ok') {

        this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });
        let type = 'success';
        let title = 'Intiated';
        let body = 'Topic content has been intiated'
        this.toasterMsg(type, title, body);
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Topic content has not intiated'
        this.toasterMsg(type, title, body);
      }
    });

  }
  // function to change less schedule status
  complete(editElement: HTMLElement) {
    let lessonscheduleId = editElement.getAttribute('lessonscheduleId');
    this.service.subUrl = 'lesson_schedule/LessonSchedule/complete';
    // var curriculumValue = localStorage.getItem('currDropdownId');
    // var termValue = localStorage.getItem('termDropdownId');
    // var courseValue = localStorage.getItem('courseDropdownId');
    // var topicValue = localStorage.getItem('topicDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');

    // let postData = {
    //   // 'pgmDrop': programValue,
    //   'curclmDrop': curriculumValue,
    //   'termDrop': termValue,
    //   'courseDrop': courseValue,
    //   'topicDrop': topicValue,
    //   'secDrop': sectionValue,
    //   'id': addinsId
    //   // 'crclmName' : curriculumName
    // };
    // var addinsId = this.instructorID;

    let postData = {
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': this.instructorID,
    };
    this.service.createPost(lessonscheduleId).subscribe(response => {
      if (response.json().status == 'ok') {

        this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          // alert(JSON.stringify(this.posts))
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });
        let type = 'success';
        let title = 'Delivered';
        let body = 'Topic content has been deliverd'
        this.toasterMsg(type, title, body);
      } else {
        let type = 'error';
        let title = 'Delivery Fail';
        let body = 'Topic content has not deliverd'
        this.toasterMsg(type, title, body);
      }
    });

  }
  //function to get reopen modal
  reopenwarning(reopenElement: HTMLElement, modalEle: HTMLDivElement) {
    let reId = reopenElement.getAttribute('lessonscheduleId');
    let reopenId;
    this.reopenId = reId;
    (<any>jQuery('#Reopen')).modal('show');

  }
  // function to change less schedule status
  reopen(reopenForm, lessscheIdInput: HTMLInputElement) {
    // var r_curriculumValue = localStorage.getItem('currDropdownId');
    // var r_termValue = localStorage.getItem('termDropdownId');
    // var r_courseValue = localStorage.getItem('courseDropdownId');
    // var r_topicValue = localStorage.getItem('topicDropdownId');
    // var r_sectionValue = localStorage.getItem('sectionDropdownId');
    // var postData = {
    //   'reopen': reopenForm.value,
    //   'lssid': lessscheIdInput.value,
    //   'curclmDrop': r_curriculumValue,
    //   'termDrop': r_termValue,
    //   'courseDrop': r_courseValue,
    //   'topicDrop': r_topicValue,
    //   'secDrop': r_sectionValue,
    //   'id': this.instructorID
    // }
    let postData = {
      'reopen': reopenForm.value,
      'lssid': lessscheIdInput.value,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': this.instructorID,
    };

    // var postdata = reopenForm.value;

    this.service.subUrl = 'lesson_schedule/LessonSchedule/reopen';
    this.service.createPost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          // alert(JSON.stringify(this.posts));
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });
      }
    });

  }
  // function to add new portion to lesson
  createPost(lessonForm) {
    let slnoData = {
      'slno': lessonForm.value.lesson,
      'id': this.instructorID,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
    }
    this.service.subUrl = 'lesson_schedule/LessonSchedule/checkduplicateserialnumber'
    this.service.createPost(slnoData).subscribe(response => {
      if (response.json().status == 'ok') {

    this.service.subUrl = 'lesson_schedule/LessonSchedule/createLessonSchedule';
    let lessonData = lessonForm.value; // Text Field/Form Data in Json Format
    // var less_curriculumValue = localStorage.getItem('currDropdownId');
    // var less_termValue = localStorage.getItem('termDropdownId');
    // var less_courseValue = localStorage.getItem('courseDropdownId');
    // var less_topicValue = localStorage.getItem('topicDropdownId');
    // var less_sectionValue = localStorage.getItem('sectionDropdownId');
    if (this.crclmId && this.termId && this.crsId && this.topID && this.secID) {

      // var insid = this.instructorID;

      // let postData = {
      //   // 'pgmDrop': programValue,

      //   // 'pgmDrop': programValue,
      //   'curclmDrop': less_curriculumValue,
      //   'termDrop': less_termValue,
      //   'courseDrop': less_courseValue,
      //   'topicDrop': this.topicValue,
      //   'secDrop': less_sectionValue,

      //   'id': insid
      //   // 'crclmName' : curriculumName
      // };
      let postData = {
        'curclmDrop': this.crclmId,
        'termDrop': this.termId,
        'courseDrop': this.crsId,
        'topicDrop': this.topID,
        'secDrop': this.secID,
        'lessondata': lessonData,
        'id': this.instructorID,
      };

      this.service.createPost(postData).subscribe(response => {
        // this.posts = response.json();
        // this.tableRerender();
        // this.dtTrigger.next();// Calling the DT trigger to manually render the table
        if (response.json().status == 'ok') {

          this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
          this.service.createPost(postData).subscribe(response => {
            this.posts = response.json();
            // alert(JSON.stringify(this.posts));
            this.tableRerender();
            this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
          });

          let type = 'success';
          let title = 'Add Success';
          let body = 'New lesson portion added successfully.'
          this.toasterMsg(type, title, body);
          this.lessonForm.reset();
          this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
          this.service.createPost(postData).subscribe(response => {

            this.slno = response.json();
            this.lesson.setValue(this.slno);
          });

        } else {
          let type = 'error';
          let title = 'Add Fail';
          let body = 'New lesson portion add failed please try again.'
          this.toasterMsg(type, title, body);
          this.lessonForm.reset();
          this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
          this.service.createPost(postData).subscribe(response => {

            this.slno = response.json();
            this.lesson.setValue(this.slno);
          });

        }
      });
      // this.flag = 0;
    }
    else {
      let type = 'error';
      let title = 'Add Fail';
      let body = 'Please select all dropdown values before you add'
      this.toasterMsg(type, title, body);
      // this.lessonForm.reset();
    }
      }
      else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Duplicate values are not allowed to serial number.Please give other serial number'
        this.toasterMsg(type, title, body);
      }
    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let portionStatus = deleteElement.getAttribute('lessstatus');
    if (portionStatus == '1') {
      let lessonschId = deleteElement.getAttribute('lessonschId');
      let delLessonId;
      this.delLessonId = lessonschId;
      (<any>jQuery('#LessonScheduleDeleteModal')).modal('show');
    }
    else {
      if (portionStatus == '0') {
        let lessonschId = deleteElement.getAttribute('lessonschId');
        this.service.subUrl = 'lesson_schedule/LessonSchedule/checkTopiclessFlag';
        this.service.createPost(lessonschId).subscribe(response => {
          //  this.tableRerender();
          if (response.json().status == 'ok') {
            let delLessonId;
            this.delLessonId = lessonschId;
            (<any>jQuery('#LessonScheduleDeleteModal')).modal('show');
          }
          else {
            let type = 'error';
            let title = 'Delete Fail';
            let body = 'You can not delete'
            this.toasterMsg(type, title, body);
          }
        });
      }
      else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Since lesson portion is completed. You can not delete'
        this.toasterMsg(type, title, body);
      }
    }
  }
  // delete lesson schedule
  deleteLessonScheduleData(lessonschIdInput: HTMLInputElement) {
    this.service.subUrl = 'lesson_schedule/LessonSchedule/deleteLesson';
    // var less_curriculumValue = localStorage.getItem('currDropdownId');
    // var less_termValue = localStorage.getItem('termDropdownId');
    // var less_courseValue = localStorage.getItem('courseDropdownId');
    // var less_topicValue = localStorage.getItem('topicDropdownId');
    // var less_sectionValue = localStorage.getItem('sectionDropdownId');
    // var delInsId = this.instructorID;
    // let postData = {
    //   // 'pgmDrop': programValue,
    //   'lessonschId': lessonschIdInput.value,
    //   'curclmDrop': less_curriculumValue,
    //   'termDrop': less_termValue,
    //   'courseDrop': less_courseValue,
    //   'topicDrop': less_topicValue,
    //   'secDrop': less_sectionValue,
    //   'id': delInsId

    //   // 'crclmName' : curriculumName
    // };
    let postData = {
      'lessonschId': lessonschIdInput.value,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': this.instructorID,
    };
    // alert(JSON.stringify(postData));
    this.service.deletePost(postData).subscribe(response => {
      //  this.tableRerender();
      if (response.json().status == 'ok') {
        this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Lesson Schedule - portion to be covered deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#LessonScheduleDeleteModal')).modal('hide');
        // this.lessonForm.reset();
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Lesson Schedule - portion to be covered delete failed please try again'
        this.toasterMsg(type, title, body);
        // this.lessonForm.reset();
      }
      this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
      this.service.createPost(postData).subscribe(response => {

        this.slno = response.json();
        this.lesson.setValue(this.slno);
      });
      // this.ngOnInit();
      //  this.posts = response.json();
      //  this.dtTrigger.next(); // Calling the DT trigger to manually render the table
    });
    console.log(this.posts);
  }


  //to get the row data onclick of edit 
  editlesson(editElement: HTMLElement) {
    let year;
    let month;
    let day;
    // $('#lesson').removeAttr("disabled");
    let status = editElement.getAttribute('lessschedulestatus');
    if (status == '1') {
      this.title1 = "Edit Portion to be Covered";
      this.bloom_update.length = 0;
      this.mtd_id_update.length = 0;

      let lessonscheduleId = editElement.getAttribute('lessonscheduleId');

      let lessonscheduleStartdate = editElement.getAttribute('lessonscheduleStartdate');
      if (lessonscheduleStartdate == "") {

      } else {
        year = lessonscheduleStartdate.substring(0, 4);
        month = lessonscheduleStartdate.substring(5, 7);
        day = lessonscheduleStartdate.substring(8, 10);
        let initial_day = day.replace(/^0+/, '');
        let initial_month = month.replace(/^0+/, '');
        this.model = { date: { year: year, month: initial_month, day: initial_day } };
      }
      let lessonschedulecompletiondate = editElement.getAttribute('lessonschedulecompletiondate');

      if (lessonschedulecompletiondate == "") {
        this.completionDate.reset();
        // let formatted_date = year+'-'+month+'-'+day;

        // this.checkDate(formatted_date)
        // this.model1=null

      } else {

        let year1 = lessonschedulecompletiondate.substring(0, 4);
        let month1 = lessonschedulecompletiondate.substring(5, 7);
        let day1 = lessonschedulecompletiondate.substring(8, 10);
        let due_day = day1.replace(/^0+/, '');
        let due_month = month1.replace(/^0+/, '');
        this.model1 = { date: { year: year1, month: due_month, day: due_day } };
      }
      this.service.subUrl = 'lesson_schedule/LessonSchedule/geteditlessonSchedule';
      this.service.createPost(lessonscheduleId).subscribe(response => {
        this.lessonScheduleEditData = response.json();
        this.lessonScheduleEditData.forEach(element => {
          this.setLessonScheduleId = element.lsn_sch_id;
          let slno = element.slno;
          let portion_per_hour = element.portion_per_hour;
          this.status = element.status;
          if (element.bloom != undefined) {
            element.bloom.forEach(data => {
              if (this.bloom_update.indexOf(data) == -1) {
                this.bloom_update.push(data);
              }
            })
          }
          if (element.method != undefined) {
            element.method.forEach(data1 => {
              if (this.mtd_id_update.indexOf(data1) == -1) {
                this.mtd_id_update.push(data1);
              }
            })
          }
          this.lesson.setValue(slno);
          this.portion.setValue(portion_per_hour);
          this.actualStartDate.setValue(this.model);
          this.completionDate.setValue(this.model1);
          this.deliveryMethod.setValue(this.mtd_id_update);
          this.bloom.setValue(this.bloom_update);
        });

      });

      this.isSaveHide = true;
      this.isUpdateHide = false;
    }
    else {
      if (status == '0') {
        // $('#lesson').attr("disabled", "disabled");
        let type = 'error';
        let title = 'Edit Fail';
        let body = 'Lesson Schedule- Portion to be covered is not initialised, so in order to mention mapping, start date and end date please initiate portion to be covered by clicking on Not Initiated button'
        this.toasterMsg(type, title, body);
      }
      else {
        // $('#lesson').attr("disabled", "disabled");
        let type = 'error';
        let title = 'Edit Fail';
        let body = 'Since lesson portion is completed. You can not edit'
        this.toasterMsg(type, title, body);
      }
    }
  }


  // Update Lesson Schedule
  updatePost(updatePost) {
    updatePost.stringify
    let slnoData = {
      'slno': updatePost.value.lesson,
      'id': this.instructorID,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'lsn_id': this.setLessonScheduleId,
    }
    var id = this.setLessonScheduleId;
    var state = this.status;
    let lessonData = updatePost.value;
    var crclm_update = localStorage.getItem('currDropdownId');
    var termValue_update = localStorage.getItem('termDropdownId');
    var course_update = localStorage.getItem('courseDropdownId');
    var topicValue_update = localStorage.getItem('topicDropdownId');
    var sectionValue_update = localStorage.getItem('sectionDropdownId');
    let postData = {
      'lesson': updatePost.value.lesson,
      'actualStartDate': updatePost.value.actualStartDate,
      'completionDate': updatePost.value.completionDate,
      'portion': updatePost.value.portion,
      'deliveryMethod': updatePost.value.deliveryMethod,
      'bloom': updatePost.value.bloom,
      'lsn_id': id,
      'lessondata': lessonData,
      'statusdata': state,
      'id': this.instructorID,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,

    };
  
    this.service.subUrl = 'lesson_schedule/LessonSchedule/checkEditDuplicateSerialNum'
    this.service.createPost(slnoData).subscribe(response => {
      if (response.json().status == 'ok') {


        this.service.subUrl = 'lesson_schedule/LessonSchedule/updateLesson';
    this.service.updatePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'lesson_schedule/LessonSchedule/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });

        let type = 'success';
        let title = 'Update Success';
        let body = 'Lesson schedule updated successfully'
        this.toasterMsg(type, title, body);
        this.lessonForm.reset();
        $('#portion').removeAttr("disabled");
        this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
        this.service.createPost(postData).subscribe(response => {
          this.slno = response.json();
          this.lesson.setValue(this.slno);
        });

      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Lesson schedule failed please try again'
        this.toasterMsg(type, title, body);
        this.lessonForm.reset();

      }

        });
      }
      else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Duplicate values are not allowed to serial number.Please give other serial number'
        this.toasterMsg(type, title, body);

      }
    });
    // window.scrollTo(0, 0);
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  // to reset form
  cancelUpdate() {
    let postData = {
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': this.instructorID,
    };

    this.title1 = "Add Portion to be Covered";
    $('#portion').removeAttr("disabled");
    // $('#lesson').attr("disabled", "disabled");
    this.lessonForm.reset();
    this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
    this.service.createPost(postData).subscribe(response => {
      this.slno = response.json();
      this.lesson.setValue(this.slno);
    });
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  cancelReset() {
    let postData = {
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'topicDrop': this.topID,
      'secDrop': this.secID,
      'id': this.instructorID,
    };
    // $('#portion').removeAttr("disabled");
    this.lessonForm.reset();
    this.service.subUrl = 'lesson_schedule/LessonSchedule/slno';
    this.service.createPost(postData).subscribe(response => {
      this.slno = response.json();
      this.lesson.setValue(this.slno);
    });
  }


  // to disable and enable portion covered
  edit(id, status) {
    // alert(id);
    if (status == '1') {
      this.service.subUrl = 'lesson_schedule/LessonSchedule/geteditlessonSchedule1';
      var less_id = id;
      let postData = {

        'lesson': less_id
      };
      this.service.createPost(postData).subscribe(response => {
        this.lessonScheduleEditstatus = response.json();
        if (response.json().status == 'ok') {
          // this.posts.forEach(id => {
          $('#portion').attr("disabled", "disabled");
          // })
          // let type = 'error';
          // let title = '';
          // let body = 'Cannot Change Course Instructor.Course Instructor Already Assigned!'
          // this.toasterMsg(type, title, body);
        } else {
          $('#portion').removeAttr("disabled");

          // $('#portion').removeAttr("disabled", "disabled");
          // let type = 'error';
          // let title = 'Add Fail';
          // let body = 'Course Instructor Already Assigned!'
          // this.toasterMsg(type, title, body);
        }
        // $('#portion').removeAttr("disabled", "disabled");
      });
    }
    // this.ngOnInit();
  }


  start() {
    this.myDatePickerOptions.disableUntil.day = 0;
    this.myDatePickerOptions.disableUntil.month = 0;
    this.myDatePickerOptions.disableUntil.year = 0;
  }

  tableRerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
    });
  }
  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  getTitle(state, parent) {
    var data = [];
    if (parent && parent.snapshot.data && parent.snapshot.data.title) {
      data.push(parent.snapshot.data.title);
    }

    if (state && parent) {
      data.push(... this.getTitle(state, state.firstChild(parent)));
    }
    return data;
  }

  // to get success msg on particular add,edit,delete functionality
  toasterMsg(type, title, body) {
    this.toast.toastType = type;
    this.toast.toastTitle = title;
    this.toast.toastBody = body;
    this.tosterconfig = new ToasterConfig({
      positionClass: 'toast-bottom-right',
      tapToDismiss: false,
      showCloseButton: true,
      animation: 'slideDown'
    });
    this.toast.toastMsg;
  }
}
