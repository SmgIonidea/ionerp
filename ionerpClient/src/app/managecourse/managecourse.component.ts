import { Component, OnInit, TemplateRef, ViewChild, Input, Injectable } from '@angular/core';
import { PostService } from './../services/post.service';
import { Title } from '@angular/platform-browser';
import { Router, ActivatedRoute, NavigationEnd, Event } from '@angular/router';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { AssignmentHeadComponent } from './../instructor/assignment-head/assignment-head.component';
import { savedId } from './../services/saved_id';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import * as $ from 'jquery';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../common/toast.service';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { IMultiSelectSettings } from 'angular-2-dropdown-multiselect';
// import "node_modules/ladda/css/ladda";

@Injectable()
@Component({
  selector: 'app-managecourse',
  templateUrl: './managecourse.component.html',
  styleUrls: ['./managecourse.component.css']
})
export class ManagecourseComponent implements OnInit {

  /* Global Variable Declarations */
  titletable_manage: Array<any> = [];
  managecourseType: Array<any> = [];
  managecoursedrop: Array<any>;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;

  tosterconfig;
  manageToast: Array<any>;
  manageProceed: Array<any>;
  managelistUsername;

  dtInstance: DataTables.Api;
  public currentPageVal;
  title: any;

  programType: Array<string>;
  curriculumType: Array<string> = [];
  termType: Array<string> = [];
  courseType: Array<string> = [];
  sectionType: IMultiSelectOption[];
  pgmId: any = 0;
  crclmId: any = 0;
  termId: any = 0;
  crsId: any = 0;
  sectionId: any = 0;
  size: number = 0;

  progress: boolean | number = false;

  /* Constructor */
  constructor(private service: PostService,
    private toast: ToastService,
    private assignHead: AssignmentHeadComponent,
    public titleService: Title,
    router: Router,
    activatedRoute: ActivatedRoute) { }


  startLoading() {
    this.progress = 0; // starts spinner

    setTimeout(() => {
      this.progress = 0.5; // sets progress bar to 50%

      setTimeout(() => {
        this.progress = 0.5; // sets progress bar to 100%

        setTimeout(() => {
          this.progress = false; // stops spinner
        }, 200);
      }, 500);
    }, 400);
  }

  ngOnInit() {
    $('#loader').hide();
    $('#proceed').attr("disabled", "disabled");

    this.currentPageVal = 'manageCourse';
    this.title = 'Manage Course Instructor';

    this.service.subUrl = 'configuration/Master/title_name';
    this.service.getData().subscribe(response => {
      this.titletable_manage = response.json();
      this.tableRerender();
      this.dtTrigger.next();    // Calling the DT trigger to manually render the table     
    });

    this.titleService.setTitle('ManageCourse | IONCUDOS');

    var userId = localStorage.getItem('id');
    this.service.subUrl = 'manage_course/Managecourse/getProgram';
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
            this.service.subUrl = 'manage_course/Managecourse/getCurriculum';
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
                    this.service.subUrl = 'manage_course/Managecourse/getTerm';
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
                            this.service.subUrl = 'manage_course/Managecourse/getCourse';
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
                                    this.service.subUrl = 'manage_course/Managecourse/getSection';
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
  //method to load curriculum dropdown data
  curriculum(event) {
    $('#proceed').attr("disabled", "disabled");
    if(this.managecourseType.length > 0){
      this.managecourseType = [];
      this.tableRerender();
      this.dtTrigger.next();
    }
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
    this.service.subUrl = 'manage_course/Managecourse/getCurriculum';
    this.service.createPost(postData).subscribe(response => {
      this.curriculumType = response.json();
    });
  }

  //method to load term dropdown data
  term(event) {
    $('#proceed').attr("disabled", "disabled");
    if(this.managecourseType.length > 0){
      this.managecourseType = [];
      this.tableRerender();
      this.dtTrigger.next();
    }
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
    this.service.subUrl = 'manage_course/Managecourse/getTerm';
    this.service.createPost(postData).subscribe(response => {
      this.termType = response.json();
    });
  }

  //method to load course dropdown data
  course(event) {
    $('#proceed').attr("disabled", "disabled");
    if(this.managecourseType.length > 0){
      this.managecourseType = [];
      this.tableRerender();
      this.dtTrigger.next();
    }
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
    this.service.subUrl = 'manage_course/Managecourse/getCourse';
    this.service.createPost(postData).subscribe(response => {
      this.courseType = response.json();
    });
  }

  //method to load section dropdown data
  section(event) {
    $('#proceed').attr("disabled", "disabled");
    if(this.managecourseType.length > 0){
      this.managecourseType = [];
      this.tableRerender();
      this.dtTrigger.next();
    }
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
    this.service.subUrl = 'manage_course/Managecourse/getSection';
    this.service.createPost(postData).subscribe(response => {
      this.sectionType = response.json();
    });
  }

  //method to load data based on dropdown changed
  getdropdowndata() {
    $('#proceed').attr("disabled", "disabled");
    this.sectionType.forEach(sectionElement => {
      if (sectionElement['id'] == this.sectionId) {
        localStorage.setItem('sectionDropdown', sectionElement['name']);
        localStorage.setItem('sectionDropdownId', sectionElement['id']);
      }
    });

    this.service.subUrl = 'configuration/Master/title_name';
    this.service.getData().subscribe(response => {
      this.titletable_manage = response.json();
      this.tableRerender();
      this.dtTrigger.next();    // Calling the DT trigger to manually render the table     
    });

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'sectionDrop': this.sectionId,
    };

    if (this.sectionId != '0' && this.sectionId != null) {
      this.service.subUrl = 'manage_course/Managecourse/getManageCourseProceedStatus';
      this.service.createPost(postData).subscribe(response => {
        this.manageProceed = response.json();
        // console.log(this.manageProceed[0]['status']);
        if (response.json().status == 'ok') {
          if (this.manageProceed[0]['status'] == "1") {
            $('#proceed').attr("disabled", "disabled");
          }
        } else {
          $('#proceed').removeAttr("disabled");
        }
      });
    }

    this.service.subUrl = 'manage_course/Managecourse/getManageCourse';
    this.service.createPost(postData).subscribe(response => {
      this.managecourseType = response.json();
      if (!(this.managecourseType.length > 0))
        $('#proceed').attr("disabled", "disabled");
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
    });


    this.service.subUrl = 'manage_course/Managecourse/getManageCoursedrop';
    this.service.createPost(postData).subscribe(response => {
      this.managecoursedrop = response.json();
    });
  }

  // To show dropdown and buttons(save,cancel) and display course instructor
  startEdit(id) {
    this.size++;
    var username = id;
    let postData = {
      'user': username,
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'sectionDrop': this.sectionId
    };
    this.service.subUrl = 'manage_course/Managecourse/getManageCourse1';
    this.service.createPost(postData).subscribe(response => {
      this.managelistUsername = response.json();
      this.managelistUsername.forEach(shows => {
        $('#select_' + id).val(shows.id);
      })
    });
    $('#para_' + id).hide();
    $('#select_' + id).show();
    $('#submit_' + id).show();
    $('#cancel_' + id).show();
    $('#hide' + id).hide();
    $('#para1_' + id).hide();
  }

  //To hide dropdown and buttons(save,cancel)
  save(id) {
    this.size--;
    // $('#para_' + id).show();
    // $('#select_' + id).hide();
    $('#submit_' + id).hide();
    $('#cancel_' + id).hide();
    $('#hide' + id).show();
    // $('#proceed').removeAttr("disabled");
    if (this.size == 0)
      $('#resize').removeClass("col-lg-2");
    // this.ngOnInit();

  }

  //To hide dropdown and buttons(save,cancel)
  cancel(id) {
    this.size--;
    // this.getdropdowndata();
    $('#para_' + id).show();
    $('#select_' + id).hide();
    $('#submit_' + id).hide();
    $('#cancel_' + id).hide();
    $('#hide' + id).show();
    // this.ngOnInit();
    if (this.size == 0)
      $('#resize').removeClass("col-lg-2");

  }

  //To Get Row Id and Instructor ID
  public save_id(dropId, userId) {
    // var skillsSelect = document.getElementById('select_'+dropId).outerText;
    // document.getElementById('select_'+dropId).options[document.getElementById('select_'+dropId).selectedIndex].text;
    // let skillsSelect = $("#select_"+dropId+" option:selected").text();
    // $('#para1_' + dropId).append(skillsSelect);
    // console.log(skillsSelect);

    localStorage.setItem('dropIds', dropId);
    localStorage.setItem('savedId', userId);

  }

  //To Update Managecourse Instructor
  proceedDelivery(id) {

    // $('#proceed').attr("disabled", "disabled");
    var drops = localStorage.getItem('dropIds');
    var user = localStorage.getItem('savedId');
    var user1 = JSON.stringify(user).split(",");
    var newElement = user1[0].split(',');
    var idval = JSON.stringify(newElement[0]).replace('"', '');
    idval = idval.replace(/\\/g, "");
    idval = idval.replace('"', '');
    idval = idval.replace('"', '');
    idval = idval.replace('"', '');
    idval = idval.replace('"', '');
    var programValue = localStorage.getItem('programDropdownId');
    var curriculumValue = localStorage.getItem('currDropdownId');
    var termValue = localStorage.getItem('termDropdownId');
    var courseValue = localStorage.getItem('courseDropdownId');
    var sectionValue = localStorage.getItem('sectionDropdownId');

    this.service.subUrl = 'manage_course/Managecourse/manageInstructorUpdate';
    let postData = {
      'insid': idval,
      'topic': drops,
      'pgmDrop': programValue,
      'curclmDrop': curriculumValue,
      'termDrop': termValue,
      'courseDrop': courseValue,
      'sectionDrop': sectionValue
    };
    this.service.createPost(postData).subscribe(response => {
      //  this.posts = response.json();
      // this.tableRerender();
      // this.dtTrigger.next();// Calling the DT trigger to manually render the table
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'Instructor assigned'
        this.toasterMsg(type, title, body);

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Instructor assigned add failed please try again'
        this.toasterMsg(type, title, body);

      }
      // this.ngOnInit();
      let skillsSelect = $("#select_" + id + " option:selected").text();
      $('#para_' + id).html(skillsSelect);
      $('#select_' + id).hide();
      $('#para_' + id).show();
    });

  }

  // To update Status from 0 to 1
  // proceed() {
  //   this.service.subUrl = 'manage_course/Managecourse/proceedToDelivery';
  //   var mng_programValue = localStorage.getItem('programDropdownId');
  //   var mng_curriculumValue = localStorage.getItem('currDropdownId');
  //   var mng_termValue = localStorage.getItem('termDropdownId');
  //   var mng_courseValue = localStorage.getItem('courseDropdownId');
  //   var mng_sectionValue = localStorage.getItem('sectionDropdownId');
  //   let postData = {
  //     'pgmDrop': mng_programValue,
  //     'curclmDrop': mng_curriculumValue,
  //     'termDrop': mng_termValue,
  //     'courseDrop': mng_courseValue,
  //     'sectionDrop': mng_sectionValue,
  //   };
  //   this.service.createPost(postData).subscribe(response => {
  //     //  this.posts = response.json();
  //     // this.tableRerender();
  //     // this.dtTrigger.next();// Calling the DT trigger to manually render the table
  //     // if (response.json().status == 'ok') {
  //     //   let type = 'success';
  //     //   let title = 'Add Success';
  //     //   let body = 'status'
  //     //   this.toasterMsg(type, title, body);
  //     // } else {
  //     //   let type = 'error';
  //     //   let title = 'Add Fail';
  //     //   let body = 'Instructor Assigned Add Failed Please Try Again'
  //     //   this.toasterMsg(type, title, body);
  //     // }
  //   });
  // }

  //To insert lesson schedule
  insertproceed() {
    $('#loader').show();
    var user_id = localStorage.getItem('id');
    var drops = localStorage.getItem('dropIds');
    var user = localStorage.getItem('savedId');
    var user1 = JSON.stringify(user).split(",");
    var user_id = localStorage.getItem('id');
    var newElement = user1[0].split(',');
    var idval = JSON.stringify(newElement[0]).replace('"', '');
    idval = idval.replace(/\\/g, "");
    idval = idval.replace('"', '');
    idval = idval.replace('"', '');
    idval = idval.replace('"', '');
    idval = idval.replace('"', '');

    this.service.subUrl = 'manage_course/Managecourse/insertToDelivery';
    let postData = {
      'manage_data': this.managecourseType,
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'sectionDrop': this.sectionId,
      'insid': idval,
      'topic': drops,
      'user_id': user_id
    };
    this.service.createPost(postData).subscribe(response => {
      //  this.posts = response.json();
      // this.tableRerender();
      // this.dtTrigger.next();// Calling the DT trigger to manually render the table
      if (response.json().status == 'ok') {

        this.service.subUrl = 'manage_course/Managecourse/proceedToDelivery';
        this.service.createPost(postData).subscribe(response => {
           this.service.subUrl = 'manage_course/Managecourse/getManageCourse';
        this.service.createPost(postData).subscribe(response => {
          this.managecourseType = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
        });
        });
       
        let type = 'success';
        $('#proceed').attr("disabled", "disabled");
        $('#loader').hide();
        let title = 'Proceed Success';
        let body = 'Topics are proceeded'
        this.toasterMsg(type, title, body);
      } else {
        $('#proceed').removeAttr("disabled");
        $('#loader').hide();
        let type = 'error';
        let title = 'Proceed Fail';
        let body = 'Topics are proceeded failed please try again'
        this.toasterMsg(type, title, body);
      }
    });

  }



  //To show popup which is already assigned instructor and disable the save button
  toastmsg(id) {
    this.service.subUrl = 'manage_course/Managecourse/getManageCourseStatus';
    var top_id = id;
    var programValue = localStorage.getItem('programDropdownId');
    var curriculumValue = localStorage.getItem('currDropdownId');
    var termValue = localStorage.getItem('termDropdownId');
    var courseValue = localStorage.getItem('courseDropdownId');
    var sectionValue = localStorage.getItem('sectionDropdownId');
    let postData = {

      'topic': top_id,
      'pgmDrop': programValue,
      'curclmDrop': curriculumValue,
      'termDrop': termValue,
      'courseDrop': courseValue,
      'sectionDrop': sectionValue
    };
    this.service.createPost(postData).subscribe(response => {
      this.manageToast = response.json();
      if (response.json().status == 'ok') {
        // this.managecourseType.forEach(id => {
        //   $('#submit_' + id.topic_id).attr("disabled", "disabled");
        // })
        let type = 'error';
        let title = '';
        let body = 'Course instructor already assigned to lesson schedule'
        this.toasterMsg(type, title, body);
      } else {
        // let type = 'error';
        // let title = 'Add Fail';
        // let body = 'Course Instructor Already Assigned!'
        // this.toasterMsg(type, title, body);
      }
    });
    // this.ngOnInit();
  }

  // To resize the table when clicks edit
  resize() {
    $('#resize').removeAttr("style");
    $('#resize').addClass("col-lg-2");
    $('#re_size1').removeAttr("style");
    $('#re_size1').addClass("col-lg-1");
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
