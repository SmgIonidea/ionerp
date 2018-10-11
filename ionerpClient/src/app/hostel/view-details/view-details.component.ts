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
import { decode } from 'punycode';

@Component({
  selector: 'app-view-details',
  templateUrl: './view-details.component.html',
  styleUrls: ['./view-details.component.css']
})
export class ViewDetailsComponent implements OnInit, AfterViewInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) { }


  /* Global Variable Declarations */
  title: string; //load title
  heading: string;
  hostelList;
  reglist;
  duelist;
  buildingList;
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;

  ngOnInit() {

    this.title = 'Hostel';
    this.heading = 'Hostel Charges Details';


    /* Get the list of  Building Names */
    this.service.subUrl = "hostel/ViewDetails/getBuildingList";
    this.service.getData().subscribe(response => {
      this.buildingList = response.json();
    });
    // this.tableRerender();
    // this.dtTrigger.next();

  }


  /* Hostel Charge Details Validation */
  private hostelChargeForm = new FormGroup({

    selectBuilding: new FormControl('', [
      Validators.required
    ]),

    selectYear: new FormControl('', [
      Validators.required
    ]),

    selectMonth: new FormControl('', [
      Validators.required
    ]),

    selectType: new FormControl('', [
      Validators.required
    ]),

    selectStatus: new FormControl('', [
      Validators.required
    ]),

    registrationNo: new FormControl('', [
      Validators.required
    ]),

  });


  /* property to access the 
   formGroup Controles. which is used to validate the form */

  get selectBuilding() {
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

  serachView(hostelChargeForm) {
    let searchData = {
      'select_building': hostelChargeForm.value.selectBuilding,
      'select_year': hostelChargeForm.value.selectYear,
      'select_month': hostelChargeForm.value.selectMonth,
      'select_type': hostelChargeForm.value.selectType,
      'payment_status': hostelChargeForm.value.selectStatus,
      'registration_num': hostelChargeForm.value.registrationNo,
    };

    this.service.subUrl = "hostel/ViewDetails/searchViewDetails";
    this.service.createPost(searchData).subscribe(response => {
      this.hostelList = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    });
  }

}
