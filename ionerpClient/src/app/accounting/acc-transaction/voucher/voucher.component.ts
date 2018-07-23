// import { Component, OnInit } from '@angular/core';
// import * as $ from 'jquery';
// import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';

// @Component({
//   selector: 'app-voucher',
//   templateUrl: './voucher.component.html',
//   styleUrls: ['./voucher.component.css']
// })
// export class VoucherComponent implements OnInit {
//   public myDatePickerOptions: IMyDpOptions = {
//     // other options...
//     dateFormat: 'dd-mm-yyyy',
//     showTodayBtn: true, markCurrentDay: true,
//     disableUntil: { year: 0, month: 0, day: 0 },
//     inline: false,
//     showClearDateBtn: true,
//     selectionTxtFontSize: '12px',
//     editableDateField: false,
//     openSelectorOnInputClick: true
//   };
//   constructor() { }

//   ngOnInit() {

//   }

//   selectDiv(select_item){
//     if (select_item == "cheque" || select_item == "dd" ) {
//       // this.hiddenDiv.style.visibility='visible';
//     $('#hiddeDiv').css('display','block');
//     // Form.fileURL.focus();
//   } 
//   else{
//     // this.hiddenDiv.style.visibility='hidden';
//     $('#hiddeDiv').css('display','none');
//   }
//   }
// }
import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import * as $ from 'jquery';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';

@Component({
  selector: 'app-voucher',
  templateUrl: './voucher.component.html',
  styleUrls: ['./voucher.component.css']
})
export class VoucherComponent implements OnInit {
  private selDate: IMyDate = { year: 0, month: 0, day: 0 };
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
  public model: any;
  title;
  maintitle;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @Input('buildId') delBuildId; // Input binding used to place building  id in hidden text box to delete the building name. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;
  post: Array<any>;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  vouchertype: Array<any>;
  particular: Array<any>;
  voucherid: any;
  vouchernumber;
  @Input('vocId') delVoucherId;




  constructor(public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router) { }

  ngOnInit() {
    this.maintitle = "Voucher";
    this.title = "Create New Voucher";
    this.service.subUrl = 'accounting/Transaction/index';
    this.service.getData().subscribe(response => {
      this.post = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
    });

    this.service.subUrl = 'accounting/Transaction/getVoucherType';
    this.service.getData().subscribe(response => {
      this.vouchertype = response.json();
    })
    this.service.subUrl = 'accounting/Transaction/getparticulars';
    this.service.getData().subscribe(response => {
      this.particular = response.json();
    });

    this.service.subUrl = 'accounting/Transaction/vounchernum';
    this.service.getData().subscribe(response => {
      this.vouchernumber = response.json();
      this.vouchernum.setValue(this.vouchernumber);
    });

    this.isSaveHide = false;
    this.isUpdateHide = true;

  }
  private voucherForm = new FormGroup({
    vouchertyp: new FormControl('', [
    ]),
    vouchernum: new FormControl('', [
      Validators.required
    ]),
    admissiondate1: new FormControl('', [
      Validators.required
    ]),
    Payment: new FormControl('', [

    ]),
    narration: new FormControl('', [

    ]),
    bankname: new FormControl('', [

    ]),
    Account: new FormControl('', [

    ]),
    Cheque: new FormControl('', [

    ]),
    Teller: new FormControl('', [

    ]),
    pin: new FormControl('', [

    ]),
    particulars: new FormControl('', [

    ]),
    Amount: new FormControl('', [

    ]),
  });
  get vouchernum() {
    return this.voucherForm.get('vouchernum');
  }
  get admissiondate1() {
    return this.voucherForm.get('admissiondate1');
  }
  get vouchertyp() {
    return this.voucherForm.get('vouchertyp');
  }

  get particulars() {
    return this.voucherForm.get('particulars');
  }
  get Amount() {
    return this.voucherForm.get('Amount');
  }

  get narration() {
    return this.voucherForm.get('narration');
  }

  get Payment() {
    return this.voucherForm.get('Payment');
  }
  get pin() {
    return this.voucherForm.get('pin');
  }
  get Teller() {
    return this.voucherForm.get('Teller');
  }
  get Cheque() {
    return this.voucherForm.get('Cheque');
  }
  get Account() {
    return this.voucherForm.get('Account');
  }
  get bankname() {
    return this.voucherForm.get('bankname');
  }

  createPost(voucherForm) {
    this.service.subUrl = "accounting/Transaction/createVoucher";
    let voucherData = voucherForm.value;
    let postData = {
      'voucher': voucherData,
    }
    this.service.createPost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = "accounting/Transaction/index";
        this.service.getData().subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Add Success';
        let body = 'New account added successfully'
        this.toasterMsg(type, title, body);
        this.voucherForm.reset();
        this.service.subUrl = 'accounting/Transaction/vounchernum';
        this.service.getData().subscribe(response => {
          this.vouchernumber = response.json();
          this.vouchernum.setValue(this.vouchernumber);
        });
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New account add failed please try again'
        this.toasterMsg(type, title, body);
        this.voucherForm.reset();
        this.service.subUrl = 'accounting/Transaction/vounchernum';
        this.service.getData().subscribe(response => {
          this.vouchernumber = response.json();
          this.vouchernum.setValue(this.vouchernumber);
        });
      }
      $('html,body').animate({ scrollTop: 0 }, 'slow');
    });
  }
  editaccount(editElement: HTMLElement) {
    this.title = "Update Voucher";
    let paymentmode = editElement.getAttribute('paymentmode');

    if (paymentmode == "cheque" || paymentmode == "dd") {
      $('#hiddeDiv').css('display', 'block');
    }
    else {
      // this.hiddenDiv.style.visibility='hidden';
      $('#hiddeDiv').css('display', 'none');
    }
    let type = editElement.getAttribute('vouchertype')
    let voucherid = editElement.getAttribute('voucherid');
    let voucherdate = editElement.getAttribute('receiptDate');

    let narration = editElement.getAttribute('narration');
    let particulars = editElement.getAttribute('particular');
    let amount = editElement.getAttribute('amount');
    let pin = editElement.getAttribute('pin');
    let tellernum = editElement.getAttribute('tellernum');
    let bankname = editElement.getAttribute('bankname');
    let checkno = editElement.getAttribute('checkno');
    let bankacc = editElement.getAttribute('bankacc');

    let year = voucherdate.substring(0, 4);
    let month = voucherdate.substring(5, 7);
    let day = voucherdate.substring(8, 10);
    let initial_day = day.replace(/^0+/, '');
    let initial_month = month.replace(/^0+/, '');
    this.model = { date: { year: year, month: initial_month, day: initial_day } };

    this.vouchertyp.setValue(type);
    this.vouchernum.setValue(voucherid);
    // this.admissiondate1.setValue(voucherdate);
    this.Payment.setValue(paymentmode);
    this.narration.setValue(narration);
    this.particulars.setValue(particulars);
    this.Amount.setValue(amount);
    this.pin.setValue(pin);
    this.Teller.setValue(tellernum);
    this.bankname.setValue(bankname);
    this.Cheque.setValue(checkno);
    this.Account.setValue(bankacc);
    this.voucherid = voucherid;

    this.isSaveHide = true;
    this.isUpdateHide = false;
  }
  updatevoucher(voucherpost) {
    this.service.subUrl = "accounting/Transaction/updateVoucher"
    voucherpost.stringify

    let postData = {
      'vouchertype': voucherpost.value.vouchertyp,
      'voucherdate': voucherpost.value.admissiondate1,
      'payment': voucherpost.value.Payment,
      'narration': voucherpost.value.narration,
      'particulars': voucherpost.value.particulars,
      'amount': voucherpost.value.Amount,
      'pin': voucherpost.value.pin,
      'teller': voucherpost.value.Teller,
      'bankname': voucherpost.value.bankname,
      'cheque': voucherpost.value.Cheque,
      'account': voucherpost.value.Account,
      'voucherid': this.voucherid,
    }

    this.service.updatePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/Transaction/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Voucher Updated Successfully.'
        this.toasterMsg(type, title, body);
        // this.accountGrpForm.reset();
        this.cancelUpdate();

      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Voucher Update Failed Please Try Again.'
        this.toasterMsg(type, title, body);
        // this.voucherForm.reset();

      }

    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let voucherId = deleteElement.getAttribute('voucherId');
    // let delDeptId;
    this.delVoucherId = voucherId;
    (<any>jQuery('#voucherDeleteModal')).modal('show');
  }

  deleteVoucherData(voucherIdInput: HTMLInputElement) {

    this.service.subUrl = 'accounting/Transaction/deleteVoucher';
    let postData = {
      'voucherId': voucherIdInput.value
    };
    this.service.deletePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/Transaction/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Voucher deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#voucherDeleteModal')).modal('hide');
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Voucher Delete Failed Please Try Again.'
        this.toasterMsg(type, title, body);
        this.cancelUpdate();
      }

    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
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
  tableRerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
    });
  }
  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

  // to get success msg on particular add,edit,delete functionality
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
  onDateChanged(event: IMyDateModel) {
    if (event.formatted == "") {
      this.myDatePickerOptions.disableUntil.day = 0;
      this.myDatePickerOptions.disableUntil.month = 0;
      this.myDatePickerOptions.disableUntil.year = 0;
      // this.selDate = event.date;
    } else {

      this.myDatePickerOptions.disableUntil.day = event.date.day - 1;
      this.myDatePickerOptions.disableUntil.month = event.date.month;
      this.myDatePickerOptions.disableUntil.year = event.date.year;
      this.selDate = event.date;
    }
  }
  cancelUpdate() {
    // this.flag = 0;
    this.title = "Create New Voucher";
    $('#hiddeDiv').css('display', 'none');
    this.voucherForm.reset();
    this.service.subUrl = 'accounting/Transaction/vounchernum';
    this.service.getData().subscribe(response => {
      this.vouchernumber = response.json();
      this.vouchernum.setValue(this.vouchernumber);
    });
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }
  cancelReset() {
    this.voucherForm.reset();
    this.service.subUrl = 'accounting/Transaction/vounchernum';
    this.service.getData().subscribe(response => {
      this.vouchernumber = response.json();
      this.vouchernum.setValue(this.vouchernumber);
    });
  }


}

