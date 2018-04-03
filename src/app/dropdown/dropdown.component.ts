import { Component, OnInit, Input } from '@angular/core';
import { PostService } from './../services/post.service';
import { Title } from '@angular/platform-browser';
import { Router, ActivatedRoute, NavigationEnd, Event } from '@angular/router';
import { IMultiSelectOption, IMultiSelectSettings, IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { AssignmentHeadComponent } from './../instructor/assignment-head/assignment-head.component';
import { SharecourseMaterialComponent } from './../instructor/sharecourse-material/sharecourse-material.component';
import { ManagecourseComponent } from '../managecourse/managecourse.component';
import { ReceivecourseMaterialComponent } from '../student/receivecourse-material/receivecourse-material.component';
import { StudenttakeAssignmentComponent } from './../student/studenttake-assignment/studenttake-assignment.component';
import { TakeAssignmentComponent } from './../student/take-assignment/take-assignment.component';

@Component({
  selector: 'app-dropdown',
  templateUrl: './dropdown.component.html',
  styleUrls: ['./dropdown.component.css'],

})
export class DropdownComponent implements OnInit {
  programType: Array<string>;
  programType1: Array<string>;
  curriculumType: Array<string> = [];
  termType: Array<string> = [];
  courseType: Array<string> = [];
  title: any;
  optionsModel: number[];
  sectionType: IMultiSelectOption[];
  programDropdown: string;
  pgmId;
  crclmId;
  termId;
  crsId;
  currDropdown: string;
  termDropdown: string;
  courseDropdown: string;
  sectionDropdown: string;
  programDrop: string;
  curriculums: string;
  termDrop: string;
  courseDrop: string;
  sectionDrop: string;
  multivalueStr: Array<any>;
  changedValue: number[] = [];
  change: number[];
  @Input() currentPage;


  constructor(private service: PostService,
    titleService: Title,
    router: Router,
    activatedRoute: ActivatedRoute,
    private manageCourse: ManagecourseComponent,
    private assignmentHead: AssignmentHeadComponent,
    private shareCourseMaterial: SharecourseMaterialComponent,
    private studentTakeAssignment: StudenttakeAssignmentComponent,
    private receiveCourseMaterial: ReceivecourseMaterialComponent) {
    router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.title = this.getTitle(router.routerState, router.routerState.root).join('-');
      }
    });
  }

  ngOnInit() {
    var userId = localStorage.getItem('id');

    if (localStorage.getItem('programDropdownId') === null) {
      this.pgmId = 0;
      if (localStorage.getItem('currDropdownId') === null) {
        this.crclmId = 0;
        if (localStorage.getItem('termDropdownId') === null) {
          this.termId = 0;
          if (localStorage.getItem('courseDropdownId') === null) {
            this.crsId = 0;
          }
        }
      }
    }

    this.service.subUrl = 'configuration/Master/getProgram';
    this.service.createPost(userId).subscribe(response => {
      this.programType = response.json();
      if (localStorage.getItem('programDropdown') || localStorage.getItem('currDropdown') || localStorage.getItem('termDropdown') ||
        localStorage.getItem('courseDropdown') || localStorage.getItem('sectionDropdownId')) {
        this.programDropdown = localStorage.getItem('programDropdown');
        this.currDropdown = localStorage.getItem('currDropdown');
        this.termDropdown = localStorage.getItem('termDropdown');
        this.courseDropdown = localStorage.getItem('courseDropdown');
        // console.log(this.pgmId);
        this.pgmId = localStorage.getItem('programDropdownId');
        this.crclmId = localStorage.getItem('currDropdownId');
        this.termId = localStorage.getItem('termDropdownId');
        this.crsId = localStorage.getItem('courseDropdownId');

        // console.log(this.changedValue);
        if (localStorage.getItem('programDropdown') != "" || localStorage.getItem('currDropdown') != "" || localStorage.getItem('termDropdown') != "" || localStorage.getItem('courseDropdown') != "") {
          this.curriculum(localStorage.getItem('programDropdownId'));
          this.term(localStorage.getItem('currDropdownId'));
          this.course(localStorage.getItem('termDropdownId'));
          this.section(localStorage.getItem('courseDropdownId'));
        }

      }
      var multivalueStr;
      var multivalue
      multivalueStr = localStorage.getItem('sectionDropdownId');

      if(localStorage.getItem('sectionDropdownId') !== null) {
        multivalue = multivalueStr.split(',');

        multivalue.forEach(element => {

          if (this.changedValue.indexOf(element) == -1) {
            this.changedValue.push(element);
          }
        });  
      }
      
    });

  }

  //method to load curriculum dropdown data

  curriculum(event) {
    var programData
    // this.programDropdown = '';
    this.curriculumType.length = 0;
    this.termType.length = 0;
    this.courseType.length = 0;
    this.changedValue.length = 0;
    programData = JSON.parse(JSON.stringify(this.programType));
    // this.currDropdown = "";
    if (event != localStorage.getItem('programDropdownId')) {
      this.crclmId = 0;
    }
    programData.forEach(programElement => {
      if (programElement.pgm_id == event) {
        localStorage.setItem('programDropdown', programElement.pgm_acronym);
        localStorage.setItem('programDropdownId', programElement.pgm_id);
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
      localStorage.setItem('curriculumType', JSON.stringify(this.curriculumType));
    });
  }


  //method to load term dropdown data
  term(event) {
    var curriculumData
    this.termType.length = 0;
    this.courseType.length = 0;
    this.changedValue.length = 0;


    if (localStorage.getItem('curriculumType')) {
      curriculumData = JSON.parse(localStorage.getItem('curriculumType'));
    }
    else {
      curriculumData = JSON.parse(JSON.stringify(this.curriculumType));
    }
    console.log(curriculumData);

    if (event != localStorage.getItem('currDropdownId')) {
      this.termId = 0;
    }

    curriculumData.forEach(curriculumElement => {

      if (curriculumElement.crclm_id == event) {

        localStorage.setItem('currDropdown', curriculumElement.crclm_name);
        localStorage.setItem('currDropdownId', event);

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
      localStorage.setItem('termType', JSON.stringify(this.termType));
    });
  }


  //method to load course dropdown data
  course(event) {
    var termData
    this.courseType.length = 0;
    this.changedValue.length = 0;

    if (localStorage.getItem('termType')) {
      termData = JSON.parse(localStorage.getItem('termType'));
    }
    else {
      termData = JSON.parse(JSON.stringify(this.termType));
    }

    if (event != localStorage.getItem('termDropdownId')) {
      this.crsId = 0;
    }

    termData.forEach(termElement => {

      if (termElement.crclm_term_id == event) {

        localStorage.setItem('termDropdown', termElement.term_name);
        localStorage.setItem('termDropdownId', termElement.crclm_term_id);
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
      localStorage.setItem('courseType', JSON.stringify(this.courseType));
    });
  }

  //method to load section dropdown data
  section(event) {

    var courseData
    if (localStorage.getItem('courseType')) {
      courseData = JSON.parse(localStorage.getItem('courseType'));
    }
    else {
      courseData = JSON.parse(JSON.stringify(this.courseType));
    }

    courseData.forEach(courseElement => {

      if (courseElement.crs_id == event) {

        localStorage.setItem('courseDropdown', courseElement.crs_title);
        localStorage.setItem('courseCodeDropdown', courseElement.crs_code);
        localStorage.setItem('courseDropdownId', event);

      }

    });
    var userId = localStorage.getItem('id');
    let postData = {
      'userId': userId,
      'crsDrop': event,
    };
    this.changedValue.length = 0;
    this.service.subUrl = 'configuration/Master/getSection';
    this.service.createPost(postData).subscribe(response => {
      this.sectionType = response.json();
      localStorage.setItem('sectionType', JSON.stringify(this.sectionType));
    });
  }

  //method to load data based on dropdown changed
  getdropdowndata() {
    localStorage.removeItem('sectionDropdownId');
    var sectionData;
    var options;
    // this.changedValue = [];
    localStorage.setItem('sectionDropdownId', this.changedValue.toString());

    if (localStorage.getItem('sectionType')) {
      sectionData = JSON.parse(localStorage.getItem('sectionType'));
    }
    else {

      sectionData = JSON.parse(JSON.stringify(this.sectionType));
    }


    if (this.currentPage == 'manageCourse') {
      this.manageCourse.ngOnInit();
    }
    if (this.currentPage == 'assignmentHead') {
      this.assignmentHead.ngOnInit();
    }
    if (this.currentPage == 'shareCourseMaterial') {
      this.shareCourseMaterial.ngOnInit();
    }
    if (this.currentPage == 'studentTakeAssignment') {
      this.studentTakeAssignment.ngOnInit();
    }
    if (this.currentPage == 'receiveCourseMaterial') {
      this.receiveCourseMaterial.ngOnInit();
    }


    //    this.assignHead.getdata(localStorage.getItem('courseType'), localStorage.getItem('termType'));
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

  clearcurr() {

    this.currDropdown = 'Select Curriculum';
    this.termDropdown = 'Select Term';
    this.courseDropdown = 'Select Course';

  }

  clearterm() {
    this.termDropdown = 'Select Term';
    this.courseDropdown = 'Select Course';

  }

  clearcourse() {
    this.courseDropdown = 'Select Course';
  }


  loadAllData() {

    localStorage.removeItem('sectionDropdownId');

    if (this.currentPage == 'manageCourse') {
      this.manageCourse.ngOnInit();
    }
    if (this.currentPage == 'assignmentHead') {
      this.assignmentHead.ngOnInit();
    }
    if (this.currentPage == 'shareCourseMaterial') {
      this.shareCourseMaterial.ngOnInit();
    }
    if (this.currentPage == 'studentTakeAssignment') {
      this.studentTakeAssignment.ngOnInit();
    }
    if (this.currentPage == 'receiveCourseMaterial') {
      this.receiveCourseMaterial.ngOnInit();
    }

  }

}