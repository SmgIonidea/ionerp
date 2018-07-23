// import { Component, OnInit } from '@angular/core';

// @Component({
//   selector: 'app-ledger',
//   templateUrl: './ledger.component.html',
//   styleUrls: ['./ledger.component.css']
// })
// export class LedgerComponent implements OnInit {

//   constructor() { }

//   ngOnInit() {
//   }

// }


import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';

@Component({
  selector: 'app-ledger',
  templateUrl: './ledger.component.html',
  styleUrls: ['./ledger.component.css']
})
export class LedgerComponent implements OnInit {

  title;
  maintitle;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
   @Input('legId') delLegtId;; // Input binding used to place building  id in hidden text box to delete the building name. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;
  post: Array<any>;
  groups: Array<any>;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  setLedgerId: any;
  constructor(public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router) { }

  ngOnInit() {
    this.title = "Create New Ledger";
    this.maintitle = "Account Ledger";
    this.service.subUrl = 'accounting/Ledger/index';
    this.service.getData().subscribe(response => {
      this.post = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
    });
    this.service.subUrl = 'accounting/accounting/getUnderGrps';
    this.service.getData().subscribe(response => {
      this.groups = response.json();
    })
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }
  private ledgerForm = new FormGroup({
    ledger: new FormControl('', [
      Validators.required
    ]),
    grpName: new FormControl('', [

    ]),
    openingBal: new FormControl('', [

    ]),
    type: new FormControl('', [

    ]),
  });
  get ledger() {
    /* property to access the 
        formGroup Controles. which is used to validate the form */
    return this.ledgerForm.get('ledger');
  }
  get grpName() {
    return this.ledgerForm.get('grpName');
  }

  get openingBal() {
    return this.ledgerForm.get('openingBal');

  }

  get type() {
    return this.ledgerForm.get('type');

  }

  createpost(ledgerForm) {
    this.service.subUrl = 'accounting/Ledger/createLedger';
    let ledgerData = ledgerForm.value;

    let postData = {

      'ledgerData': ledgerData,

    }
    this.service.createPost(postData).subscribe(response => {
      if (response.json().status == 'ok') {

        this.service.subUrl = 'accounting/ledger/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Add sucess';
        let body = 'New ledger added successfully';
        this.toasterMsg(type, title, body);
        this.ledgerForm.reset();
      }
      else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New ledger add failed please try again'
        this.toasterMsg(type, title, body);
        this.ledgerForm.reset();
      }
    });

    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  editledger(editElement: HTMLElement) {
    this.title = "Update Account Ledger";
    let lgname = editElement.getAttribute('ledgername');
    let lggrpname = editElement.getAttribute('grpname');
    let legbal = editElement.getAttribute('ledgerbal');
    let legtype = editElement.getAttribute('ledgertype');
    let legid = editElement.getAttribute('ledgerId');
    this.ledger.setValue(lgname);
    this.grpName.setValue(lggrpname);
    this.openingBal.setValue(legbal);
    this.type.setValue(legtype);
    this.setLedgerId = legid;
    // this.grpName.setValue(accountGrp);
    this.isSaveHide = true;
    this.isUpdateHide = false;

  }
  updateLedger(ledgerpost) {
    this.service.subUrl = 'accounting/ledger/updateLedger';
    ledgerpost.stringfy
    let postData = {
      'lgname': ledgerpost.value.ledger,
      'lggrpname': ledgerpost.value.grpName,
      'lgbal': ledgerpost.value.openingBal,
      'lgtype': ledgerpost.value.type,
      'lgid': this.setLedgerId,
    }

    this.service.updatePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/ledger/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Ledger Updated Successfully.'
        this.toasterMsg(type, title, body);
        // this.accountGrpForm.reset();
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Ledger Update Failed Please Try Again.'
        this.toasterMsg(type, title, body);
        this.ledgerForm.reset();
      }
    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }
   deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let legId = deleteElement.getAttribute('ledgerId');
    // let delDeptId;
    this.delLegtId = legId;
    (<any>jQuery('#ledgerDeleteModal')).modal('show');
  }
  deleteAccntData(ledgerIdInput:HTMLInputElement){
    this.service.subUrl = 'accounting/ledger/deleteledger';
    let postData = {
      'ledgerId': ledgerIdInput.value
    };
    this.service.deletePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/ledger/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'ledger deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#ledgerDeleteModal')).modal('hide');

        this.ledgerForm.reset();
        // this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'ledger Deleted Failed Please Try Again.'
        this.toasterMsg(type, title, body);
        this.ledgerForm.reset();
      }

    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
    
  }

cancelUpdate() {
    // this.flag = 0;
    this.title = "Create New Ledger";
    this.ledgerForm.reset();
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


}
