import { AccountingGroupComponent } from './accounting-group/accounting-group.component';
import { ManageVocherComponent } from './manage-vocher/manage-vocher.component';
import { LedgerComponent } from './ledger/ledger.component';
import { NgModule } from '@angular/core';
import { LayoutModule } from '../.../../layout/layout.module';
import { AccountTransactionModule } from './acc-transaction/acc-transaction.module';
import { AccReportsModule } from './acc-reports/acc-reports.module';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { DataTablesModule } from 'angular-datatables';

@NgModule({
    imports: [

        CommonModule,
        RouterModule,
        FormsModule,
        DataTablesModule,
        MyDatePickerModule,
        AccountTransactionModule,
        AccReportsModule

    ],
    declarations: [
        AccountingGroupComponent,
        ManageVocherComponent,
        LedgerComponent,
       

    ],
    exports: [AccountingGroupComponent, ManageVocherComponent, LedgerComponent],
    bootstrap: [AccountingGroupComponent, ManageVocherComponent, LedgerComponent]
})
export class AccountingModule { }
