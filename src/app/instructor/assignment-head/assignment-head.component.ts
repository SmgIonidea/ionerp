//import { DropdownComponent } from './../../dropdown/dropdown.component';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { Component, OnInit, Input, ViewChild, AfterViewInit } from '@angular/core';
import { Injectable } from '@angular/core';
import { PostService } from '../../services/post.service';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { FormsModule } from '@angular/forms';
import { ContentWrapperComponent } from '../../content-wrapper/content-wrapper.component';
import { CharctersOnlyValidation } from '../../custom.validators';
import { ActivatedRoute, Params, Event } from "@angular/router";
import { Title } from '@angular/platform-browser';
import { customDateFormatPipe } from '../../services/date-format.pipe';
import { RouterModule } from '@angular/router';
import * as $ from 'jquery';
import { forEach } from '@angular/router/src/utils/collection';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';


@Component({
  selector: 'app-assignment-head',
  templateUrl: './assignment-head.component.html',
  styleUrls: ['./assignment-head.component.css']
})
@Injectable()
export class AssignmentHeadComponent implements OnInit, AfterViewInit {
  private selDate: IMyDate = { year: 0, month: 0, day: 0 };

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


  /* Constructor */
  constructor(private service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    public titleService: Title) {
    let d: Date = new Date();
    this.selDate = {
      year: d.getFullYear(),
      month: d.getMonth() + 1,
      day: d.getDate()
    };
  }

  /* Global Variable Declarations */
  posts: Array<any> = [];
  programType: Array<string>;
  curriculumType: Array<string> = [];
  termType: Array<string> = [];
  courseType: Array<string> = [];
  // sectionType: IMultiSelectOption[];
  sectionType: Array<string> = [];
  pgmId: any = 0;
  crclmId: any = 0;
  termId: any = 0;
  crsId: any = 0;
  sectionId: any = 0;
  user_id = localStorage.getItem('id');
  manage_id;

  assignment_id;
  aId: Array<any>;;
  deleteAllId;
  studentId = [];
  disabledChecked = [];
  stu_usn = [];
  student_progress: Array<any>;
  stunot_progress: Array<any>;
  stu_name: Array<any>;
  checked: Array<any> = [];
  usn = [];
  private sub: any;
  assignmentName;
  deleteAllAssignId: Array<any>;
  id;
  stu_count;
  totalStu_count;
  tosterconfig;
  percentage: number;
  title: string;
  courseT: any;
  // posts = [];
  assignedStu_list;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  flag = 0;
  // curriculumName;
  // termName;
  // courseName;
  courseCode;
  programName: Array<any>;
  curriculumName: Array<any>;
  termName: Array<any>;
  courseName: Array<any>;
  sectionName: Array<any>;
  disabledIds: Array<any>;
  secName;
  setAssignmenttId: any; // assignment id used to update the details
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();
  public currentPageVal;
  @Input('assignmentId') delAssignmentId; // Input binding used to place assignment id in hidden text box to delete the assignment. this is one more way of input binding.
  status: Array<any> = [];
  subResult: Array<any> = [];
  maintitle;
  // marksData;


  ngOnInit() {
    $("#delete_head").attr("style", "visibility: hidden")
    this.currentPageVal = 'assignmentHead';
    this.flag = 0;
    //default sort according to slno
    this.dtOptions = {
      "order": [[1, 'asc']]
    };
    this.maintitle = "Assignment List";
    this.title = "Add Assignment Head";
    this.titleService.setTitle('AssignmentList | IONCUDOS');

    var userId = localStorage.getItem('id');
    this.service.subUrl = 'configuration/Master/getProgram';
    this.service.createPost(userId).subscribe(response => {
      this.programType = response.json();
      if (localStorage.getItem('programDropdownId') != null && localStorage.getItem('programDropdownId') != '0') {
        this.programType.forEach(termElement => {
          if (termElement['pgm_id'] == localStorage.getItem('programDropdownId')) {
            this.pgmId = termElement['pgm_id'];
            let postData = {
              'userId': userId,
              'pgmDrop': this.pgmId,
            };
            this.service.subUrl = 'configuration/Master/getCurriculum';
            this.service.createPost(postData).subscribe(response => {
              this.curriculumType = response.json();
              if (localStorage.getItem('currDropdownId') != null && localStorage.getItem('currDropdownId') != '0') {
                this.curriculumType.forEach(termElement => {
                  if (termElement['crclm_id'] == localStorage.getItem('currDropdownId')) {
                    this.crclmId = termElement['crclm_id'];
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
                                            this.sectionId = termElement['id'];
                                            this.getdropdowndata();
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


  }
  curriculum(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('currDropdownId');
    localStorage.removeItem('termDropdownId');
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');
    this.curriculumType = [];
    this.termType = [];
    this.courseType = [];
    this.sectionType = [];
    this.crclmId = 0;
    this.termId = 0;
    this.crsId = 0;
    this.sectionId = 0;

    this.programType.forEach(programElement => {
      if (programElement['pgm_id'] == event) {
        localStorage.setItem('programDropdown', programElement['pgm_acronym']);
        localStorage.setItem('programDropdownId', programElement['pgm_id']);
      }
    });
    var userId = localStorage.getItem('id');
    let postData = {
      'userId': userId,
      'pgmDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getCurriculum';
    this.service.createPost(postData).subscribe(response => {
      this.curriculumType = response.json();
      // alert(JSON.stringify(this.curriculumType));
    });
  }

  //method to load term dropdown data
  term(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
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

  //method to load course dropdown data
  course(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
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

  //method to load section dropdown data
  section(event) {
    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('sectionDropdownId');
    this.sectionType = [];
    this.sectionId = 0;

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

  getdropdowndata() {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();

    this.sectionType.forEach(sectionElement => {
      if (sectionElement['id'] == this.sectionId) {
        localStorage.setItem('sectionDropdown', sectionElement['name']);
        localStorage.setItem('sectionDropdownId', sectionElement['id']);
      }
    });

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'user_id': this.user_id,
    };

    //service to get Program Name
    this.service.subUrl = 'assignment/assignment/getProgramName';
    this.service.createPost(this.pgmId).subscribe(response => {
      this.programName = response.json();

    });
    //service to get Curriculum Name
    this.service.subUrl = 'assignment/assignment/getCurriculumName';
    this.service.createPost(this.crclmId).subscribe(response => {
      this.curriculumName = response.json();

    });
    //service to get Term Name
    this.service.subUrl = 'assignment/assignment/getTermName';
    this.service.createPost(this.termId).subscribe(response => {
      this.termName = response.json();

    });
    //service to get Course Name
    this.service.subUrl = 'assignment/assignment/getCourseName';
    this.service.createPost(this.crsId).subscribe(response => {
      this.courseName = response.json();

    });
    //service to get Section Name
    this.service.subUrl = 'assignment/assignment/getSectionName';
    this.service.createPost(this.sectionId).subscribe(response => {
      this.sectionName = response.json();

    });

    this.sub = this.activatedRoute
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.service.subUrl = 'assignment/assignment/getAssignDetails';
        this.service.createPost(this.id).subscribe(response => {
          this.assignmentName = response.json();
        });
      });

    this.service.subUrl = 'assignment/assignment/index';
    this.service.createPost(postData).subscribe(response => {
      this.posts = response.json();

      this.assignment_id = JSON.parse(JSON.stringify(this.posts));
      this.assignment_id.assignmentList.forEach(element => {

        this.status.push(element.a_id);
        // this.disableStudents(element.a_id);
        this.disablesubmittedassignment();
        this.tableRerender();
        this.dtTrigger.next(); // Calling the DT trigger to manually render the table    
      })
    });
    this.isSaveHide = false;
    this.isUpdateHide = true;
    // to fetch students
    // this.sub = this.activatedRoute
    //   .queryParams
    //   .subscribe(params => {
    //     // Defaults to 0 if no query param provided.
    //     this.id = +params['id'] || 0;
    //     this.service.subUrl = 'assignment/assignment/getStudents';
    //     this.service.createPost(this.id).subscribe(response => {
    //       this.stu_name = response.json();
    //       this.disableStudents();
    //       // this.tableRerender();
    //       // this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    //     });
    //   });
  }

  getStudents(manageStudents: HTMLElement) {
   
    this.manage_id = manageStudents.getAttribute('assignmentId');
    // alert( this.manage_id)
    this.service.subUrl = 'assignment/assignment/getAssignDetails';
        this.service.createPost(this.manage_id).subscribe(response => {
          this.assignmentName = response.json();
          // alert(JSON.stringify(this.assignmentName))
        });

    this.service.subUrl = 'assignment/assignment/getStudents';
    this.service.createPost(this.manage_id).subscribe(response => {
      this.stu_name = response.json();
      this.disableStudents();
      this.stuForm.reset();
      // this.uncheckAll();
      // this.tableRerender();
      // this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });
  }

  disablesubmittedassignment() {
    var post = { 'id_array': this.status }
    this.service.subUrl = 'assignment/assignment/getsubmissionStatus';
    this.service.createPost(post).subscribe(response => {
      this.subResult = response.json();
      this.subResult.forEach(sub => {
        if (sub.status_flag == 2 || sub.status_flag == 4 || sub.status_flag == 3) {
          $("#" + sub.asgt_id).prop('disabled', true);
          // $("#" + sub.asgt_id).prop('checked', true);
          // $("#delete_head").prop('disabled', true);
          $("#delete_head").attr("style", "visibility: hidden")
        }
      })
    });
  }

  //to get % of students in View Progress Module
  getStudentProgressPercentage(aid) {

    //to fetch assigned student list 
    this.service.subUrl = 'assignment/assignment/assignedStudentList';
    this.service.createPost(aid).subscribe(response => {
      this.assignedStu_list = response.json();
      this.totalStu_count = this.assignedStu_list.length;
      // this.tableRerender();
      // this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
      //submitted student list
      this.service.subUrl = 'assignment/assignment/getProgress';
      this.service.createPost(aid).subscribe(response => {
        this.student_progress = response.json();
        this.stu_count = this.student_progress.length;

        //not submitted student list
        this.service.subUrl = 'assignment/assignment/getProgressNotSubmitted';
        this.service.createPost(this.id).subscribe(response => {
          this.stunot_progress = response.json();
        });
        //to get % of no of  students submiited out of no of students assigned
        this.percentage = (this.stu_count / this.totalStu_count) * 100;
        // if percentage is Nan , return 0 else return percentage
        if (isNaN(this.percentage)) {
          return this.percentage = 0;
        } else {
          return this.percentage;
        }
      });
    });

  }

 

  //to disable students after assigning them to particular assignment
  disableStudents() {
    // this.manage_id = manageStudents.getAttribute('assignmentId');
       // this.sub = this.activatedRoute
    //   .queryParams
    //   .subscribe(params => {
    // Defaults to 0 if no query param provided.
    // this.id = +params['id'] || 0;
    this.disabledChecked.length = 0;
    this.service.subUrl = 'assignment/assignment/StudentdisableIds';
    this.service.createPost( this.manage_id).subscribe(response => {
      this.disabledChecked = response.json();
      this.disabledChecked.forEach(element => {
        $('#' + element.student_id).prop('checked', true);
        $('#' + element.student_id).prop('disabled', true);
        $('#submit_stu').prop('disabled', true)
      })
      // this.tableRerender();
      // this.dtTrigger.next(); // Calling the DT trigger to manually render the table                        
    });
    // });
  }


  // validation for assignment form
  private assignmentForm = new FormGroup({
    assignment: new FormControl('', [
      Validators.required
    ]),
    myinitialdate: new FormControl('', [
      Validators.required,

    ]),
    myenddate: new FormControl('', [
      Validators.required
    ]),
    totalmarks: new FormControl('', [
      CharctersOnlyValidation.DigitswithDecimals
    ]),
    instructions: new FormControl('', [
      // Validators.required
    ])
  });

  get assignment() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.assignmentForm.get('assignment');
  }
  get myinitialdate() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.assignmentForm.get('myinitialdate');
  }
  get myenddate() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.assignmentForm.get('myenddate');
  }
  get totalmarks() {
    return this.assignmentForm.get('totalmarks');
  }
  get instructions() {
    return this.assignmentForm.get('instructions');
  }

  // validation for student form
  private stuForm = new FormGroup({
    stu_check: new FormControl('', []),
  });

  get stu_check() {
    return this.assignmentForm.get('stu_check');
  }
  start() {

    this.myDatePickerOptions.disableUntil.day = 0;
    this.myDatePickerOptions.disableUntil.month = 0;
    this.myDatePickerOptions.disableUntil.year = 0;
  }
  // to create assignment
  createPost(assignmentForm) {

    this.service.subUrl = 'assignment/Assignment/createAssignment';
    let assignmentData = assignmentForm.value; // Text Field/Form Data in Json Format

    if (this.pgmId && this.crclmId && this.termId && this.crsId && this.sectionId) {


      let postData = {
        'pgmDrop': this.pgmId,
        'curclmDrop': this.crclmId,
        'termDrop': this.termId,
        'courseDrop': this.crsId,
        'secDrop': this.sectionId,
        'assignmentData': assignmentData,
        'user_id': this.user_id,
      };
      this.service.createPost(postData).subscribe(response => {

        if (response.json().status == 'ok') {

          this.service.subUrl = 'assignment/assignment/index';
          this.service.createPost(postData).subscribe(response => {
            this.posts = response.json();
            this.disablesubmittedassignment();
            this.tableRerender();
            this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
          });

          let type = 'success';
          let title = 'Add Success';
          let body = 'New assignment added successfully'
          this.toasterMsg(type, title, body);

          this.assignmentForm.reset();
        } else {
          let type = 'error';
          let title = 'Add Fail';
          let body = 'New assignment add failed please try again'
          this.toasterMsg(type, title, body);
          // this.disablesubmittedassignment();
          this.assignmentForm.reset();
        }
      });
    }
    else {
      let type = 'error';
      let title = 'Add Fail';
      let body = 'Please select all dropdown values before you add'
      this.toasterMsg(type, title, body);
      // this.disablesubmittedassignment();
    }
    this.flag = 0;
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  marksData = [];

  //to save students in Manage Students Module
  saveStudents(save_assign_id) {
    var id;
    // this.sub = this.activatedRoute
    //   .queryParams
    //   .subscribe(params => {
    //     id = +params['id'] || 0;
        let assignData = { 'assignment_id': save_assign_id }
        this.service.subUrl = 'assignment/assignment/getTotalMarks';
        this.service.createPost(assignData).subscribe(response => {
          this.marksData = response.json();

          this.marksData.forEach(marks => {

            if (((marks.que_marks == marks.total_marks) || marks.total_marks == 0)) {
              var CheckedUsn;
              var StudentId = this.checked;
              var user_id = localStorage.getItem('id');
              this.service.subUrl = 'assignment/assignment/getStudentUsnById';
              this.service.createPost(StudentId).subscribe(response => {
                this.stu_usn = response.json();
                CheckedUsn = this.stu_usn;

                var assignmentHeadId = save_assign_id;
                let postData = {
                  'pgmDrop': this.pgmId,
                  'curclmDrop': this.crclmId,
                  'termDrop': this.termId,
                  'courseDrop': this.crsId,
                  'secDrop': this.sectionId,
                  'assignmentHeadId': assignmentHeadId,
                  'checkedId': StudentId,
                  'CheckedUsn': CheckedUsn,
                  'user_id': this.user_id,
                };
                this.service.subUrl = 'assignment/Assignment/saveStudent';
                this.service.createPost(postData).subscribe(response => {
                  if (response.json().status == 'ok') {

                    let type = 'success';
                    let title = 'Post Success';
                    let body = 'Assignment posted successfully';
                    this.checked.length = 0;
                    if ((this.checked.length == 0)) {
                      $("#submit_stu").attr('disabled', 'disabled');
                      $('#checkall').prop("disabled", false)
                      $('#checkall').prop("checked", false)
                    }
                    else {
                      // if ($('#checkall').is(":checked")) {
                      $("#submit_stu").prop('disabled', true);
                      $('#checkall').prop("disabled", true)
                      // }
                    }
                    this.toasterMsg(type, title, body);
                    // this.ngOnInit();

                  }
                  // else {
                  //   let type = 'error';
                  //   let title = 'Post Fail';
                  //   let body = 'Assignment post failed please try again'
                  //   this.toasterMsg(type, title, body);
                  //   // this.ngOnInit();
                  // }
                })
              });
            }
            else {
              let type = 'error';
              let title = 'Submit Fail';
              let body = 'Use all the assigned marks'
              this.toasterMsg(type, title, body);
              this.stuForm.reset();
              this.checked.length = 0;
              if ((this.checked.length == 0)) {
                $("#submit_stu").attr('disabled', 'disabled');
                $('#checkall').prop("checked", false);
              }
              // this.ngOnInit();
            }
          })
        });
      // });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  // to get data for edit fields
  editassignment(editElement: HTMLElement) {
    let Id = editElement.getAttribute('assignmentId');
    this.service.subUrl = 'assignment/Assignment/getassignmentsubmission';
    this.service.createPost(Id).subscribe(response => {
      if (response.json().status == 'ok') {
        this.title = "Edit Assignment Head";
        let assignmentName = editElement.getAttribute('assignmentName');

        let selecteInitialdDate = editElement.getAttribute('assignmentInitialDate');
        let year = selecteInitialdDate.substring(0, 4);
        let month = selecteInitialdDate.substring(5, 7);
        let day = selecteInitialdDate.substring(8, 10);
        let initial_day = day.replace(/^0+/, '');
        let initial_month = month.replace(/^0+/, '');
        this.model = { date: { year: year, month: initial_month, day: initial_day } };


        let assignmentEndDate = editElement.getAttribute('assignmentEndDate');
        let year1 = assignmentEndDate.substring(0, 4);
        let month1 = assignmentEndDate.substring(5, 7);
        let day1 = assignmentEndDate.substring(8, 10);
        let due_day = day1.replace(/^0+/, '');
        let due_month = month1.replace(/^0+/, '');
        this.model1 = { date: { year: year1, month: due_month, day: due_day } };


        let assignmentTotalMarks = editElement.getAttribute('assignmentTotalMarks');
        let assignmentInstruction = editElement.getAttribute('assignmentInstruction');
        let assignmentId = editElement.getAttribute('assignmentId');

        this.assignment.setValue(assignmentName);
        this.myinitialdate.setValue(this.model);
        this.myenddate.setValue(this.model1);
        this.totalmarks.setValue(assignmentTotalMarks);
        this.instructions.setValue(assignmentInstruction);
        this.setAssignmenttId = assignmentId;
        this.isSaveHide = true;
        this.isUpdateHide = false;
        this.flag = 1;
      }
      else {
        let type = 'error';
        let title = 'Edit Fail';
        let body = 'Student(s) have already submitted the assignment'
        this.toasterMsg(type, title, body);
      }
    });
  }
  // update assignment
  updatePost(updatePost) {
    this.service.subUrl = 'assignment/Assignment/updateAssignment';
    updatePost.stringify

    let postData = {
      'assignment': updatePost.value.assignment,
      'myinitialdate': updatePost.value.myinitialdate,
      'myenddate': updatePost.value.myenddate,
      'totalmarks': updatePost.value.totalmarks,
      'instructions': updatePost.value.instructions,
      'AssignmenttId': this.setAssignmenttId,
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'user_id': this.user_id,
    };

    this.service.updatePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'assignment/assignment/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.disablesubmittedassignment();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Assignment updated successfully'
        this.toasterMsg(type, title, body);

        this.cancelUpdate();
        // this.assignmentForm.reset();
        // this.isSaveHide = false;
        // this.isUpdateHide = true;

      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Assignment update failed please try again'
        this.toasterMsg(type, title, body);
        this.assignmentForm.reset();

      }
    });
    this.flag = 0;
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }
  // to reset form
  cancelUpdate() {
    this.flag = 0;
    this.title = "Add Assignment Head";
    this.assignmentForm.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    let Id = deleteElement.getAttribute('assignmentId');
    this.service.subUrl = 'assignment/Assignment/getassignmentsubmission';
    this.service.createPost(Id).subscribe(response => {
      if (response.json().status == 'ok') {
        let delAssignmentId;
        this.delAssignmentId = Id;
        (<any>jQuery('#assignmentDeleteModal')).modal('show');
      }
      else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Student(s) have already submitted the assignment'
        this.toasterMsg(type, title, body);
      }
    });

  }

  deleteAllWarning(deleteAllElement: HTMLElement, modalEle: HTMLDivElement) {


    this.deleteAllAssignId = this.checked;
    let postData = {
      'deleteAllAssignId': this.deleteAllAssignId
    }
    this.service.subUrl = 'assignment/Assignment/getAssignsubStatusForDeleteAll';
    this.service.createPost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        let delAssignmentId;
        this.delAssignmentId = this.deleteAllAssignId;
        (<any>jQuery('#assignmentDeleteAllModal')).modal('show');
      }
      else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'One or more assignments are submitted already'
        this.toasterMsg(type, title, body);
      }
    });
  }

  // delete assignment
  deleteAssignmentData(assignmentIdInput: HTMLInputElement) {

    this.service.subUrl = 'assignment/Assignment/deleteAssigment';

    let postData = {
      'assignmentId': assignmentIdInput.value,
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'user_id': this.user_id,
    };

    this.service.deletePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'assignment/assignment/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.disablesubmittedassignment();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Assignment deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#assignmentDeleteModal')).modal('hide');
        // this.disablesubmittedassignment();
        this.assignmentForm.reset();
        this.cancelUpdate();
        // this.ngOnInit();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Assignment delete failed please try again'
        this.toasterMsg(type, title, body);
        // this.disablesubmittedassignment();
        this.assignmentForm.reset();
        this.cancelUpdate();
        // this.ngOnInit();
      }

    });

  }



  //to get selected students in Manage Students Module
  checkIfSelected(stuid) {


    if ($("#" + stuid).is(":checked") && !($("#" + stuid).is(":disabled"))) {

      if (this.checked.indexOf(stuid) == -1) {
        this.checked.push(stuid);
        $("#" + stuid).prop('checked', true);
      }
      if ((this.checked.length > 0) && !($("#" + stuid).is(":disabled"))) {
        $("#submit_stu").removeAttr('disabled');
      }
    }
    else {
      var data = this.checked.indexOf(stuid);
      this.checked.splice(data, 1);
      $("#" + stuid).prop('checked', false);
      if ((this.checked.length == 0)) {
        $("#submit_stu").attr('disabled', 'disabled');
      }
    }       
    if (this.checked.length == this.stu_name.length) {   //stu_name is taken from the service getStudents of getdropdowndata function
      $("#checkall").prop('checked', true);
    } else {
      $("#checkall").prop('checked', false);
    }    
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

  deleteSelected(assignId) {

        if($("#" + assignId).is(":checked")) {

          if (this.checked.indexOf(assignId) == -1) {
            this.checked.push(assignId);
            $("#" + assignId).prop('checked', true);
          }

          if ((this.checked.length > 1)) {

            $("#delete_head").removeAttr("style")
          }
        }
    else {

          var data = this.checked.indexOf(assignId);
          this.checked.splice(data, 1);
          $("#" + assignId).prop('checked', false);

      if((this.checked.length == 1)) {

      $("#delete_head").attr("style", "visibility: hidden")
    }
  }
    this.deleteAllId = JSON.parse(JSON.stringify(this.posts));

if (this.checked.length == this.deleteAllId.assignmentList.length) {
  // if (this.checked.length == this.deleteAllId.length) {
  $("#delete_check").prop('checked', true);
} else {
  $("#delete_check").prop('checked', false);
}

  }

deleteAll() {

  this.deleteAllId = this.checked;
  let postData = {
    'deleteAllId': this.deleteAllId
  }

  let Data = {
    // 'assignmentId': assignmentIdInput.value,
    'pgmDrop': this.pgmId,
    'curclmDrop': this.crclmId,
    'termDrop': this.termId,
    'courseDrop': this.crsId,
    'secDrop': this.sectionId,
    'user_id': this.user_id,
  };
  this.service.subUrl = 'assignment/Assignment/deleteAllAssigment';
  this.service.createPost(postData).subscribe(response => {

    if (response.json().status == 'ok') {
      this.service.subUrl = 'assignment/assignment/index';
      this.service.createPost(Data).subscribe(response => {
        this.posts = response.json();
        this.disablesubmittedassignment();
        this.tableRerender();
        this.dtTrigger.next();

      });

      let type = 'success';
      let title = 'Delete Success';
      let body = 'Assignment deleted successfully'
      this.toasterMsg(type, title, body);

      this.checked.length = 0;
      if ((this.checked.length == 0)) {
        $("#delete_check").prop('checked', false);
        $("#delete_head").attr("style", "visibility: hidden");
      }

    } else {
      let type = 'error';
      let title = 'Delete Fail';
      let body = 'Assignment delete failed please try again'
      this.toasterMsg(type, title, body);
      // this.disablesubmittedassignment();
    }
  });

}

onDateChanged(event: IMyDateModel) {
  if (event.formatted == "") {
    this.myDatePickerOptions1.disableUntil.day = 0;
    this.myDatePickerOptions1.disableUntil.month = 0;
    this.myDatePickerOptions1.disableUntil.year = 0;
    // this.selDate = event.date;
  } else {

    this.myDatePickerOptions1.disableUntil.day = event.date.day - 1;
    this.myDatePickerOptions1.disableUntil.month = event.date.month;
    this.myDatePickerOptions1.disableUntil.year = event.date.year;
    this.selDate = event.date;
  }
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

//checkall function in manage students
checkall(e) {

  this.stu_name.forEach(element => {
    var stu_id = element.ssd_id;
    if ((e.target.checked) && !($("#" + stu_id).is(":disabled"))) {
      $("#" + stu_id).prop('checked', true);
      if (this.checked.indexOf(stu_id) == -1) {
        this.checked.push(stu_id);
        $("#" + stu_id).prop('checked', true);
      }
      if ((this.checked.length > 0) && !($("#" + stu_id).is(":disabled"))) {
        $("#submit_stu").removeAttr('disabled');

      }
    }
    else if (!(e.target.checked) && !($("#" + stu_id).is(":disabled"))) {
      $("#" + stu_id).prop('checked', false);
      var data = this.checked.indexOf(stu_id);
      this.checked.splice(data, 1);
      if ((this.checked.length == 0)) {
        $("#submit_stu").attr('disabled', 'disabled');
      }
    }
  });

}

deleteAllSelected(e) {

  this.deleteAllId = JSON.parse(JSON.stringify(this.posts));
  this.deleteAllId.assignmentList.forEach(element => {
    this.assignment_id = element.a_id;
    if ((e.target.checked) && !($("#" + this.assignment_id).is(":disabled"))) {
      $("#" + this.assignment_id).prop('checked', true);
      if ((this.checked.indexOf(this.assignment_id) == -1)) {
        this.checked.push(this.assignment_id);
        $("#" + this.assignment_id).prop('checked', true);
      }
      if ((this.checked.length > 1)) {
        $("#delete_head").removeAttr("style");
      }
    }
    else if (!(e.target.checked) && !($("#" + this.assignment_id).is(":disabled"))) {
      $("#" + this.assignment_id).prop('checked', false);
      $("#delete_check").prop('checked', false);
      var data = this.checked.indexOf(this.assignment_id);
      this.checked.splice(data, 1);
      if ((this.checked.length == 1)) {
        $("#delete_head").attr("style", "visibility: hidden");
      }
    }
  })
}

// uncheckAll(e) {
//   this.stu_name.forEach(element => {
//     if ($("#" + element.ssd_id).is(":checked") && !($("#" + element.ssd_id).is(":disabled"))) {
//       $("#" + element.ssd_id).prop('checked', false);
//     }
//     $('#checkall').prop('checked', false);
//     $('#submit_stu').prop('disabled', true);
//   });
// }
// }
uncheckAll() {
  
  this.stu_name.forEach(element => {
    if ($("#" + element.ssd_id).is(":checked") && !($("#" + element.ssd_id).is(":disabled"))) {
      $("#" + element.ssd_id).prop('checked', false);
    }
    $('#checkall').prop('checked', false);
    $('#submit_stu').prop('disabled', true);
  });
}
}
