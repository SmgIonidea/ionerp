import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EmployeePayslipComponent } from './employee-payslip/employee-payslip.component';
import { PayslipListComponent } from './payslip-list/payslip-list.component';

@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [
    EmployeePayslipComponent,

    PayslipListComponent,
  ]
})
export class PayslipGenerationModule { }
