import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { PostService } from './../../services/post.service';
import { Http, Response } from '@angular/http';
import 'rxjs/add/operator/map';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Component({
  selector: 'app-view-details',
  templateUrl: './view-details.component.html',
  styleUrls: ['./view-details.component.css']
})
export class ViewDetailsComponent implements OnInit, AfterViewInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) { }

  title: string; //load title
  heading: string;

  /* Global Variable Declarations */
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;

  ngOnInit() {

    this.title = 'Hostel';
    this.heading = 'Hostel Charges Details';

  }

  private hostelChargeForm = new FormGroup({

    selectBuilding: new FormControl('', [
    ]),

    selectYear: new FormControl('', [
    ]),

    selectMonth: new FormControl('', [
    ]),

    selectType: new FormControl('', [
    ]),

    selectStatus: new FormControl('', [
    ]),

    registrationNo: new FormControl('', [
    ]),

  });

  get selectBuilding() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.hostelChargeForm.get('selectBuilding');
  }
  get selectYear() {
    return this.hostelChargeForm.get('selectYear');
  }
  get selectMonth() {
    return this.hostelChargeForm.get('selectMonth');
  }
  get selectType() {
    return this.hostelChargeForm.get('selectType');
  }
  get selectStatus() {
    return this.hostelChargeForm.get('selectStatus');
  }
  get registrationNo() {
    return this.hostelChargeForm.get('registrationNo');
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
