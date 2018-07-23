// import { Component, OnInit } from '@angular/core';
// import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
// import { PostService } from './../../services/post.service';
// import { Title } from '@angular/platform-browser';
// import { DataTableDirective } from "angular-datatables";

// @Component({
//   selector: 'app-accounting',
//   templateUrl: './accounting-group.component.html',
//   styleUrls: ['./accounting-group.component.css']
// })
// export class AccountingGroupComponent implements OnInit {

//   constructor(
//     public titleService: Title,
//     public service: PostService,
//     // private toast: ToastService,
//     private activatedRoute: ActivatedRoute,
//     private router: Router,)  {

//    }

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
// import { Http, Response } from '@angular/http';
import { Http, Response, Headers, RequestOptions } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';


@Component({
  selector: 'app-accounting',
  templateUrl: './accounting-group.component.html',
  styleUrls: ['./accounting-group.component.css']
})
export class AccountingGroupComponent implements OnInit {
  maintitle;
  title;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  isSaveHide: boolean;
  isUpdateHide: boolean;
  setAccountId: any;
  @Input('accId') delAccntId;
  // Input binding used to place building  id in hidden text box to delete the building name. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;
  post: Array<any> = [];
  groups: Array<any>;


  constructor(
    public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router) {

  }



  ngOnInit() {
    this.maintitle = "Account Groups";
    this.title = "Create Account Groups";

    this.service.subUrl = 'accounting/accounting/getUnderGrps';
    this.service.getData().subscribe(response => {
      this.groups = response.json();
    });

    this.service.subUrl = "accounting/accounting/index";
    this.service.getData().subscribe(response => {
      this.post = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });

    this.isSaveHide = false;
    this.isUpdateHide = true;

  }
  private accountGrpForm = new FormGroup({
    grpName: new FormControl('', [
      Validators.required
    ]),
    underGrp: new FormControl('', [
      Validators.required
    ]),
  });
  get grpName() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.accountGrpForm.get('grpName');
  }
  get underGrp() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.accountGrpForm.get('underGrp');
  }
  createPost(accountGrpForm) {
    this.service.subUrl = 'accounting/accounting/createAccount';
    let accountData = accountGrpForm.value; // Text Field/Form Data in Json Format
    let postData = {

      'accountData': accountData,
      // 'user_id': this.user_id,
    };
    this.service.createPost(postData).subscribe(response => {

      if (response.json().status == 'ok') {

        this.service.subUrl = 'accounting/accounting/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json()
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });

        let type = 'success';
        let title = 'Add Success';
        let body = 'New account added successfully'
        this.toasterMsg(type, title, body);

        this.accountGrpForm.reset();
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New account add failed please try again'
        this.toasterMsg(type, title, body);
        this.accountGrpForm.reset();
      }
    });

    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }
  editaccount(editElement: HTMLElement) {
    this.title = "Update Account Groups";
    let accountGrp = editElement.getAttribute('accountgrp');
    let accountUnderGrp = editElement.getAttribute('accountundergrp');
    let accountId = editElement.getAttribute('accountId');
    this.grpName.setValue(accountGrp);
    this.underGrp.setValue(accountUnderGrp);
    this.setAccountId = accountId;
    this.isSaveHide = true;
    this.isUpdateHide = false;
  }
  updateAccount(accountpost) {
    this.service.subUrl = 'accounting/accounting/updateAccount';
    accountpost.stringify
    let postData = {
      'grp': accountpost.value.grpName,
      'undergrp': accountpost.value.underGrp,
      'accountId': this.setAccountId
    };
    // let postData = updatePost.value; // Text Field/Form Data in Json Format
    this.service.updatePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/accounting/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Account Updated Successfully.'
        this.toasterMsg(type, title, body);
        // this.accountGrpForm.reset();
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Account Update Failed Please Try Again.'
        this.toasterMsg(type, title, body);
        this.accountGrpForm.reset();
      }

    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let accntId = deleteElement.getAttribute('accId');
    let delDeptId;
    this.delAccntId = accntId;
    (<any>jQuery('#accountDeleteModal')).modal('show');
  }

  deleteAccntData(accntIdInput: HTMLInputElement) {

    this.service.subUrl = 'accounting/accounting/deleteaccount';
    let postData = {
      'accntId': accntIdInput.value
    };
    this.service.deletePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/accounting/index';
        this.service.createPost(postData).subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Account deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#accountDeleteModal')).modal('hide');

        this.accountGrpForm.reset();
        // this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Account Delete Failed Please Try Again.'
        this.toasterMsg(type, title, body);
        this.accountGrpForm.reset();
      }

    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }
  cancelUpdate() {
    // this.flag = 0;
    this.title = "Create Account Groups";
    this.accountGrpForm.reset();
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

