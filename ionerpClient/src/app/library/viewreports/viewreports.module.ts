import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AllbooksComponent } from './allbooks/allbooks.component';
import { BooksavailabilityComponent } from './booksavailability/booksavailability.component';
import { StudentreportComponent } from './studentreport/studentreport.component';
import { StaffreportComponent } from './staffreport/staffreport.component';
import { BooksissuedstudentsComponent } from './/booksissuedstudents/booksissuedstudents.component';
import { BooksissuedstaffComponent } from './booksissuedstaff/booksissuedstaff.component';
import { AllfinesComponent } from './allfines/allfines.component';
import { BookanalysisComponent } from './bookanalysis/bookanalysis.component';
import { DataTablesModule } from 'angular-datatables';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
@NgModule({
  imports: [
    CommonModule,
    DataTablesModule,
    MyDatePickerModule,
    FormsModule, ReactiveFormsModule,
    RouterModule
  ],
  declarations: [ AllbooksComponent,
    BooksavailabilityComponent,
    StudentreportComponent,
    StaffreportComponent,
    BooksissuedstudentsComponent,
    BooksissuedstaffComponent,
    AllfinesComponent,
    BookanalysisComponent,
  ],
  exports: [AllbooksComponent,
    BooksavailabilityComponent,
    StudentreportComponent,
    StaffreportComponent,
    BooksissuedstudentsComponent,
    BooksissuedstaffComponent,
    AllfinesComponent,
    BookanalysisComponent,],
  bootstrap: [AllbooksComponent,
    BooksavailabilityComponent,
    StudentreportComponent,
    StaffreportComponent,
    BooksissuedstudentsComponent,
    BooksissuedstaffComponent,
    AllfinesComponent,
    BookanalysisComponent,]
})
export class ViewreportsModule { }
