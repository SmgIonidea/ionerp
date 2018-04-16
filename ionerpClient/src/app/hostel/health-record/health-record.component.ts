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
  selector: 'app-health-record',
  templateUrl: './health-record.component.html',
  styleUrls: ['./health-record.component.css']
})
export class HealthRecordComponent implements OnInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) { }

  heading: string;

  ngOnInit() {

    this.heading = 'Health Record';

  }

   private healthRecordForm = new FormGroup({

    regEmpNo: new FormControl('', [
    Validators.required]),

    personType: new FormControl('', [
    Validators.required]),

    name: new FormControl('', [
    Validators.required]),

    class: new FormControl('', [
    Validators.required]),

    problemDef: new FormControl('', [
    Validators.required]),

    doctorName: new FormControl('', [
    Validators.required]),

    address: new FormControl('', [
    ]),

    contactNo: new FormControl('', [
    ]),

    DoctorPresc: new FormControl('', [
    ]),

  });

  get regEmpNo() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.healthRecordForm.get('regEmpNo');
  }
  get personType() {
    return this.healthRecordForm.get('personType');
  }

  get name() {
    return this.healthRecordForm.get('name');
  }

  get class() {
    return this.healthRecordForm.get('class');
  }

  get problemDef() {
    return this.healthRecordForm.get('problemDef');
  }

  get doctorName() {
    return this.healthRecordForm.get('doctorName');
  }

  get address() {
    return this.healthRecordForm.get('address');
  }

  get contactNo() {
    return this.healthRecordForm.get('contactNo');
  }

  get DoctorPresc() {
    return this.healthRecordForm.get('DoctorPresc');
  }

}
