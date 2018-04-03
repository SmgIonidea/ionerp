import { Component, OnInit } from '@angular/core';
// import { Angular2Csv } from 'angular2-csv/Angular2-csv';
@Component({
  selector: 'app-vehicle-report',
  templateUrl: './vehicle-report.component.html',
  styleUrls: ['./vehicle-report.component.css']
})
export class VehicleReportComponent implements OnInit {

  constructor() { }

  ngOnInit() {
    
  }

  export_to_excel(){
   var data = [
      {
        date_of_purchase: "12-03-2004",
        type: "Van",
        registration_number: "KA 14 A 007",
        route:"Vijaynagar to Vinobhanagar",
        insurance_valid_upto:"22-09-2038",
        allocated_driver_name:"Prakash",
        seating_capacity: "8 "
      },
      
    ];

    // new Angular2Csv(data, 'Vehicle_Report');
  }
}
