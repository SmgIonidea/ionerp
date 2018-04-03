import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';

@Component({
  selector: 'app-view-hostel-persons',
  templateUrl: './view-hostel-persons.component.html',
  styleUrls: ['./view-hostel-persons.component.css']
})
export class ViewHostelPersonsComponent implements OnInit {

  title: any;
  title1: any;
  hostel: Array<any>;
  rooms: Array<any>;
  person:Array<any>;
  viewperson:Array<any>;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @Input('buildId') delBuildId; // Input binding used to place building  id in hidden text box to delete the building name. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;

  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http) { }

    private viewHostelPersonForm = new FormGroup({

    selectBuilding: new FormControl('', [
    ]),

    selectRoomType: new FormControl('', [
    ]),

    selectPersonType: new FormControl('', [
    ]),

  });

  get selectBuilding() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.viewHostelPersonForm.get('selectBuilding');
  }
  get selectRoomNo() {
    return this.viewHostelPersonForm.get('selectRoomNo');
  }

  get selectPersonType() {
    return this.viewHostelPersonForm.get('selectPersonType');
  }

  ngOnInit() {

    this.title = 'Hostel Persons';
    this.titleService.setTitle('ViewHostelPersons | IONCUDOS');
    this.title1 = "View Hostel Persons";

    this.service.subUrl = 'hostel/hostel/gethostel';
    this.service.getData().subscribe(response => {
      this.hostel = response.json();

    });

    this.service.subUrl = 'hostel/hostel/getPerson';
    this.service.getData().subscribe(response => {
      this.person = response.json();

    });

  }

  selectRoom(event) {
    this.service.subUrl = 'hostel/hostel/selectRoom';
    this.service.createPost(event).subscribe(response => {
      this.rooms = response.json();
    });

  }

  searchperson(viewHostelPersonForm){
    this.service.subUrl = 'hostel/hostel/getViewPerosn';
    let postData = viewHostelPersonForm.value; // Text Field/Form Data in Json Format
    this.service.createPost(postData).subscribe(response => {
      this.viewperson = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
      this.viewHostelPersonForm.reset();
    });
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

printtable():void{
  let printContents, popupWin;
    printContents = document.getElementById('print1').innerHTML;
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

student() {
  $(function () {
    $("#ddlPassport").change(function () {
      if ($(this).val() == "Y") {
        $("#dvPassport").show();
        $("#student").hide();
      } else {
        $("#dvPassport").hide();
        $("#student").show();
      }
    });
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
