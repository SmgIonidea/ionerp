import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {EmployeeModule} from './employee/employee.module';
import {PayslipGenerationModule} from './payslip-generation/payslip-generation.module';
import { AnnualLeaveComponent } from './annual-leave/annual-leave.component';
import { AllowanceTypeComponent } from './allowance-type/allowance-type.component';
import { DeductionTypeComponent } from './deduction-type/deduction-type.component';
import { LoanComponent } from './loan/loan.component';
import { TaxComponent } from './tax/tax.component';
import { PFComponent } from './pf/pf.component';
@NgModule({
  imports: [
    CommonModule,
    EmployeeModule,
    PayslipGenerationModule
  ],
  declarations: [
    AnnualLeaveComponent,

    AllowanceTypeComponent,

    DeductionTypeComponent,

    LoanComponent,

    TaxComponent,

    PFComponent,

    
   

  ]
})
export class PayrollModule { }
