import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
// import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { RouterModule } from '@angular/router';
import { Subject } from 'rxjs/Rx';
import 'rxjs/add/operator/map';
import { ActivatedRoute, Params, Router } from "@angular/router";
// import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-issue-items',
  templateUrl: './issue-items.component.html',
  styleUrls: ['./issue-items.component.css']
})
export class IssueItemsComponent implements OnInit {

  title: string; //load title
  title1: string; //load title
  private fieldArray: Array<any> = [];
  private newAttribute: any = {};
  private sub: any;
  persondetails;
  itemcode;
  itemname;
  dropname;
  itemid;
  itname;
  roomtype;
  roomno;
  preadmissionid;
  prename;
  newAttributeName;
  newAttributetype;
  newAttributequantity;
  tosterconfig;
  id;
  persontype;
  personid;
  fieldcode;
  fieldname;
  fieldtype;
  fieldquantity;
  //  returnUrl: string;

  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http, private route: ActivatedRoute, private router: Router) { }

  ngOnInit() {
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.persontype = params['pername'] || 0;
        this.personid = +params['perid'] || 0;
        let postData = {
          'roomallotid': this.id,
          'persontype': this.persontype,
          'personid': this.personid,
        }
        this.service.subUrl = 'hostel/RoomAllocation/issueitems';
        this.service.createPost(postData).subscribe(response => {
          this.persondetails = response.json();
          this.persondetails.forEach(element => {
            this.roomtype = element.room_type
            this.roomno = element.room_no
            this.preadmissionid = element.preid
            this.prename = element.name
          })
        });

      });

    this.title = 'Person Items';
    this.titleService.setTitle('IssueItems | IONCUDOS');

    this.service.subUrl = 'hostel/RoomAllocation/itemcode';
    this.service.getData().subscribe(response => {
      this.itemcode = response.json();
    });

  }
  loaditemname(event) {
    this.service.subUrl = 'hostel/RoomAllocation/particularitemname';
    this.service.createPost(event).subscribe(response => {
      this.itemname = response.json();
      this.itemname.forEach(element => {
        // this.itname = element.in_item_name;
        this.newAttributeName = element.in_item_name;
        // this.studClass= element.es_classname;
      })
    });
    //  console.log(this.itemname);
    //  this.service.subUrl = 'hostel/RoomAllocation/itemname';
    // this.service.getData().subscribe(response => {
    //   this.dropname = response.json();
    // });
  }
  addpersonitem() {
    let postdata = {
      'newitemcode': this.itemid,
      'newitemname': this.newAttributeName,
      'newitemquant': this.newAttributequantity,
      'newitemtype': this.newAttributetype,
      'newroomallotid': this.id,
      'newpersontype': this.persontype,
      'newpersonid': this.personid,
    }
    this.service.subUrl = 'hostel/RoomAllocation/inserthostelpersonitem';
    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {  
        let type = 'success';
        let title = 'Add Success';
        let body = 'Item Added  Successfully'
        this.toasterMsg(type, title, body);
        // this.router.navigateByUrl('/(appCommon:issuetoroom)');
      }
      else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Please Try Again'
        this.toasterMsg(type, title, body);
      }
    });
     this.router.navigate(['/content',{outlets: { appCommon: ['issuetoroom']}}]);
  }
  //two add values of one more row
  loadfieldvalue(event) {
    this.service.subUrl = 'hostel/RoomAllocation/particularitemname';
    this.service.createPost(event).subscribe(response => {
      this.itemname = response.json();
      this.itemname.forEach(element => {
        // this.itname = element.in_item_name;
        this.fieldname = element.in_item_name;
        // this.studClass= element.es_classname;
      })
    });
  }
  //two add values of one more row
  addfieldpersonitem() {
    let postdata = {
      'newitemcode': this.fieldcode,
      'newitemname': this.fieldname,
      'newitemquant': this.fieldquantity,
      'newitemtype': this.fieldtype,
      'newroomallotid': this.id,
      'newpersontype': this.persontype,
      'newpersonid': this.personid,
    }
    this.service.subUrl = 'hostel/RoomAllocation/inserthostelpersonitem';
    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'Item Added  Successfully'
        this.toasterMsg(type, title, body);
      }
      else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Please Try Again'
        this.toasterMsg(type, title, body);
      }
    });
     this.router.navigate(['/content',{outlets: { appCommon: ['issuetoroom']}}]);
  }

  addFieldValue() {
    this.fieldArray.push(this.newAttribute)
    this.newAttribute = {};
  }

  deleteFieldValue(index) {
    this.fieldArray.splice(index, 1);
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
