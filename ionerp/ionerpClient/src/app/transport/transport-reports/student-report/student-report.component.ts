import { Component, OnInit } from '@angular/core';
// import { Angular2Csv } from 'angular2-csv/Angular2-csv';
@Component({
  selector: 'app-student-report',
  templateUrl: './student-report.component.html',
  styleUrls: ['./student-report.component.css']
})
export class StudentReportComponent implements OnInit {

  constructor() { }

  ngOnInit() {
  }

  export_to_excel(){
    var data = [
       {
        reg_no: "532",
         Name: "Pankaj",
         Father_Name: "Sbk",
         From_Place:"Vulta(Root 1)(Boart 1)",
         Mobile_No:"9560248029",
         TPT_Fee:"Rs 500.00",
        
       },
       {
        reg_no: "526",
         Name: "PRIYA",
         Father_Name: "GANESH RAM PATEL",
         From_Place:"Vulta(Root 1)(Boart 1)",
         Mobile_No:"7582844252",
         TPT_Fee:"Rs 500.00",
        
       },
       
     ];
 
    //  new Angular2Csv(data, 'Student_Report');
   }
}
