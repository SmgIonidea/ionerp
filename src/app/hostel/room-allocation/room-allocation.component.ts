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
  selector: 'app-room-allocation',
  templateUrl: './room-allocation.component.html',
  styleUrls: ['./room-allocation.component.css']
})
export class RoomAllocationComponent implements OnInit, AfterViewInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) { }

  title: string; //load title
  heading: string;

  type: string;
  capacity: string;
  number: string;

  /* Global Variable Declarations */
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;

  buildId: any = 0;
  roomId: any = 0;

  buildings = [];
  rooms = [];
  posts = [];

  ngOnInit() {

    this.title = 'Room';
    this.heading = 'Room Allocation To A Person';

    this.service.subUrl = 'hostel/hostel/selectBuilding';
    this.service.getData().subscribe(response => {
      this.buildings = response.json();
    });

  }

  selectRoom(event) {

    this.service.subUrl = 'hostel/hostel/selectRoom';
    this.service.createPost(event).subscribe(response => {
      this.rooms = response.json();
    });

  }

  loadData(event) {
    this.service.subUrl = 'hostel/hostel/loadData';
    this.service.createPost(event).subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next();

      this.type = 'Room Type';
      this.capacity = 'Room Capacity';
      this.number = 'Room No';
    });
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
