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
import { DataTablesModule } from 'angular-datatables';
import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
import { MyDatePickerModule } from 'mydatepicker';
import { EmployeeModule } from './employee/employee.module';
import { PayslipGenerationModule } from './payslip-generation/payslip-generation.module';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
@NgModule({
  imports: [
    CommonModule,
    DataTablesModule,
    MultiselectDropdownModule,
    MyDatePickerModule,
    FormsModule,
    ReactiveFormsModule

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
