import { AppRouter } from './app.router';
import { LoginModule } from './login/login.module';
// import { ToastService } from './common/toast.service';
import { PostService } from './services/post.service';
import { HttpModule } from '@angular/http';
import { BrowserModule } from '@angular/platform-browser';
import { DataTablesModule } from 'angular-datatables';
import { NgModule, Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AppComponent } from './app.component';
import { TitleCasePipe } from './title-case.pipe';

import { ContentWrapperComponent } from './content-wrapper/content-wrapper.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { LayoutModule } from './layout/layout.module';
import { DepartmentComponent } from './department/department.component';
import { ToasterModule, ToasterService } from 'angular2-toaster';
import { ToastComponent } from './toast/toast.component';
import { DashboardComponent } from './dashboard/dashboard.component';

import { DropdownComponent } from './dropdown/dropdown.component';
import { StickyNavModule } from 'ng2-sticky-nav';
import { ManagecourseComponent } from './managecourse/managecourse.component';
import { AssignmentHeadComponent } from './instructor/assignment-head/assignment-head.component';
import { ManageAssignmentComponent } from './instructor/manage-assignment/manage-assignment.component';
import { SharecourseMaterialComponent } from './instructor/sharecourse-material/sharecourse-material.component';
import { ReceivecourseMaterialComponent } from './student/receivecourse-material/receivecourse-material.component';
import { AngularMultiSelectModule } from 'angular2-multiselect-dropdown/angular2-multiselect-dropdown';
import { AssignmentReviewComponent } from './instructor/assignment-review/assignment-review.component';
import { ViewAnswersComponent } from './instructor/view-answers/view-answers.component';
import { MyDatePickerModule } from 'mydatepicker';//datepicker
// import { TinymceComponent } from './thirdparty/tinymce/tinymce.component';

import { StudenttakeAssignmentComponent } from './student/studenttake-assignment/studenttake-assignment.component';
import { TakeAssignmentComponent } from './student/take-assignment/take-assignment.component';
import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
import { customDateFormatPipe } from './services/date-format.pipe';
import { Ng2PageScrollModule } from 'ng2-page-scroll'; //Page scroll
import { LessonScheduleComponent } from './instructor/lesson-schedule/lesson-schedule.component';
import { AccordionModule } from "ng2-accordion"; //accordion
import { ActivityComponent } from './faculty/activity/activity.component';
import { ManageRubricsComponent } from "./faculty/manage-rubrics/manage-rubrics.component";
import { TakeActivityComponent } from './student/take-activity/take-activity.component';
import { AuthGuard } from './guards/auth.guard';
import { StudentloginComponent } from './studentlogin/studentlogin.component';
import { RoleGuard } from "./guards/role.guard";
import { LaddaModule } from 'angular2-ladda';

import { ReviewActivityComponent } from '../app/faculty/review-activity/review-activity.component';
import { KeysPipe } from './keys.pipe';
import { MessageModule } from './Message/message.module';
import { SMSModule } from './SMS/sms.module';

import { AdmissionFormComponent } from './admission-form/admission-form.component';
import { AccountingModule } from './accounting/accounting.module';
import { TransportModule } from './transport/transport.module';

import { HostelComponent } from './hostel/hostel.component';
import { HostelModule } from './hostel/hostel.module';

import { DualListBoxModule } from 'ng2-dual-list-box';
import {HrdModule} from './hrd/hrd.module'; //HRD Component
import {PayrollModule} from './payroll/payroll.module'; //Payroll Component

import { LibraryModule } from './library/library.module';


@NgModule({
  declarations: [
    AppComponent,
    TitleCasePipe,

    ContentWrapperComponent,
    DepartmentComponent,

    DashboardComponent,

    DropdownComponent,
    ManagecourseComponent,

    AssignmentHeadComponent,
    ManageAssignmentComponent,
    SharecourseMaterialComponent,
    ReceivecourseMaterialComponent,
    AssignmentReviewComponent,
    ViewAnswersComponent,

    StudenttakeAssignmentComponent,
    TakeAssignmentComponent,
    customDateFormatPipe,
    LessonScheduleComponent,
    ActivityComponent,
    ManageRubricsComponent,
    StudentloginComponent,

    TakeActivityComponent,
    ReviewActivityComponent,
    KeysPipe,

    AdmissionFormComponent,
    // TinymceComponent

    

    

  ],
  imports: [
    BrowserModule,
    ToasterModule,
    FormsModule,
    ReactiveFormsModule,
    HttpModule,
    BrowserAnimationsModule,
    DataTablesModule,
    AppRouter,
    StickyNavModule,
    AngularMultiSelectModule,
    MyDatePickerModule,
    MultiselectDropdownModule,
    Ng2PageScrollModule,
    AccordionModule,
    LaddaModule,
    DualListBoxModule.forRoot(),
    MessageModule,
    SMSModule,
    HostelModule,
    LayoutModule,
    AccountingModule,
    TransportModule,
    LibraryModule,
    LoginModule,
    HrdModule,
    PayrollModule,
    // TinymceComponent

  ],
  providers: [
    PostService,
    
    AssignmentHeadComponent,
    SharecourseMaterialComponent,
    ReceivecourseMaterialComponent,
    ManagecourseComponent,
    StudenttakeAssignmentComponent,
    TakeAssignmentComponent,
    ManageAssignmentComponent,
    ActivityComponent,
   
    AuthGuard,
    RoleGuard,
    TakeActivityComponent,
    // TinymceComponent

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }


/*
  RouterModule.forRoot([
      {
      path:'', 
      component:LoginComponent,
      pathMatch:'full'
    },
    {
      path:'content', 
      component:ContentWrapperComponent,
      children:[
        {
          path:'', 
          component:DashboardComponent, 
          pathMatch:'full',
          outlet:'appCommon'
        },
        {
          path:'department',
          component:DepartmentComponent,
          pathMatch:'full',
          outlet:'appCommon',
        }
      ]

    },
  ]), 
 */
