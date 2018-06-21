import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MyDatePickerModule } from 'mydatepicker';
import { DataTablesModule } from 'angular-datatables';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';
import { IssueLoanComponent } from './issue-loan/issue-loan.component';
import { LoanIssuedToComponent } from './loan-issued-to/loan-issued-to.component';

@NgModule({
  imports: [
    CommonModule,
    MyDatePickerModule,
    FormsModule,ReactiveFormsModule,
    DataTablesModule
  ],
  declarations: [
    IssueLoanComponent,

    LoanIssuedToComponent,

  ]
})
export class EmployeeModule { }
