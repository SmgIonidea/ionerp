import { Component, OnInit, TemplateRef, ViewChild, Input, Injectable } from '@angular/core';
import { PostService } from './../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { Title } from '@angular/platform-browser';
import { customDateFormatPipe } from './../../services/date-format.pipe';
@Injectable()
@Component({
  selector: 'app-studenttake-assignment',
  templateUrl: './studenttake-assignment.component.html',
  styleUrls: ['./studenttake-assignment.component.css']
})
export class StudenttakeAssignmentComponent implements OnInit {

  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  studentassignmentType: Array<any>;

  dtInstance: DataTables.Api;
  public currentPageVal;

  title: string; //load title

  programType: Array<string>;
  curriculumType: Array<string> = [];
  termType: Array<string> = [];
  courseType: Array<string> = [];
  sectionType: Array<string> = [];
  pgmId: any = 0;
  crclmId: any = 0;
  termId: any = 0;
  crsId: any = 0;
  sectionId: any = 0;
  userId;

  constructor(private service: PostService,
    public titleService: Title) { }

  ngOnInit() {

    this.currentPageVal = 'studentTakeAssignment';
    this.title = 'My Assignment List';

    this.titleService.setTitle('StudentTakeAssignment | IONCUDOS');


    this.userId = localStorage.getItem('id');
    this.service.subUrl = 'configuration/Master/getProgram';
    this.service.createPost(this.userId).subscribe(response => {
      this.programType = response.json();
      if (localStorage.getItem('programDropdownId') != null && localStorage.getItem('programDropdownId') != '0') {
        this.programType.forEach(termElement => {
          if (termElement['pgm_id'] == localStorage.getItem('programDropdownId')) {
            this.pgmId = termElement['pgm_id'];
            let postData = {
              'userId': this.userId,
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
                      'userId': this.userId,
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
                              'userId': this.userId,
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
                                      'userId': this.userId,
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

  //method to load curriculum dropdown data
  curriculum(event) {

    this.studentassignmentType = [];
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
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'pgmDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getCurriculum';
    this.service.createPost(postData).subscribe(response => {
      this.curriculumType = response.json();
    });
  }

  //method to load term dropdown data
  term(event) {

    this.studentassignmentType = [];
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
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'curDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getTerm';
    this.service.createPost(postData).subscribe(response => {
      this.termType = response.json();
    });
  }

  //method to load course dropdown data
  course(event) {

    this.studentassignmentType = [];
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
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'termDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getCourse';
    this.service.createPost(postData).subscribe(response => {
      this.courseType = response.json();
    });
  }

  //method to load section dropdown data
  section(event) {

    this.studentassignmentType = [];
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
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'crsDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getSection';
    this.service.createPost(postData).subscribe(response => {
      this.sectionType = response.json();
    });
  }

  getdropdowndata() {

    this.sectionType.forEach(sectionElement => {
      if (sectionElement['id'] == this.sectionId) {
        localStorage.setItem('sectionDropdown', sectionElement['name']);
        localStorage.setItem('sectionDropdownId', sectionElement['id']);
      }
    });

    this.title = 'My Assignment List';

    // var studentId = localStorage.getItem('id');
    // var programValue = localStorage.getItem('programDropdownId');
    // var curriculumValue = localStorage.getItem('currDropdownId');
    // var termValue = localStorage.getItem('termDropdownId');
    // var courseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');

    let postData = {
      'stdId': this.userId,
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'sectionDrop': this.sectionId

    };

    this.service.subUrl = 'student_assignment/Studentassignment/getStudentAssignment';
    this.service.createPost(postData).subscribe(response => {
      this.studentassignmentType = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table    
    });

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

}
