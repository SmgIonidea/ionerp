import { Component, OnInit,ViewChild } from '@angular/core';
// import { Angular2Csv } from 'angular2-csv/Angular2-csv';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { RouterModule, ActivatedRoute, Params, Event } from '@angular/router';
import * as $ from 'jquery';
import { Subject } from 'rxjs/Rx';
@Component({
  selector: 'app-student-report',
  templateUrl: './student-report.component.html',
  styleUrls: ['./student-report.component.css']
})
export class StudentReportComponent implements OnInit {
  studentWiseReportList;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();
  constructor(private service: PostService, 
    private activatedRoute: ActivatedRoute,) { }


  ngOnInit() {
    this.service.subUrl = 'transport/StudentWiseReport/getStudentWiseReportList';
    this.service.getData().subscribe(response => {
      this.studentWiseReportList = response.json();
      this.tableRerender();
      this.dtTrigger.next();
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
