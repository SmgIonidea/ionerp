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
// import { DataTableDirective } from 'angular-datatables';

@Component({
  selector: 'app-report',
  templateUrl: './report.component.html',
  styleUrls: ['./report.component.css']
})
export class ReportComponent implements OnInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService, private route: ActivatedRoute) { }

  heading: string;
  title: string;
  head: string;
  private sub: any;
  id;
  persontype;
  personid;
  class;
  reportdetails;
  roomtype;
  roomno;
  prename;
  preclass;
  healthreports = [];
  hostelitemreports = [];

  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;
  tosterconfig;



  ngOnInit() {

    this.heading = 'View Record';
    this.title = 'Item Issued';
    this.head = 'Health Record';
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
          this.reportdetails = response.json();
          this.reportdetails.forEach(element => {
            this.roomtype = element.room_type
            this.roomno = element.room_no
            this.preclass = element.class
            this.prename = element.name
          })
        });

        this.service.subUrl = 'hostel/RoomAllocation/getreportitems';
        this.service.createPost(this.personid).subscribe(response => {
          this.hostelitemreports = response.json();
          // console.log(this.hostelitemreports);
          // this.tableRerender();
          // this.dtTrigger.next();
        });
        this.service.subUrl = 'hostel/RoomAllocation/getreporthealth';
        this.service.createPost(this.personid).subscribe(response => {
          this.healthreports = response.json();
          // console.log(this.healthreports);
        });


      });
    // if(this.hostelitemreports.length == 0 && this.healthreports.length == 0) {
    //   // alert("No Records Found");
    //   // let type = 'error';
    //   // let title = 'Search Fail';
    //   // let body = 'No Records Found'
    //   // this.toasterMsg(type, title, body);
    // }

  }

    print(): void {
    let printContents, popupWin;
    printContents = document.getElementById('print-section').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
          <title>Print tab</title>
          <style>
          //........Customized style.......
          </style>
        </head>
    <body onload="window.print();window.close()">${printContents}</body>
      </html>`
    );
    popupWin.document.close();
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
