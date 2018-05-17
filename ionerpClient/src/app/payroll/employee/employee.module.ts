import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IssueLoanComponent } from './issue-loan/issue-loan.component';
import { LoanIssuedToComponent } from './loan-issued-to/loan-issued-to.component';
@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    IssueLoanComponent,

    LoanIssuedToComponent,

  ]
})
export class EmployeeModule { }
