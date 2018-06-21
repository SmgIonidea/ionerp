import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MyDatePickerModule } from 'mydatepicker';
import { PostVacancyComponent } from './post-vacancy/post-vacancy.component';
import { ClassifiedsComponent } from './classifieds/classifieds.component';
import { ApplicantEnquiryComponent } from './applicant-enquiry/applicant-enquiry.component';
import { SearchApplicantsComponent } from './search-applicants/search-applicants.component';
import { TakeInterviewComponent } from './take-interview/take-interview.component';
import { ApplicantsListComponent } from './applicants-list/applicants-list.component';
import { OfferLetterComponent } from './offer-letter/offer-letter.component';
import { LetterFormatsComponent } from './letter-formats/letter-formats.component';
import { ResignTerminationComponent } from './resign-termination/resign-termination.component';
import { OtherLetterFormatsComponent } from './other-letter-formats/other-letter-formats.component';
import { SendLetterComponent } from './send-letter/send-letter.component';
import { PrintLetterComponent } from './print-letter/print-letter.component';
import { DataTablesModule } from 'angular-datatables';



@NgModule({

  imports: [
    CommonModule,
    DataTablesModule,
    FormsModule,
    ReactiveFormsModule,
    MyDatePickerModule


    
  ],

  declarations: [
    PostVacancyComponent,
    ClassifiedsComponent,
    ApplicantEnquiryComponent,
    SearchApplicantsComponent,
    TakeInterviewComponent,
    ApplicantsListComponent,
    OfferLetterComponent,
    LetterFormatsComponent,
    ResignTerminationComponent,
    OtherLetterFormatsComponent,
    SendLetterComponent,
    PrintLetterComponent,
 
    
   
  ],
  providers:[
    
  ]
})
export class HrdModule { }
