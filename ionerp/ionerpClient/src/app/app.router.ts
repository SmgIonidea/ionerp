import { AssignmentHeadComponent } from './instructor/assignment-head/assignment-head.component';
import { ContentWrapperComponent } from './content-wrapper/content-wrapper.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DepartmentComponent } from './department/department.component';
import { LoginComponent } from './login/login.component';
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ManagecourseComponent } from './managecourse/managecourse.component';
import { ManageAssignmentComponent } from './instructor/manage-assignment/manage-assignment.component';
import { SharecourseMaterialComponent } from './instructor/sharecourse-material/sharecourse-material.component';
import { ReceivecourseMaterialComponent } from './student/receivecourse-material/receivecourse-material.component';
import { AssignmentReviewComponent } from './instructor/assignment-review/assignment-review.component';
import { ViewAnswersComponent } from './instructor/view-answers/view-answers.component';
import { StudenttakeAssignmentComponent } from './student/studenttake-assignment/studenttake-assignment.component';
import { TakeAssignmentComponent } from './student/take-assignment/take-assignment.component';
import { DropdownComponent } from './dropdown/dropdown.component';
import { LessonScheduleComponent } from './instructor/lesson-schedule/lesson-schedule.component';
import { ManageRubricsComponent } from "./faculty/manage-rubrics/manage-rubrics.component";
import { StudentloginComponent } from './studentlogin/studentlogin.component';
import { AuthGuard } from './guards/auth.guard';
import { RoleGuard } from "./guards/role.guard";
import { ReviewActivityComponent } from "./faculty/review-activity/review-activity.component";
import { ActivityComponent } from './faculty/activity/activity.component';
import { TakeActivityComponent } from './student/take-activity/take-activity.component';
import { MessageinboxComponent } from './Message/messageinbox/messageinbox.component';
import { SentmessagesComponent } from './Message/sentmessages/sentmessages.component';
import { ComposemessageComponent } from './Message/composemessage/composemessage.component';
import { SendsmsComponent } from './SMS/sendsms/sendsms.component';
import { EnquirylistComponent } from './SMS/enquirylist/enquirylist.component';
import { SmssetupComponent } from './SMS/smssetup/smssetup.component';
import { AdmissionFormComponent } from './admission-form/admission-form.component';
import { LedgerComponent } from './accounting/ledger/ledger.component';
import { VoucherComponent } from './accounting/acc-transaction/voucher/voucher.component';
import { BalancesheetComponent } from './accounting/acc-reports/balancesheet/balancesheet.component';
import { LedgersummaryComponent } from './accounting/acc-reports/ledgersummary/ledgersummary.component';
import {AccountingGroupComponent} from './accounting/accounting-group/accounting-group.component';
import { ManageVocherComponent } from './accounting/manage-vocher/manage-vocher.component';
import { RouteComponent } from './transport/route/route.component';
import { RouteListComponent } from './transport/route-list/route-list.component';
import { VehicleListComponent } from './transport/vehicle-list/vehicle-list.component';
import { DriversListComponent } from './transport/drivers-list/drivers-list.component';
import { TransportBillsComponent } from './transport/transport-bills/transport-bills.component';
import { MaintenanceComponent } from './transport/maintenance/maintenance.component';
import { BoardlistComponent } from './/transport/boardlist/boardlist.component';
import { VehicleBoardComponent } from './transport/vehicle-board/vehicle-board.component';
import { DriverVehicleComponent } from './transport/driver-vehicle/driver-vehicle.component';
import { DriverReportComponent } from './transport/transport-reports/driver-report/driver-report.component';
import { VehicleReportComponent } from './transport/transport-reports/vehicle-report/vehicle-report.component';
import { StudentReportComponent } from './transport/transport-reports/student-report/student-report.component';
import { StaffReportComponent } from './transport/transport-reports/staff-report/staff-report.component';
import { HostelComponent } from './hostel/hostel.component';
import { RoomAvailabilityComponent } from './hostel/room-availability/room-availability.component';
import { RoomAllocationComponent } from './hostel/room-allocation/room-allocation.component';
import { ViewHostelPersonsComponent } from './hostel/view-hostel-persons/view-hostel-persons.component';
import { CollectItemsComponent } from './hostel/collect-items/collect-items.component';
import { PrepareBillComponent } from './hostel/prepare-bill/prepare-bill.component';
import { ViewDetailsComponent } from './hostel/view-details/view-details.component';
import { IssueItemsComponent } from './hostel/issue-items/issue-items.component';
import { HealthRecordComponent } from './hostel/health-record/health-record.component';
import { DeAllocateComponent } from './hostel/de-allocate/de-allocate.component';
import { ReportComponent } from './hostel/report/report.component';
import { AddBuildingComponent } from './hostel/add-building/add-building.component';
import { AddRoomComponent } from './hostel/add-room/add-room.component';
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
const route: Routes = [
  {
    path: '',
    component: ContentWrapperComponent,
    pathMatch: 'full'
  },
  {
    path: 'studentlogin',
    component: StudentloginComponent,
    pathMatch: 'full'
  },

  {
    path: 'content',
    component: ContentWrapperComponent,
    // canActivate: [AuthGuard],
    children: [
      {
        path: '',
        component: DashboardComponent,
        pathMatch: 'full',
        outlet: 'appCommon'
      },
      {
        path: 'department',
        component: DepartmentComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        // data: {title: 'DEPARTMENT LIST'},
        outlet: 'appCommon',
      },
      {
        path: 'assignment_head/manageassignment',
        component: ManageAssignmentComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        //data: { title: 'Add/Edit Assignment' },
        outlet: 'appCommon',
      },
      {
        path: 'assignment_head/assignmentreview',
        component: AssignmentReviewComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        //data: { title: 'Review Assignment List' },
        outlet: 'appCommon',
      },
      {
        path: 'assignment_head/assignmentreview/viewassignment',
        component: ViewAnswersComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        //data: { title: 'Review answers' },
        outlet: 'appCommon',
      },
      {
        path: 'studenttakeassignment/takeassignment',
        component: TakeAssignmentComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:false,isChairman:false,isProgramOwner:false,isCourseOwner:false,isStudent:true },
        // data: { title: 'Take Assignment' },
        outlet: 'appCommon',
      },
      {
        path: 'managecourse',
        component: ManagecourseComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'Manage Course Instructor',isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'assignment_head',
        component: AssignmentHeadComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'Assignment List',isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'studenttakeassignment',
        component: StudenttakeAssignmentComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'My Assignment List', isAdmin:false,isChairman:false,isProgramOwner:false,isCourseOwner:false,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'sharecoursematerial',
        component: SharecourseMaterialComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'Share Course Materials List', isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'lesson-schedule',
        component: LessonScheduleComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      
      {
        path: 'receivecoursematerial',
        component: ReceivecourseMaterialComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'Shared Course Materials', isAdmin:false,isChairman:false,isProgramOwner:false,isCourseOwner:false,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'activity/managerubricsdefinition',
        component: ManageRubricsComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        // data: { title: 'Manage Rubrics' },
        outlet: 'appCommon',
      },
      {
        path: 'activity/reviewactivity',
        component: ReviewActivityComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        // data: { title: 'Manage Rubrics' },
        outlet: 'appCommon',
      },
      {
        path: 'activity',
        component: ActivityComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'Activity List', isAdmin:true, isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'takeactivity',
        component: TakeActivityComponent,
        pathMatch: 'full',
        canActivate: [RoleGuard],
        data: { title: 'My Activity List', isAdmin:false,isChairman:false,isProgramOwner:false,isCourseOwner:false,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'messageinbox',
        component: MessageinboxComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'sentmessages',
        component: SentmessagesComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'composemessage',
        component: ComposemessageComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'sendsms',
        component: SendsmsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'enquirylist',
        component: EnquirylistComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      }, 
      {
        path: 'smssetup',
        component: SmssetupComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'AdmissionForm',
        component: AdmissionFormComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },    
      {
        path: 'accountingGroup',
        component: AccountingGroupComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'manageVocher',
        component: ManageVocherComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'ledger',
        component: LedgerComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'voucher',
        component: VoucherComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'balance',
        component: BalancesheetComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
     
      {
        path: 'ledgersummary',
        component: LedgersummaryComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'route',
        component: RouteComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'routeList',
        component: RouteListComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'vehicleList',
        component: VehicleListComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'driversList',
        component: DriversListComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'transportBills',
        component: TransportBillsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'maintenanceDetails',
        component: MaintenanceComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'boardList',
        component: BoardlistComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'AllotVehicletoBoard',
        component: VehicleBoardComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'DriverVehicleComponent',
        component: DriverVehicleComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'driverReport',
        component: DriverReportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'vehicleReport',
        component: VehicleReportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'studentReport',
        component: StudentReportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin: true, isChairman: true, isProgramOwner: true, isCourseOwner: true, isStudent: false },
        outlet: 'appCommon',
      },
      {
        path: 'staffReport',
        component: StaffReportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin: true, isChairman: true, isProgramOwner: true, isCourseOwner: true, isStudent: false },
        outlet: 'appCommon',
      },
      {
        path: 'hostel',
        component: HostelComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'addbuilding',
        component: AddBuildingComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'addroom',
        component: AddRoomComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'roomavailability',
        component: RoomAvailabilityComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'roomallocation',
        component: RoomAllocationComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'viewhostelpersons',
        component: ViewHostelPersonsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'collectitems',
        component: CollectItemsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'preparebill',
        component: PrepareBillComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'viewdetails',
        component: ViewDetailsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'issueitems',
        component: IssueItemsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'healthrecord',
        component: HealthRecordComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'deallocate',
        component: DeAllocateComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'report',
        component: ReportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:false },
        outlet: 'appCommon',
      },
      {
        path: 'category',
        component: CategoryComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
        {
        path: 'subcategory',
        component: SubcategoryComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'libraryfine',
        component: LibraryfineComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'publisher',
        component: PublisherComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'books',
        component: BooksComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      }, 
      {
        path: 'studentreturn',
        component: StudentreturnbooksComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'staffreturn',
        component: StaffreturnbooksComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
        {
        path: 'allbooks',
        component: AllbooksComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'booksavailability',
        component: BooksavailabilityComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
          {
        path: 'studentreport',
        component: StudentreportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
       {
        path: 'staffreport',
        component: StaffreportComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'bookissuedstudent',
        component: BooksissuedstudentsComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
           {
        path: 'bookissuedstaff',
        component: BooksissuedstaffComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
             {
        path: 'allfines',
        component: AllfinesComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: { isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
               {
        path: 'bookanalysis',
        component: BookanalysisComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {  isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      },
      {
        path: 'opac',
        component: OpacComponent,
        pathMatch: 'full',
        // canActivate: [RoleGuard],
        // data: {isAdmin:true,isChairman:true,isProgramOwner:true,isCourseOwner:true,isStudent:true },
        outlet: 'appCommon',
      }
                  
    ]
  },

];
@NgModule({
  imports: [
    RouterModule.forRoot(route)
  ],
  exports: [RouterModule],
})
export class AppRouter {

}