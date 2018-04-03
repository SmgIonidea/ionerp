import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { ContentWrapperComponent } from './../../content-wrapper/content-wrapper.component';
import { PostService } from './../../services/post.service';
import { CharctersOnlyValidation } from './../../custom.validators';
import { Component, OnInit, Input, ViewChild, AfterViewInit, Injectable } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { Title } from '@angular/platform-browser';
@Component({
  selector: 'app-receivecourse-material',
  templateUrl: './receivecourse-material.component.html',
  styleUrls: ['./receivecourse-material.component.css']
})
@Injectable()
export class ReceivecourseMaterialComponent implements OnInit, AfterViewInit {

  constructor(private service: PostService,
    private toast: ToastService,
    public titleService: Title) { }

  /* Global Variable Declarations */
  titletable: Array<any> = [];
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  // listPageHeading = "Department List";
  // operationHeading = "Department Add/Edit";
  posts = [];
  isSaveHide: boolean;
  isUpdateHide: boolean;
  // setDeptId:any; // department id used to update the details
  // @Input('deptId')delDeptId; // Input binding used to place department id in hidden text box to delete the dept. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;

  //base url assigned to variable
  curriculumValueFile: string;
  courseValueFile: string;
  baseUrl: string;

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

  ngOnInit() {

    this.currentPageVal = 'receiveCourseMaterial';
    this.titleService.setTitle('RecieveCourseMaterial | IONCUDOS');

    this.title = 'Received Course Materials';

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

    this.title = 'Received Course Materials';

    this.service.subUrl = 'configuration/Master/title_name';
    this.service.getData().subscribe(response => {
      this.titletable = response.json();

      this.tableRerender();
      this.dtTrigger.next();

    });

    this.curriculumValueFile = localStorage.getItem('currDropdown');
    this.courseValueFile = localStorage.getItem('courseDropdown');

    if (localStorage.getItem('currDropdown') && localStorage.getItem('courseDropdown')) {
      this.curriculumValueFile = this.curriculumValueFile.replace(/ /g, '_');
      this.courseValueFile = this.courseValueFile.replace(/ /g, '_');
    }

    this.baseUrl = this.service.baseUrlFile + 'course_materials/' + this.curriculumValueFile + '/' + this.courseValueFile + '/faculty/';
    this.titleService.setTitle('RecieveCourseMaterial | IONCUDOS');
    // var programValue = localStorage.getItem('programDropdownId');
    // var curriculumValue = localStorage.getItem('currDropdownId');
    // var termValue = localStorage.getItem('termDropdownId');
    // var courseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId
    };

    this.service.subUrl = 'course_material/Coursematerial/receive';
    this.service.createPost(postData).subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });

    this.isSaveHide = false;
    this.isUpdateHide = true;

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
