import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { EmployeePayslipComponent } from './employee-payslip/employee-payslip.component';
import { PayslipListComponent } from './payslip-list/payslip-list.component';
import { MyDatePickerModule } from 'mydatepicker';
import { DataTablesModule } from 'angular-datatables';
import { FormsModule,ReactiveFormsModule } from '@angular/forms';
@NgModule({
  imports: [
    CommonModule,
    MyDatePickerModule,
    FormsModule,ReactiveFormsModule,
    DataTablesModule
  ],
  declarations: [
    EmployeePayslipComponent,

    PayslipListComponent,
  ]
})
export class PayslipGenerationModule { }
