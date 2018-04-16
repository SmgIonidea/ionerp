import { NgModule, Component } from '@angular/core';
import { RouterModule } from '@angular/router';
import { DataTablesModule } from 'angular-datatables';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MyDatePickerModule } from 'mydatepicker';//datepicker
import { CommonModule } from '@angular/common';  
import { DualListBoxModule } from 'ng2-dual-list-box';
import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
import { TinymceComponent } from '../thirdparty/tinymce/tinymce.component';
import { MessageinboxComponent } from '../Message/messageinbox/messageinbox.component';
import { SentmessagesComponent } from '../Message/sentmessages/sentmessages.component';
import { ComposemessageComponent } from '../Message/composemessage/composemessage.component';

@NgModule({
    declarations: [
        MessageinboxComponent,
        SentmessagesComponent,
        ComposemessageComponent,
        TinymceComponent
    ],
    imports:[
        DataTablesModule,
        MyDatePickerModule,
        CommonModule,
        FormsModule,
        ReactiveFormsModule,
        DualListBoxModule,
        MultiselectDropdownModule,
        
        
    ],
    providers:[
        TinymceComponent
    ],
    exports:[

    ],
})

export class MessageModule {

}