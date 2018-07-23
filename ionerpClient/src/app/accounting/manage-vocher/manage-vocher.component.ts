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
  selector: 'app-manage-vocher',
  templateUrl: './manage-vocher.component.html',
  styleUrls: ['./manage-vocher.component.css']
})
export class ManageVocherComponent implements OnInit {
  isActive;
  maintitle;
  title;
  post: Array<any>;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;
  modeid;
  modevalue;

  constructor(public service: PostService, private toast: ToastService, ) { }

  ngOnInit() {
    this.maintitle = "Account Groups";
    this.title = "Create Account Groups";
    this.service.subUrl = 'accounting/Managevoucher/index';
    this.service.getData().subscribe(response => {
      this.post = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
    });
  }
  toggle(id) {
    $('#updateVocher' + id).hide();
    $('#vouchermode' + id).hide();
    $('#voucher' + id).show();
    $('#submitVocher' + id).show();
    $('#backvoucher' + id).show();
    // this.isActive = !this.isActive;
  }
  toggleback(id){
    $('#updateVocher' + id).show();
    $('#vouchermode' + id).show();
    $('#voucher' + id).hide();
    $('#submitVocher' + id).hide();
    $('#backvoucher' + id).hide();
  }
  getmodevalue(id, value) {
   
    this.modeid = id;
    this.modevalue = value;

  }
  togglesubmit() {
   
    this.service.subUrl = 'accounting/Managevoucher/updateVoucherMode';

    let postData = {
      'id': this.modeid,
      'mode': this.modevalue,
    }
    
    this.service.createPost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'accounting/Managevoucher/index';

        this.service.getData().subscribe(response => {
          this.post = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        });
        let type = "success";
        let title = "Update Success";
        let body = "VoucherMode updated Successfully";
        this.toasterMsg(type, title, body);
      }
      else {
        let type = "error";
        let title = "Update Failed";
        let body = "VoucherMode Update Failed Please Try  Again";
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
