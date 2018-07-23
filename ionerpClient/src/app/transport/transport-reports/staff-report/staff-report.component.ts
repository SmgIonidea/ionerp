import { Component, OnInit, ViewChild } from '@angular/core';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
// import { Angular2Csv } from 'angular2-csv/Angular2-csv';
@Component({
  selector: 'app-staff-report',
  templateUrl: './staff-report.component.html',
  styleUrls: ['./staff-report.component.css']
})
export class StaffReportComponent implements OnInit {

  constructor(private service: PostService) { }

  staffWiseReport;
  maintitle;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  ngOnInit() {

    this.maintitle = "Staff Wise Report (Driver Copy)";

    this.service.subUrl = 'transport/staff_report/getStaffWiseReportData';
    this.service.getData().subscribe(response => {
      this.staffWiseReport = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    })

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

  // export_to_excel(){
  //   var data = [
  //      {
  //       Sl_No: "1",
  //       EMP_ID: "52",
  //       Name_of_the_staff: "Bharti kashyap",
  //       Department:"TEACHING STAFF",
  //       Post:"HSST ZOOLOGY",
  //       Type_of_Vehicle:"bus",
  //       RegNo_of_the_Vehicle: "8065 ",
  //       Route:"Root 1(Sitai Hat)",
  //       Driver_name:"ANILKUMAR.S"
  //      },
  //      {
  //       Sl_No: "2",
  //       EMP_ID: "55",
  //       Name_of_the_staff: "Mustansar sdf",
  //       Department:"TEACHING STAFF",
  //       Post:"HSST CHEMISTRY",
  //       Type_of_Vehicle:"bus",
  //       RegNo_of_the_Vehicle: "8065 ",
  //       Route:"Root 1(Board 1)",
  //       Driver_name:"ANILKUMAR.S"
  //      },
       
  //    ];
 
  //   //  new Angular2Csv(data, 'Staff_Report');
  //  }
}
