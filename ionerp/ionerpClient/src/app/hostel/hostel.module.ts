import { NgModule, Component } from '@angular/core';
import { CommonModule } from '@angular/common';  

import { HostelComponent } from '../hostel/hostel.component';
import { AddBuildingComponent } from '../hostel/add-building/add-building.component';
import { AddRoomComponent } from '../hostel/add-room/add-room.component';
import { RoomAvailabilityComponent } from '../hostel/room-availability/room-availability.component';
import { RoomAllocationComponent } from '../hostel/room-allocation/room-allocation.component';
import { ViewHostelPersonsComponent } from '../hostel/view-hostel-persons/view-hostel-persons.component';
import { CollectItemsComponent } from '../hostel/collect-items/collect-items.component';
import { PrepareBillComponent } from '../hostel/prepare-bill/prepare-bill.component';
import { ViewDetailsComponent } from '../hostel/view-details/view-details.component';
import { IssueItemsComponent } from '../hostel/issue-items/issue-items.component';
import { HealthRecordComponent } from '../hostel/health-record/health-record.component';
import { DeAllocateComponent } from '../hostel/de-allocate/de-allocate.component';
import { ReportComponent } from '../hostel/report/report.component';

import { DataTablesModule } from 'angular-datatables';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { ToasterModule, ToasterService } from 'angular2-toaster';
import { ToastComponent } from '../toast/toast.component';
import { ToastService } from '../common/toast.service';
import { MyDatePickerModule } from 'mydatepicker';//datepicker


@NgModule({
    declarations: [
        HostelComponent,
        AddBuildingComponent,
        AddRoomComponent,
        RoomAvailabilityComponent,
        RoomAllocationComponent,
        ViewHostelPersonsComponent,
        CollectItemsComponent,
        PrepareBillComponent,
        ViewDetailsComponent,
        IssueItemsComponent,
        HealthRecordComponent,
        DeAllocateComponent,
        ReportComponent,
        ToastComponent
    ],
    imports: [
        DataTablesModule,
        FormsModule,
        ReactiveFormsModule,
        RouterModule,
        ToasterModule,
        CommonModule,
        MyDatePickerModule,
    ],
    providers: [
        ToastService,
        ToasterService
    ],
    exports: [
        AddBuildingComponent,
        AddRoomComponent,
        RoomAvailabilityComponent,
        RoomAllocationComponent,
        ViewHostelPersonsComponent,
        CollectItemsComponent,
        PrepareBillComponent,
        ViewDetailsComponent,
        IssueItemsComponent,
        HealthRecordComponent,
        DeAllocateComponent,
        ReportComponent,
    ],
    bootstrap: [HostelComponent]
})
export class HostelModule { }