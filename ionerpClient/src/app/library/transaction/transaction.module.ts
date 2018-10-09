import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DataTablesModule } from 'angular-datatables';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { StudentreturnbooksComponent } from './studentreturnbooks/studentreturnbooks.component';
import { StaffreturnbooksComponent } from './staffreturnbooks/staffreturnbooks.component';
import { RouterModule } from '@angular/router';
import { ToasterModule, ToasterService, ToasterConfig } from 'angular2-toaster';
@NgModule({
  imports: [
    CommonModule,
    DataTablesModule,
    MyDatePickerModule,
    FormsModule, ReactiveFormsModule,
    RouterModule,
    ToasterModule
  ],
  declarations: [
    StudentreturnbooksComponent,
    StaffreturnbooksComponent,
  ],
    exports: [StudentreturnbooksComponent,
      StaffreturnbooksComponent,],
  bootstrap: [StudentreturnbooksComponent,
    StaffreturnbooksComponent,]
})
export class TransactionModule { }
