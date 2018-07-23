import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { CharctersOnlyValidation } from '../../custom.validators';
import { PostService } from '../../services/post.service';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';

@Component({
  selector: 'app-drivers-list',
  templateUrl: './drivers-list.component.html',
  styleUrls: ['./drivers-list.component.css']
})
export class DriversListComponent implements OnInit {
  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };
  constructor(private service: PostService, private toast: ToastService, private http: Http, ) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  tosterconfig;
  public model: any;

  driverDetails;
  transDriverId;
  transDriverDelId;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  @ViewChild('fileInput') inputEl: ElementRef; //file upload

  private driverList = new FormGroup({
    driverName: new FormControl('', [
      Validators.required
    ]),
    driverAddress: new FormControl('', [
      Validators.required
    ]),
    driverMobileNumber: new FormControl('', [
      Validators.required,
      // CharctersOnlyValidation.DigitsOnlyMobileNumber
    ]),
    drivingLicense: new FormControl('', [
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ]),
    IssueAuthority: new FormControl('', [
      Validators.required
    ]),
    DLValidUpto: new FormControl('', [
      Validators.required
    ]),
    LicenseDoc: new FormControl('', [
      Validators.required
    ])
  })

  get driverName() {
    return this.driverList.get('driverName');
  }

  get driverAddress() {
    return this.driverList.get('driverAddress');
  }

  get driverMobileNumber() {
    return this.driverList.get('driverMobileNumber');
  }

  get drivingLicense() {
    return this.driverList.get('drivingLicense');
  }

  get IssueAuthority() {
    return this.driverList.get('IssueAuthority');
  }

  get DLValidUpto() {
    return this.driverList.get('DLValidUpto');
  }

  get LicenseDoc() {
    return this.driverList.get('LicenseDoc');
  }

  getFileName(replaceElement: HTMLElement, splitElement: HTMLElement) {
    var value = JSON.stringify($('#userdoc').val());

    //get filepath replace forward slash with backward slash
    var filePath = value.replace(/\\/g, "/");
    //remove backward slash and "

    var path = filePath.split('/').pop();
    path = path.replace('"', '');
    //get the fileName

    var fileName = JSON.stringify($('#addDocFiles').val(path));
    this.LicenseDoc.setValue(path);
  }

  ngOnInit() {

    this.maintitle = "Drivers List";
    this.subtitle = "Add Driver Details";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.service.subUrl = 'transport/Driver_list/getDriverDetails';
    this.service.getData().subscribe(response => {
      this.driverDetails = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    })
  }

  saveDriverDetails(driverForm) {
    let driver_name = driverForm.value.driverName;
    let driver_license = driverForm.value.drivingLicense;
    this.service.subUrl = 'transport/Driver_list/saveDriverDetails';

    let postdata = {
      'driverName': driver_name,
      'driverAddress': driverForm.value.driverAddress,
      'mobile': driverForm.value.driverMobileNumber,
      'drivingLicense': driver_license,
      'issueAuth': driverForm.value.IssueAuthority,
      'DLvalidupto': driverForm.value.DLValidUpto,
      'licenseDocument': driverForm.value.LicenseDoc
    }

    this.service.createPost(postdata).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'Driver details added successfully'
        this.toasterMsg(type, title, body);
        this.driverList.reset();
        this.service.subUrl = 'transport/Driver_list/getDriverDetails';
        this.service.getData().subscribe(response => {
          this.driverDetails = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })

        let inputEl: HTMLInputElement = this.inputEl.nativeElement;

        //get the total amount of files attached to the file input.
        let fileCount: number = inputEl.files.length;
        if (fileCount > 0) {

          let file: File = fileCount[0];

          let formData: FormData = new FormData();
          formData.append('userdoc', inputEl.files.item(0));
          console.log(inputEl.files.item(0));
          let headers = new Headers();
          // No need to include Content-Type in Angular 4 /
          headers.append('Content-Type', 'multipart/form-data');
          headers.append('Accept', 'application/json');

          this.http.post(this.service.baseUrl + 'transport/Driver_list/upload'+ '/' + driver_name + '/' + driver_license, formData)
          .subscribe(response => {
            this.service.subUrl = 'transport/Driver_list/getDriverDetails';
            this.service.getData().subscribe(response => {
              this.driverDetails = response.json();
              this.tableRerender();
              this.dtTrigger.next(); // Calling the DT trigger to manually render the table  
              // this.getdropdowndata();
            });

          })
          // formData.append('userdoc', inputEl.files.item(0));              
        }
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Driver details add failed please try again'
        this.toasterMsg(type, title, body);
        this.driverList.reset();
        this.service.subUrl = 'transport/Driver_list/getDriverDetails';
        this.service.getData().subscribe(response => {
          this.driverDetails = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })
      }
    })
  }

  editDriverList(editElement: HTMLElement) {

    this.subtitle = "Edit Driver Details";
    this.isSaveHide = true;
    this.isUpdateHide = false;

    this.transDriverId = editElement.getAttribute('transDriverId');
    let driverName = editElement.getAttribute('driverName');
    let driverAddress = editElement.getAttribute('driverAddress');
    let mobileNumber = editElement.getAttribute('mobile');
    let drivingLicense = editElement.getAttribute('DL');
    let issueAuthority = editElement.getAttribute('authority');

    let DLvalidDate = editElement.getAttribute('DLvalid');
    let year2 = DLvalidDate.substring(0, 4);
    let month2 = DLvalidDate.substring(5, 7);
    let day2 = DLvalidDate.substring(8, 10);
    let due_day2 = day2.replace(/^0+/, '');
    let due_month2 = month2.replace(/^0+/, '');
    this.model = { date: { year: year2, month: due_month2, day: due_day2 } };


    let licenseDocument = editElement.getAttribute('licenseDoc');

    this.driverName.setValue(driverName);
    this.driverAddress.setValue(driverAddress);
    this.driverMobileNumber.setValue(mobileNumber);
    this.drivingLicense.setValue(drivingLicense);
    this.IssueAuthority.setValue(issueAuthority);
    this.DLValidUpto.setValue(this.model);
    this.LicenseDoc.setValue(licenseDocument);

  }

  updateDriverDetails(driverForm) {
    let driver_name = driverForm.value.driverName
    let driver_license = driverForm.value.drivingLicense;
    this.service.subUrl = 'transport/Driver_list/updateDriverDetails';
    let postdata = {
      'id': this.transDriverId,
      'driverName': driver_name,
      'driverAddress': driverForm.value.driverAddress,
      'mobile': driverForm.value.driverMobileNumber,
      'drivingLicense': driver_license,
      'issueAuth': driverForm.value.IssueAuthority,
      'DLvalidupto': driverForm.value.DLValidUpto,
      'licenseDocument': driverForm.value.LicenseDoc
    }

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Vehicle details updated successfully'
        this.toasterMsg(type, title, body);
        this.driverList.reset();
        this.cancelUpdate();

        let inputEl: HTMLInputElement = this.inputEl.nativeElement;

        //get the total amount of files attached to the file input.
        let fileCount: number = inputEl.files.length;
        if (fileCount > 0) {

          let file: File = fileCount[0];

          let formData: FormData = new FormData();
          formData.append('userdoc', inputEl.files.item(0));
          console.log(inputEl.files.item(0));
          let headers = new Headers();
          // No need to include Content-Type in Angular 4 /
          headers.append('Content-Type', 'multipart/form-data');
          headers.append('Accept', 'application/json');

          this.http.post(this.service.baseUrl + 'transport/Driver_list/uploadUpdate/'+ this.transDriverId + '/' + driver_name+ '/'+ driver_license, formData)
          .subscribe(response => {
            this.service.subUrl = 'transport/Driver_list/getDriverDetails';
            this.service.getData().subscribe(response => {
              this.driverDetails = response.json();
              this.tableRerender();
              this.dtTrigger.next(); // Calling the DT trigger to manually render the table  
              // this.getdropdowndata();
            });

          })
          // formData.append('userdoc', inputEl.files.item(0));              
        }
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Vehicle details update failed please try again'
        this.toasterMsg(type, title, body);
        this.driverList.reset();
        this.cancelUpdate();
      }
      this.service.subUrl = 'transport/Driver_list/getDriverDetails';
      this.service.getData().subscribe(response => {
        this.driverDetails = response.json();
        this.tableRerender();
        this.dtTrigger.next();
      })

    })

  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    this.transDriverDelId = deleteElement.getAttribute('transDriverId');
    (<any>jQuery('#driverListDeleteModal')).modal('show');

  }

  deleteDriverDetails(transDriverId) {

    this.service.subUrl = 'transport/Driver_list/deleteDriverDetails';
    let deleteDriverId = transDriverId;

    this.service.updatePost(deleteDriverId).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Driver list deleted successfully'
        this.toasterMsg(type, title, body);
        this.driverList.reset();

      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Driver list failed please try again'
        this.toasterMsg(type, title, body);
        this.driverList.reset();
      }
      this.service.subUrl = 'transport/Driver_list/getDriverDetails';
      this.service.getData().subscribe(response => {
        this.driverDetails = response.json();
        this.tableRerender();
        this.dtTrigger.next();
      })

    })

  }

  cancelUpdate() {
    this.maintitle = "Drivers List";
    this.subtitle = "Add Driver Details";
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  tableRerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
    });
  }
  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  toasterMsg(type, title, body) {
    this.toast.toastType = type;
    this.toast.toastTitle = title;
    this.toast.toastBody = body;
    this.tosterconfig = new ToasterConfig({
      positionClass: 'toast-bottom-right',
      tapToDismiss: false,
      showCloseButton: true,
      animation: 'slideDown'
    });
    this.toast.toastMsg;
  }

}
