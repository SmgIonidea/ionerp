// import { NgModule } from '@angular/core';
// import { CommonModule } from '@angular/common';
// import { BalancesheetComponent } from './balancesheet/balancesheet.component';
// import { LedgersummaryComponent } from './ledgersummary/ledgersummary.component';
// import { FormsModule, ReactiveFormsModule } from '@angular/forms';
// import { DataTablesModule } from 'angular-datatables';
// import { MyDatePickerModule } from 'mydatepicker';
// import { RouterModule } from '@angular/router';
// @NgModule({
//   imports: [
//     CommonModule,
//     RouterModule,
//     FormsModule,
//     DataTablesModule,
//     MyDatePickerModule
//   ],
//   declarations: [ BalancesheetComponent,
//     LedgersummaryComponent,
// ],exports: [BalancesheetComponent,
//   LedgersummaryComponent],
// bootstrap: [BalancesheetComponent,
//   LedgersummaryComponent]
// })
// export class AccReportsModule { }
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BalancesheetComponent } from './balancesheet/balancesheet.component';
import { LedgersummaryComponent } from './ledgersummary/ledgersummary.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DataTablesModule } from 'angular-datatables';
import { MyDatePickerModule } from 'mydatepicker';
import { RouterModule } from '@angular/router';
@NgModule({
  imports: [
    CommonModule,
    RouterModule,
    ReactiveFormsModule,
    FormsModule,
    DataTablesModule,
    MyDatePickerModule
  ],
  declarations: [BalancesheetComponent,
    LedgersummaryComponent,
  ], exports: [BalancesheetComponent,
    LedgersummaryComponent],
  bootstrap: [BalancesheetComponent,
    LedgersummaryComponent]
})
export class AccReportsModule { }

