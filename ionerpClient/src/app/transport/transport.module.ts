import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DataTablesModule } from 'angular-datatables';
import { RouteComponent } from './route/route.component';
import { RouteListComponent } from './route-list/route-list.component';
import { VehicleListComponent } from './vehicle-list/vehicle-list.component';
import { DriversListComponent } from './drivers-list/drivers-list.component';
import { TransportBillsComponent } from './transport-bills/transport-bills.component';
import { MaintenanceComponent } from './maintenance/maintenance.component';
import { BoardlistComponent } from './boardlist/boardlist.component';
import { VehicleBoardComponent } from './vehicle-board/vehicle-board.component';
import { DriverVehicleComponent } from './driver-vehicle/driver-vehicle.component';
import {TransportReportsModule} from './transport-reports/transport-reports.module';
import { MyRouteDetailsComponent } from './my-route-details/my-route-details.component';
import { AllRouteBoardComponent } from './all-route-board/all-route-board.component'
@NgModule({
  imports: [
    CommonModule,
    RouterModule,
    FormsModule,
    DataTablesModule,
    MyDatePickerModule,
    TransportReportsModule
    
  ],
  declarations: [RouteComponent,
    RouteListComponent,
    VehicleListComponent,
    DriversListComponent,
    TransportBillsComponent,
    MaintenanceComponent,
    BoardlistComponent,
    VehicleBoardComponent,
    DriverVehicleComponent,
    MyRouteDetailsComponent,
    AllRouteBoardComponent,
    ]
})
export class TransportModule { }
