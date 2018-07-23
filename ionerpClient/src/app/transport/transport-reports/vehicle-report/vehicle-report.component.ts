import { Component, OnInit ,ViewChild} from '@angular/core';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { RouterModule, ActivatedRoute, Params, Event } from '@angular/router';
import * as $ from 'jquery';
import { Subject } from 'rxjs/Rx';

@Component({
  selector: 'app-vehicle-report',
  templateUrl: './vehicle-report.component.html',
  styleUrls: ['./vehicle-report.component.css']
})
export class VehicleReportComponent implements OnInit {
  vehicleReportList;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();
  constructor(private service: PostService, 
    private activatedRoute: ActivatedRoute,) { }


  ngOnInit() {
    this.service.subUrl = 'transport/VehicleReport/getVehicleReportList';
    this.service.getData().subscribe(response => {
      this.vehicleReportList = response.json();
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
