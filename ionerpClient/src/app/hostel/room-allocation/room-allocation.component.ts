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
// import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { IMyOptions, IMyDateModel, IMyDate } from 'mydatepicker';

@Component({
  selector: 'app-room-allocation',
  templateUrl: './room-allocation.component.html',
  styleUrls: ['./room-allocation.component.css']
})
export class RoomAllocationComponent implements OnInit, AfterViewInit {
  //  private selDate: IMyDate = { year: 0, month: 0, day: 0 };
  // public myDatePickerOptions: IMyDpOptions = {
  //   // other options...
  //   dateFormat: 'dd-mm-yyyy',
  //   showTodayBtn: true, markCurrentDay: true,
  //   disableUntil: { year: 0, month: 0, day: 0 },
  //   inline: false,
  //   showClearDateBtn: true,
  //   selectionTxtFontSize: '12px',
  //   editableDateField: false,
  //   openSelectorOnInputClick: true
  // };
  public mytime: Date = new Date();
  currentYear: any = this.mytime.getUTCFullYear();
  currentDate: any = this.mytime.getUTCDate() - 1;
  currentMonth: any = this.mytime.getUTCMonth() + 1; //months from 1-12

  private myDatePickerOptions: IMyOptions = {
    //  need use the current day,month and year 

    disableUntil: { year: this.currentYear, month: this.currentMonth, day: this.currentDate },
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) {

  }

  title: string; //load title
  heading: string;

  room = [];
  // capacity: string;
  // number: string;



  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;
  tosterconfig;

  buildId: any = 0;
  roomId: any = 0;

  buildings = [];
  rooms = [];
  posts = [];
  person;
  model;
  reg;
  day;
  month;
  year;
  admissiondate;
  hostelroomid;
  roomvacancy
  isallotHide: boolean;


  ngOnInit() {
    this.title = 'Room';
    this.heading = 'Room Allocation To A Person';

    this.service.subUrl = 'hostel/RoomAllocation/getBuilding';
    this.service.getData().subscribe(response => {
      this.buildings = response.json();
    });

    //  $("#roomallot").hide();
  }

  selectRoom(event) {
    // alert("adi");
    this.service.subUrl = 'hostel/RoomAllocation/selectRoom';
    this.service.createPost(event).subscribe(response => {
      this.rooms = response.json();
    });

  }

  loadData(event) {
    this.hostelroomid = event;
    this.service.subUrl = 'hostel/RoomAllocation/loadData';
    this.service.createPost(event).subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    });
    this.service.subUrl = 'hostel/RoomAllocation/getroomdetails';
    this.service.createPost(event).subscribe(response => {
      this.room = response.json();
      // this.type = 'Room Type';
      // this.capacity = 'Room Capacity';
      // this.number = 'Room No';
    });

    // $("#roomallot").hide();
  }

  private roomAllocationForm = new FormGroup({

    selectBuilding: new FormControl('', [
    ]),

    selectRoomNo: new FormControl('', [
    ]),

  });
  get selectBuilding() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.roomAllocationForm.get('selectBuilding');
  }
  get selectRoomNo() {
    return this.roomAllocationForm.get('selectRoomNo');
  }

  roomallotment() {
    this.service.subUrl = 'hostel/RoomAllocation/validregno';

    let postData = {
      'persontype': this.person,
      'allocationdate': this.admissiondate,
      'personid': this.reg,
      'hostelroomid': this.hostelroomid
    }
    this.service.createPost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/RoomAllocation/roomallotment';
        this.service.createPost(postData).subscribe(response => {
          if (response.json().status == 'ok') {
            this.service.subUrl = 'hostel/RoomAllocation/loadData';
            this.service.createPost(this.hostelroomid).subscribe(response => {
              this.posts = response.json();
              this.tableRerender();
              this.dtTrigger.next();
            });
            let type = 'success';
            let title = 'Add Success';
            let body = 'Room Allotted Successfully'
            this.toasterMsg(type, title, body);
            (<any>jQuery('#allotroom')).modal('hide');
            // $('#allotroom').reset();
            // $(document).on('hide.bs.modal', '#allotroom', function (e) {
            //   $('#allotroom').empty();
            // });
          }
          else {
            let type = 'error';
            let title = 'Add Fail';
            let body = 'Room Allottment Failed'
            this.toasterMsg(type, title, body);
          }
        });
      }
      else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'please enter valid register number'
        this.toasterMsg(type, title, body);
      }
    });
    $('#allotroom').on('hidden.bs.modal', function (e) {
      $(this)
        .find("input,textarea,select")
        .val('')
        .end()
    })
  }
  checkroomvacancy(modalEle: HTMLDivElement) {
    if (this.hostelroomid != 0) {
      this.service.subUrl = 'hostel/RoomAllocation/checkroomvacancy';
      this.service.createPost(this.hostelroomid).subscribe(response => {
        if (response.json().status == 'ok') {
          (<any>jQuery('#allotroom')).modal('show');
        }
        else {
          let type = 'error';
          let title = 'Add Fail';
          let body = 'Room is Full'
          this.toasterMsg(type, title, body)
        }
      });
    }
    else {
      let type = 'error';
      let title = 'Fail';
      let body = 'Please select Building and Room Number'
      this.toasterMsg(type, title, body)
    }

  }

  onDateChanged(event: IMyDateModel) {
    this.day = event.date.day;
    this.month = event.date.month;
    this.year = event.date.year;
    this.admissiondate = this.day + "/" + this.month + "/" + this.year;

    // alert(this.admissiondate);

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
