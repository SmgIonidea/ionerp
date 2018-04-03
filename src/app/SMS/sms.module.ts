import { NgModule, Component } from '@angular/core';
import { RouterModule } from '@angular/router';
import { DataTablesModule } from 'angular-datatables';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';  
import { MyDatePickerModule } from 'mydatepicker';//datepicker
import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
import { DualListBoxModule } from 'ng2-dual-list-box';
import { SendsmsComponent } from '../SMS/sendsms/sendsms.component';
import { EnquirylistComponent } from '../SMS/enquirylist/enquirylist.component';
import { SmssetupComponent } from '../SMS/smssetup/smssetup.component';

@NgModule({
    declarations: [
        SendsmsComponent,
        EnquirylistComponent,
        SmssetupComponent
    ],
    imports: [
        FormsModule,
        ReactiveFormsModule,
        RouterModule,
        DataTablesModule,
        CommonModule,
        MultiselectDropdownModule,
        DualListBoxModule,
        MyDatePickerModule
    ],
    providers:[
        
    ],
    exports:[

    ],
})

export class SMSModule {

}