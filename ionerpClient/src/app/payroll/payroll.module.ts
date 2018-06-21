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
import { DataTablesModule } from 'angular-datatables';
import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
import { MyDatePickerModule } from 'mydatepicker';

import {FormsModule, ReactiveFormsModule} from '@angular/forms';
@NgModule({
  imports: [
    CommonModule,
    DataTablesModule,
    MultiselectDropdownModule,
    MyDatePickerModule,
    FormsModule,
    ReactiveFormsModule,
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
