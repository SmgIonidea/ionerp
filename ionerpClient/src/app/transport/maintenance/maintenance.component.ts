import { element } from 'protractor';
import { forEach } from '@angular/router/src/utils/collection';
import { Component, OnInit, TemplateRef, ViewChild, Input, Injectable } from '@angular/core';
import * as $ from 'jquery';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { CharctersOnlyValidation } from '../../custom.validators';
import { PostService } from './../../services/post.service';
import { Title } from '@angular/platform-browser';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from '../../common/toast.service';

@Component({
  selector: 'app-maintenance',
  templateUrl: './maintenance.component.html',
  styleUrls: ['./maintenance.component.css']
})
export class MaintenanceComponent implements OnInit {
  maintainProceed: Array<any>;
  maintainanceList;
  busList;
  VoucherList;
  ledgerList;
  maintainanceDetails;
  model;
  voucherDetails;
  tosterconfig;
  isUpdateHide: boolean;
  isSaveHide: boolean;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

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
  constructor(private service: PostService,
    public titleService: Title,
    private toast: ToastService) { }

  ngOnInit() {
    this.titleService.setTitle('MaintenanceDetails');
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.service.subUrl = 'transport/MaintenanceDetails/getMaintenanceList';
    this.service.getData().subscribe(response => {
      this.maintainanceList = response.json();
      this.tableRerender();
      this.dtTrigger.next();

    });

    this.service.subUrl = 'transport/MaintenanceDetails/getBusList'
    this.service.getData().subscribe(response => {
      this.busList = response.json();
    });

    this.service.subUrl = 'transport/MaintenanceDetails/getVoucherList'
    this.service.getData().subscribe(response => {
      this.VoucherList = response.json();
    });

    this.service.subUrl = 'transport/MaintenanceDetails/getLedgerList'
    this.service.getData().subscribe(response => {
      this.ledgerList = response.json();
    });


  }

  selectDiv(select_item) {
    if (select_item == "cheque" || select_item == "dd") {
      // this.hiddenDiv.style.visibility='visible';
      $('#hiddeDiv').css('display', 'block');
      // Form.fileURL.focus();
    }
    else {
      // this.hiddenDiv.style.visibility='hidden';
      $('#hiddeDiv').css('display', 'none');
    }
  }



  private maintenancedetailForm = new FormGroup({
    regno: new FormControl('', [
      Validators.required
    ]),
    maintenanceType: new FormControl('', [
      Validators.required
    ]),
    maintenanceDate: new FormControl('', [
      Validators.required
    ]),
    amountPaid: new FormControl('', [
      Validators.required
    ]),
    remarks: new FormControl('', [
      Validators.required
    ]),
    payMode: new FormControl('', [
      Validators.required
    ]),
    voucherType: new FormControl('', [
      Validators.required
    ]),
    ledgerType: new FormControl('', [
      Validators.required
    ]),
    voucherEntry: new FormControl('', [

    ]),
    maintainanceID: new FormControl('', [

    ]),
  });
  get regno() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.maintenancedetailForm.get('regno');
  }
  get maintenanceType() {
    return this.maintenancedetailForm.get('maintenanceType');
  }
  get maintenanceDate() {
    return this.maintenancedetailForm.get('maintenanceDate');
  }
  get amountPaid() {
    return this.maintenancedetailForm.get('amountPaid');
  }
  get remarks() {
    return this.maintenancedetailForm.get('remarks');
  }
  get payMode() {
    return this.maintenancedetailForm.get('payMode');
  }
  get voucherType() {
    return this.maintenancedetailForm.get('voucherType');
  }
  get ledgerType() {
    return this.maintenancedetailForm.get('ledgerType');
  }
  get voucherEntry() {
    return this.maintenancedetailForm.get('voucherEntry');
  }
  get maintainanceID() {
    return this.maintenancedetailForm.get('maintainanceID');
  }



  createpost(maintenancedetailForm) {
    let postData = {
      'registration_num': maintenancedetailForm.value.regno,
      'maintenance_type': maintenancedetailForm.value.maintenanceType,
      'maintenance_date': maintenancedetailForm.value.maintenanceDate,
      'amount_paid': maintenancedetailForm.value.amountPaid,
      'remarks': maintenancedetailForm.value.remarks,
      'payment_mode': maintenancedetailForm.value.payMode,
      'voucher_type': maintenancedetailForm.value.voucherType,
      'ledger_type': maintenancedetailForm.value.ledgerType,
    };

    this.service.subUrl = 'transport/MaintenanceDetails/getMaintenanceStatus';
    this.service.createPost(postData).subscribe(response => {
      this.maintainProceed = response.json();
      if (response.json() == true) {
        this.service.subUrl = 'transport/MaintenanceDetails/getMaintenanceList';
        this.service.getData().subscribe(response => {
          this.maintainanceList = response.json();
          this.tableRerender();
          this.dtTrigger.next();

        });
        let type = 'success';
        let title = 'Create Success';
        let body = 'maintenance details created successfully'
        this.toasterMsg(type, title, body);
        this.maintenancedetailForm.reset();
      }
      else {
        let type = 'error';
        let title = 'Create Fail';
        let body = ''
        this.toasterMsg(type, title, body);
        this.maintenancedetailForm.reset();
      }

    });
  }

  editMaintenance(editElement: HTMLElement) {

    let voucherEntryId = editElement.getAttribute('voucherEntryId');
    this.service.subUrl = 'transport/MaintenanceDetails/getVoucherDeatils';
    this.service.createPost(voucherEntryId).subscribe(response => {
      this.voucherDetails = response.json();
      this.voucherDetails.forEach(element => {
        this.payMode.setValue(element.es_paymentmode)
        this.amountPaid.setValue(element.es_amount)
        if (element.es_paymentmode == "cheque" || element.es_paymentmode == "dd") {
          // this.hiddenDiv.style.visibility='visible';
          $('#hiddeDiv').css('display', 'block');
          // Form.fileURL.focus();
        }
        else {
          // this.hiddenDiv.style.visibility='hidden';
          $('#hiddeDiv').css('display', 'none');
        }
      })
    });
    let maintainanceId = editElement.getAttribute('maintainanceId');
    let transportId = editElement.getAttribute('transportId');
    let maintenanceType = editElement.getAttribute('maintenance_type');
    let date = editElement.getAttribute('maintenanceDate');
    let remarks = editElement.getAttribute('tr_remarks');
    let ledgerId = editElement.getAttribute('ledgerId');
    let voucherId = editElement.getAttribute('voucherId');
    let year = date.substring(0, 4);
    let month = date.substring(5, 7);
    let day = date.substring(8, 10);
    let initial_day = day.replace(/^0+/, '');
    let initial_month = month.replace(/^0+/, '');
    this.model = { date: { year: year, month: initial_month, day: initial_day } };
    this.voucherEntry.setValue(voucherEntryId)
    this.regno.setValue(transportId)
    this.maintenanceType.setValue(maintenanceType)
    this.maintenanceDate.setValue(this.model)
    this.remarks.setValue(remarks)
    this.voucherType.setValue(voucherId)
    this.ledgerType.setValue(ledgerId);
    this.maintainanceID.setValue(maintainanceId)
    this.isSaveHide = true;
    this.isUpdateHide = false;


  }


  editForm(formValue) {
    this.service.subUrl = 'transport/MaintenanceDetails/editMaintenance';
    this.service.createPost(formValue.value).subscribe(response => {
      if (response.json() == 1) {
        this.service.subUrl = 'transport/MaintenanceDetails/getMaintenanceList';
        this.service.getData().subscribe(response => {
          this.maintainanceList = response.json();
          this.tableRerender();
          this.dtTrigger.next();
          this.maintenancedetailForm.reset();

        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'maintenance details updated successfully'
        this.toasterMsg(type, title, body);
      }
      else {
        let type = 'error';
        let title = 'Update Fail';
        let body = ''
        this.toasterMsg(type, title, body);
      }

    });


  }

  maintenancedelId;
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    let Id = deleteElement.getAttribute('maintainanceId');

    let delAssignmentId;
    this.maintenancedelId = Id;
    (<any>jQuery('#maintenanceDeleteModal')).modal('show');


  }

  deleteMaintenanceData(ID) {

    this.service.subUrl = 'transport/MaintenanceDetails/delMaintenance';
    this.service.createPost(ID).subscribe(response => {

      if (response.json() == 1) {
        (<any>jQuery('#maintenanceDeleteModal')).modal('hide');
        this.service.subUrl = 'transport/MaintenanceDetails/getMaintenanceList';
        this.service.getData().subscribe(response => {
          this.maintainanceList = response.json();


          this.tableRerender();
          this.dtTrigger.next();
          this.maintenancedetailForm.reset();

        });

        let type = 'success';
        let title = 'Delete Success';
        let body = 'maintenance details deleted successfully'
        this.toasterMsg(type, title, body);
        // this.clearform()
      }
      else {
        let type = 'error';
        let title = 'delete Fail';
        let body = ''
        this.toasterMsg(type, title, body);
      }

    });

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
      animation: 'fade'
      // animation: 'slideDown'
    });
    this.toast.toastMsg;

  }

}
