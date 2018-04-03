import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { Component, OnInit, Input, ViewChild, AfterViewInit } from '@angular/core';
import { IMyDpOptions } from 'mydatepicker';
import { Injectable } from '@angular/core';
import { PostService } from '../../services/post.service';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { FormsModule } from '@angular/forms';
import { ContentWrapperComponent } from '../../content-wrapper/content-wrapper.component';
import { CharctersOnlyValidation } from '../../custom.validators';
import { ActivatedRoute, Params } from "@angular/router";
import { Title } from '@angular/platform-browser';
import { customDateFormatPipe } from '../../services/date-format.pipe';
import { RouterModule } from '@angular/router';
import * as $ from 'jquery';
import { forEach } from '@angular/router/src/utils/collection';

@Component({
  selector: 'app-assignment-review',
  templateUrl: './assignment-review.component.html',
  styleUrls: ['./assignment-review.component.css']
})
export class AssignmentReviewComponent implements OnInit {

  constructor(private service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    public titleService: Title) { }
  curriculumName;
  termName;
  courseName;
  sectionName;
  secName;
  private sub: any;
  assignmentName;
  id;
  student_id;
  tosterconfig;
  stu_name: Array<any>;
  stu_list: Array<any>;
  
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  ngOnInit() {

    this.titleService.setTitle('Assignment Review | IONCUDOS');
    this.curriculumName = localStorage.getItem('currDropdown');
    this.termName = localStorage.getItem('termDropdown');
    this.courseName = localStorage.getItem('courseDropdown');
    this.sectionName = localStorage.getItem('sectionDropdown');
    // this.sectionName.forEach(element => {
    //   this.secName = element.name;
    // })
    var programValue = localStorage.getItem('programDropdownId');
    var curriculumValue = localStorage.getItem('currDropdownId');
    var termValue = localStorage.getItem('termDropdownId');
    var courseValue = localStorage.getItem('courseDropdownId');
    var sectionValue = localStorage.getItem('sectionDropdownId');
    let postData = {
      'pgmDrop': programValue,
      'curclmDrop': curriculumValue,
      'termDrop': termValue,
      'courseDrop': courseValue,
      'secDrop': sectionValue,
      // 'crclmName' : curriculumName
    };

    this.sub = this.activatedRoute
    .queryParams
    .subscribe(params => {
      // Defaults to 0 if no query param provided.
      this.id = +params['id'] || 0;
      this.service.subUrl = 'assignment/ReviewAssignment/getAssignDetails';
      this.service.createPost(this.id).subscribe(response => {
        this.assignmentName = response.json();
      });
    });

     // to fetch students
     this.service.subUrl = 'assignment/ReviewAssignment/getStudents';
     this.service.createPost(this.id).subscribe(response => {
       this.stu_list = response.json();   
       this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table             
     });
    //  alert(JSON.stringify(this.student_id))
     //to get students name through id              
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
}
