import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DriverReportComponent } from './driver-report/driver-report.component';
import { VehicleReportComponent } from './vehicle-report/vehicle-report.component';
import { StudentReportComponent } from './student-report/student-report.component';
import { StaffReportComponent } from './staff-report/staff-report.component';
import { DataTablesModule } from 'angular-datatables';
@NgModule({
  imports: [
    CommonModule,
    DataTablesModule
  ],
  declarations: [DriverReportComponent,
    VehicleReportComponent,
    StudentReportComponent,
    StaffReportComponent]
})
export class TransportReportsModule { }
