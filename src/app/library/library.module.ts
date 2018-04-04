import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { OpacComponent } from './opac/opac.component';
import {MasterRecordsModule } from './master-records/master-records.module';
import {TransactionModule } from './transaction/transaction.module';
import {ViewreportsModule } from './viewreports/viewreports.module';
import { DataTablesModule } from 'angular-datatables';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
@NgModule({
  imports: [
    CommonModule,
    DataTablesModule,
    MyDatePickerModule,
    FormsModule, ReactiveFormsModule,
    RouterModule,
    
    
    MasterRecordsModule,
    TransactionModule,
    ViewreportsModule
  ],
  declarations: [
    
   
   
    OpacComponent,
  ],
  exports: [OpacComponent],
  bootstrap: [OpacComponent]
})
export class LibraryModule { }
