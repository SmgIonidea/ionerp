import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AnnualLeaveComponent } from './annual-leave/annual-leave.component';
import { AllowanceTypeComponent } from './allowance-type/allowance-type.component';
import { DeductionTypeComponent } from './deduction-type/deduction-type.component';
import { LoanComponent } from './loan/loan.component';
import { TaxComponent } from './tax/tax.component';
import { PFComponent } from './pf/pf.component';
import { IssueLoanComponent } from './employee/issue-loan/issue-loan.component';
import { LoanIssuedToComponent } from './employee/loan-issued-to/loan-issued-to.component';
import { EmployeePayslipComponent } from './payslip-generation/employee-payslip/employee-payslip.component';
import { PayslipListComponent } from './payslip-generation/payslip-list/payslip-list.component';
@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    AnnualLeaveComponent,

    AllowanceTypeComponent,

    DeductionTypeComponent,

    LoanComponent,

    TaxComponent,

    PFComponent,

    IssueLoanComponent,

    LoanIssuedToComponent,

    EmployeePayslipComponent,

    PayslipListComponent,

  ]
})
export class PayrollModule { }
