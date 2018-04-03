import { AppRouter } from './app.router';
import { LoginComponent } from './login/login.component';
// import { ToastService } from './common/toast.service';
import { PostService } from './services/post.service';
import { HttpModule } from '@angular/http';
import { BrowserModule } from '@angular/platform-browser';
import { DataTablesModule } from 'angular-datatables';
import { NgModule, Component } from '@angular/core';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { AppComponent } from './app.component';
import { TitleCasePipe } from './title-case.pipe';
// import { MainHeaderComponent } from './main-header/main-header.component';
// import { MainSidenavComponent } from './main-sidenav/main-sidenav.component';
// import { FooterComponent } from './footer/footer.component';
import { ContentWrapperComponent } from './content-wrapper/content-wrapper.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
// import { RouterModule } from '@angular/router';
import {LayoutModule} from './layout/layout.module';
import { DepartmentComponent } from './department/department.component';
import { ToasterModule, ToasterService } from 'angular2-toaster';
import { ToastComponent } from './toast/toast.component';
import { DashboardComponent } from './dashboard/dashboard.component';
// import { MenuNavbarComponent } from './menu-navbar/menu-navbar.component';
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
// import { CharLimiterPipe } from "./services/char-limiter.pipe";
import { ReviewActivityComponent } from '../app/faculty/review-activity/review-activity.component';
import { KeysPipe } from './keys.pipe';
import { MessageModule } from './Message/message.module';
import { SMSModule } from './SMS/sms.module';
// import { MessageinboxComponent } from './Message/messageinbox/messageinbox.component';
// import { SentmessagesComponent } from './Message/sentmessages/sentmessages.component';
// import { ComposemessageComponent } from './Message/composemessage/composemessage.component';
// import { SendsmsComponent } from './SMS/sendsms/sendsms.component';
// import { EnquirylistComponent } from './SMS/enquirylist/enquirylist.component';
// import { SmssetupComponent } from './SMS/smssetup/smssetup.component';
import { AdmissionFormComponent } from './admission-form/admission-form.component';
import { AccountingModule } from './accounting/accounting.module';
import { TransportModule } from './transport/transport.module';
// import { LedgerComponent } from './instructor/account/ledger/ledger.component';
// import { VoucherComponent } from './instructor/account/voucher/voucher.component';
// import { BalancesheetComponent } from './instructor/account/balancesheet/balancesheet.component';
// import { LedgersummaryComponent } from './instructor/account/ledgersummary/ledgersummary.component';
// import { AccountingComponent} from './instructor/account/accounting/accounting.component';
// import { ManageVocherComponent } from './instructor/account/manage-vocher/manage-vocher.component';
// import { RouteComponent } from './instructor/transport/route/route.component';
// import { RouteListComponent } from './instructor/transport/route-list/route-list.component';
// import { VehicleListComponent } from './instructor/transport/vehicle-list/vehicle-list.component';
// import { DriversListComponent } from './instructor/transport/drivers-list/drivers-list.component';
// import { TransportBillsComponent } from './instructor/transport/transport-bills/transport-bills.component';
// import { MaintenanceComponent } from './instructor/transport/maintenance/maintenance.component';
// import { BoardlistComponent } from './instructor/transport/boardlist/boardlist.component';
// import { VehicleBoardComponent } from './instructor/transport/vehicle-board/vehicle-board.component';
// import { DriverVehicleComponent } from './instructor/transport/driver-vehicle/driver-vehicle.component';
// import { DriverReportComponent } from './instructor/transport/driver-report/driver-report.component';
// import { VehicleReportComponent } from './instructor/transport/vehicle-report/vehicle-report.component';
// import { StudentReportComponent } from './instructor/transport/student-report/student-report.component';
// import { StaffReportComponent } from './instructor/transport/staff-report/staff-report.component';
import { HostelComponent } from './hostel/hostel.component';
import { HostelModule } from './hostel/hostel.module';
// import { RoomAvailabilityComponent } from './hostel/room-availability/room-availability.component';
// import { RoomAllocationComponent } from './hostel/room-allocation/room-allocation.component';
// import { ViewHostelPersonsComponent } from './hostel/view-hostel-persons/view-hostel-persons.component';
// import { CollectItemsComponent } from './hostel/collect-items/collect-items.component';
// import { PrepareBillComponent } from './hostel/prepare-bill/prepare-bill.component';
// import { ViewDetailsComponent } from './hostel/view-details/view-details.component';
// import { IssueItemsComponent } from './hostel/issue-items/issue-items.component';
// import { HealthRecordComponent } from './hostel/health-record/health-record.component';
// import { DeAllocateComponent } from './hostel/de-allocate/de-allocate.component';
// import { ReportComponent } from './hostel/report/report.component';
// import { AddBuildingComponent } from './hostel/add-building/add-building.component';
// import { AddRoomComponent } from './hostel/add-room/add-room.component';
import { DualListBoxModule } from 'ng2-dual-list-box';
import { CategoryComponent } from './library/master-records/category/category.component';
import { SubcategoryComponent } from './library/master-records/subcategory/subcategory.component';
import { LibraryfineComponent } from './library/master-records/libraryfine/libraryfine.component';
import { PublisherComponent } from './library/master-records/publisher/publisher.component';
import { BooksComponent } from './library/master-records/books/books.component';
import { StudentreturnbooksComponent } from './library/transaction/studentreturnbooks/studentreturnbooks.component';
import { StaffreturnbooksComponent } from './library/transaction/staffreturnbooks/staffreturnbooks.component';
import { AllbooksComponent } from './library/viewreports/allbooks/allbooks.component';
import { BooksavailabilityComponent } from './library/viewreports/booksavailability/booksavailability.component';
import { StudentreportComponent } from './library/viewreports/studentreport/studentreport.component';
import { StaffreportComponent } from './library/viewreports/staffreport/staffreport.component';
import { BooksissuedstudentsComponent } from './library/viewreports/booksissuedstudents/booksissuedstudents.component';
import { BooksissuedstaffComponent } from './library/viewreports/booksissuedstaff/booksissuedstaff.component';
import { AllfinesComponent } from './library/viewreports/allfines/allfines.component';
import { BookanalysisComponent } from './library/viewreports/bookanalysis/bookanalysis.component';
import { OpacComponent } from './library/opac/opac.component';

@NgModule({
  declarations: [
    AppComponent,
    TitleCasePipe,
    // MainHeaderComponent,
    // MainSidenavComponent,
    // FooterComponent,
    ContentWrapperComponent,
    DepartmentComponent,
    LoginComponent,
    DashboardComponent,
    // MenuNavbarComponent,
    DropdownComponent,
    ManagecourseComponent,
    // StudenttakeassignmentComponent,
    AssignmentHeadComponent,
    ManageAssignmentComponent,
    SharecourseMaterialComponent,
    ReceivecourseMaterialComponent,
    AssignmentReviewComponent,
    ViewAnswersComponent,
    // TinymceComponent,
    StudenttakeAssignmentComponent,
    TakeAssignmentComponent,
    customDateFormatPipe,
    LessonScheduleComponent,
    ActivityComponent,
    ManageRubricsComponent,
    StudentloginComponent,
    // CharLimiterPipe,
    TakeActivityComponent,
    ReviewActivityComponent,
    KeysPipe,
    // MessageinboxComponent,
    // SentmessagesComponent,
    // ComposemessageComponent,
    // SendsmsComponent,
    // EnquirylistComponent,
    // SmssetupComponent,
    AdmissionFormComponent,
    // AccountingComponent,
    // ManageVocherComponent,
    // LedgerComponent,
    // VoucherComponent,
    // BalancesheetComponent,
    // LedgersummaryComponent,
    // RouteComponent,
    // RouteListComponent,
    // VehicleListComponent,
    // DriversListComponent,
    // TransportBillsComponent,
    // MaintenanceComponent,
    // BoardlistComponent,
    // VehicleBoardComponent,
    // DriverVehicleComponent,
    // DriverReportComponent,
    // VehicleReportComponent,
    // StudentReportComponent,
    // StaffReportComponent,
    // AddBuildingComponent,
    // AddRoomComponent,
    // HostelComponent,
    // RoomAvailabilityComponent,
    // RoomAllocationComponent,
    // ViewHostelPersonsComponent,
    // CollectItemsComponent,
    // PrepareBillComponent,
    // ViewDetailsComponent,
    // IssueItemsComponent,
    // HealthRecordComponent,
    // DeAllocateComponent,
    // ReportComponent,
    CategoryComponent,
    SubcategoryComponent,
    LibraryfineComponent,
    PublisherComponent,
    BooksComponent,
    StudentreturnbooksComponent,
    StaffreturnbooksComponent,
    AllbooksComponent,
    BooksavailabilityComponent,
    StudentreportComponent,
    StaffreportComponent,
    BooksissuedstudentsComponent,
    BooksissuedstaffComponent,
    AllfinesComponent,
    BookanalysisComponent,
    OpacComponent
   
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
    TransportModule

  ],
  providers: [
    PostService,
    // ToastService,
    AssignmentHeadComponent,
    SharecourseMaterialComponent,
    ReceivecourseMaterialComponent,
    ManagecourseComponent,
    StudenttakeAssignmentComponent,
    TakeAssignmentComponent,
    ManageAssignmentComponent,
    ActivityComponent,
    // TinymceComponent,
    AuthGuard,
    RoleGuard,
    TakeActivityComponent
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