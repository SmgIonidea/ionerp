import { Component, OnInit, ViewChild } from '@angular/core';
import * as $ from 'jquery';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { CharctersOnlyValidation } from '../../custom.validators';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';

@Component({
  selector: 'app-vehicle-list',
  templateUrl: './vehicle-list.component.html',
  styleUrls: ['./vehicle-list.component.css']
})
export class VehicleListComponent implements OnInit {
  public myDatePickerOptions1: IMyDpOptions = {
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
  public myDatePickerOptions2: IMyDpOptions = {
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
  public myDatePickerOptions3: IMyDpOptions = {
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
  constructor(private service: PostService, private toast: ToastService) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  tosterconfig;

  vehicleListData;
  typeListData;
  transportId;
  vehicleListId;

  public model: any;
  public model1: any;
  public model2: any;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  private vehicleList = new FormGroup({
    transportList: new FormControl('', [
      Validators.required
    ]),
    transportName: new FormControl('', [
      Validators.required
    ]),
    vehicleRegNumber: new FormControl('', [
      Validators.required
    ]),
    numOfPassengers: new FormControl('', [
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ]),
    purchaseDate: new FormControl('', [
      Validators.required
    ]),
    insureDate: new FormControl('', [
      Validators.required
    ]),
    insureRenewDate: new FormControl('', [
      Validators.required
    ])
  })

  get transportList() {
    return this.vehicleList.get('transportList');
  }

  get transportName() {
    return this.vehicleList.get('transportName');
  }

  get vehicleRegNumber() {
    return this.vehicleList.get('vehicleRegNumber');
  }

  get numOfPassengers() {
    return this.vehicleList.get('numOfPassengers');
  }

  get purchaseDate() {
    return this.vehicleList.get('purchaseDate');
  }

  get insureDate() {
    return this.vehicleList.get('insureDate');
  }

  get insureRenewDate() {
    return this.vehicleList.get('insureRenewDate');
  }

  ngOnInit() {

    this.maintitle = "Vehicle List";
    this.subtitle = "Add New Vehicle";
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.getVehicleDetails();

    this.service.subUrl = 'transport/Vehicle_list/getTransportTypeList';
    this.service.getData().subscribe(response => {
      this.typeListData = response.json();
    })


  }

  //function to get data on load 
  getVehicleDetails() {
    this.service.subUrl = 'transport/Vehicle_list/getVehicleDetails';
    this.service.getData().subscribe(response => {
      this.vehicleListData = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    })
  }

  //Function to save vehicle details
  saveVehicleDetails(vehicleForm) {

    this.service.subUrl = 'transport/Vehicle_list/saveVehicleDetails';

    let postdata = {
      'transport_type': vehicleForm.value.transportList,
      'transport_name': vehicleForm.value.transportName,
      'vehicle_number': vehicleForm.value.vehicleRegNumber,
      'passengers_num': vehicleForm.value.numOfPassengers,
      'purchase_date': vehicleForm.value.purchaseDate,
      'insurance_date': vehicleForm.value.insureDate,
      'ins_renew_date': vehicleForm.value.insureRenewDate
    }

    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New vehicle list added'
        this.toasterMsg(type, title, body);
        this.vehicleList.reset();
        this.getVehicleDetails();
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New vehicle list add failed please try again'
        this.toasterMsg(type, title, body);
        this.vehicleList.reset();
      }
      this.tableRerender();
      this.dtTrigger.next();
    });

  }

  updateVehicleDetails(vehicleForm) {

    this.service.subUrl = 'transport/Vehicle_list/updateVehicleDetails';

    let postdata = {
      'transportId': this.transportId,
      'transport_type': vehicleForm.value.transportList,
      'transport_name': vehicleForm.value.transportName,
      'vehicle_number': vehicleForm.value.vehicleRegNumber,
      'passengers_num': vehicleForm.value.numOfPassengers,
      'purchase_date': vehicleForm.value.purchaseDate,
      'insurance_date': vehicleForm.value.insureDate,
      'ins_renew_date': vehicleForm.value.insureRenewDate
    }

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Vehicle details updated successfully'
        this.toasterMsg(type, title, body);
        this.vehicleList.reset();
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Vehicle details update failed please try again'
        this.toasterMsg(type, title, body);
        this.vehicleList.reset();
        this.cancelUpdate();
      }
      this.getVehicleDetails();

    })

  }

  editVehicleList(editElement: HTMLElement) {

    this.subtitle = 'Edit Vehicle List';
    this.isSaveHide = true;
    this.isUpdateHide = false;

    this.transportId = editElement.getAttribute('transportid');
    let transtype = editElement.getAttribute('transtype');
    let transname = editElement.getAttribute('transname');
    let vehiclenum = editElement.getAttribute('vehiclenum');
    let seat_capacity = editElement.getAttribute('passengers');

    let purchasedate = editElement.getAttribute('purchasedate');
    let year = purchasedate.substring(0, 4);
    let month = purchasedate.substring(5, 7);
    let day = purchasedate.substring(8, 10);
    let due_day = day.replace(/^0+/, '');
    let due_month = month.replace(/^0+/, '');
    this.model = { date: { year: year, month: due_month, day: due_day } };

    let insrdate = editElement.getAttribute('insrdate');
    let year1 = insrdate.substring(0, 4);
    let month1 = insrdate.substring(5, 7);
    let day1 = insrdate.substring(8, 10);
    let due_day1 = day1.replace(/^0+/, '');
    let due_month1 = month1.replace(/^0+/, '');
    this.model1 = { date: { year: year1, month: due_month1, day: due_day1 } };


    let renewdate = editElement.getAttribute('renewaldate');
    let year2 = renewdate.substring(0, 4);
    let month2 = renewdate.substring(5, 7);
    let day2 = renewdate.substring(8, 10);
    let due_day2 = day2.replace(/^0+/, '');
    let due_month2 = month2.replace(/^0+/, '');
    this.model2 = { date: { year: year2, month: due_month2, day: due_day2 } };


    this.transportList.setValue(transtype);
    this.transportName.setValue(transname);
    this.vehicleRegNumber.setValue(vehiclenum);
    this.numOfPassengers.setValue(seat_capacity);
    this.purchaseDate.setValue(this.model);
    this.insureDate.setValue(this.model1);
    this.insureRenewDate.setValue(this.model2);



  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    this.vehicleListId = deleteElement.getAttribute('transportid');
    (<any>jQuery('#vehicleListDeleteModal')).modal('show');

  }

  deleteVehicleDetails(transportId) {

    this.service.subUrl = 'transport/Vehicle_list/deleteVehicleData';
    let deleteVehicleId = transportId;
    // alert(deleteVehicleId);

    this.service.updatePost(deleteVehicleId).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Vehicle list deleted successfully'
        this.toasterMsg(type, title, body);
        this.vehicleList.reset();
        this.getVehicleDetails();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Vehicle list failed please try again'
        this.toasterMsg(type, title, body);
        this.vehicleList.reset();
      }


    })

  }

  cancelUpdate() {

    this.maintitle = "Vehicle List";
    this.subtitle = "Create New Vehicle List";
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
