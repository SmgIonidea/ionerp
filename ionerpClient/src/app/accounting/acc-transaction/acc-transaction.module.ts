
// import { VoucherComponent } from './voucher/voucher.component';

// import { NgModule } from '@angular/core';
// import { LayoutModule } from '../../layout/layout.module';
// import { CommonModule } from '@angular/common';
// import { RouterModule } from '@angular/router';
// import { MyDatePickerModule } from 'mydatepicker';
// import { FormsModule,ReactiveFormsModule } from '@angular/forms';
// import { DataTablesModule } from 'angular-datatables';


// @NgModule({
//   imports: [

//     CommonModule,
//     RouterModule,
//     FormsModule,
//     DataTablesModule,
//     MyDatePickerModule

//   ],
//   declarations: [

//     VoucherComponent,




//   ],
//   exports: [VoucherComponent],
//   bootstrap: [VoucherComponent]
// })
// export class AccountTransactionModule { }
import { VoucherComponent } from './voucher/voucher.component';
import { NgModule } from '@angular/core';
import { LayoutModule } from '../../layout/layout.module';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DataTablesModule } from 'angular-datatables';
import { ToasterModule, ToasterService } from 'angular2-toaster';
import { ToastComponent } from '../../toast/toast.component';



@NgModule({
  imports: [

    CommonModule,
    RouterModule,
    FormsModule,
    ReactiveFormsModule,
    DataTablesModule,
    MyDatePickerModule,
    ToasterModule,
  ],
  declarations: [

    VoucherComponent,




  ],
  exports: [VoucherComponent],
  bootstrap: [VoucherComponent]
})
export class AccountTransactionModule { }
