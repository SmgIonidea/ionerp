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
import { ActivatedRoute, Params, Router } from "@angular/router";
import { CharctersOnlyValidation } from '../../custom.validators';


@Component({
  selector: 'app-health-record',
  templateUrl: './health-record.component.html',
  styleUrls: ['./health-record.component.css']
})
export class HealthRecordComponent implements OnInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService, private route: ActivatedRoute,private router: Router) { }

  heading: string;
  roomallotid
  personid;
  persontype;
  personclass;
  personname;
  private sub: any;
  healthdetails;
  tosterconfig

  ngOnInit() {

    this.heading = 'Health Record';
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.roomallotid = +params['id'] || 0;
        this.persontype = params['pername'] || 0;
        this.personid = +params['perid'] || 0;
        let postData = {
          'persontype': this.persontype,
          'personid': this.personid,
        }
        this.service.subUrl = 'hostel/RoomAllocation/gethostelhealthrecord';
        this.service.createPost(postData).subscribe(response => {
          this.healthdetails = response.json();
          this.healthdetails.forEach(element => {
            this.personclass = element.class
            this.personname = element.name
            //   this.preadmissionid = element.preid
            //   this.prename = element.name
          })
        });
      });

  }

  private healthRecordForm = new FormGroup({

    regEmpNo: new FormControl('', [
    ]),

    personType: new FormControl('', [
    ]),

    name: new FormControl('', [
    ]),

    class: new FormControl('', [
    ]),

    problemDef: new FormControl('', [
      Validators.required]),

    doctorName: new FormControl('', [
      Validators.required]),

    address: new FormControl('', [
    ]),

    contactNo: new FormControl('', [
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
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

  inserthealth(healthRecordForm) {
    let postdata = {
      'healthdata': healthRecordForm.value,
      'class': this.personclass,
      'name': this.personname,
      'type': this.persontype,
      'id': this.personid,
    }

    this.service.subUrl = 'hostel/RoomAllocation/inserthealthrecord';
    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'Health Record Added Successfully'
        this.toasterMsg(type, title, body);
      }
      else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Health Record Add Successfully'
        this.toasterMsg(type, title, body);
      }
    });
    this.router.navigate(['/content',{outlets: { appCommon: ['issuetoroom']}}]);
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
