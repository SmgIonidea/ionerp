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
  selector: 'app-room-availability',
  templateUrl: './room-availability.component.html',
  styleUrls: ['./room-availability.component.css']
})
export class RoomAvailabilityComponent implements OnInit {

  title: any;
  title1: any;
  availability: Array<any>;
  room: Array<any>;
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

  private roomForm = new FormGroup({
    roomavailability: new FormControl('', [
      Validators.required,

    ])

  });
  get roomavailability() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.roomForm.get('roomavailability');
  }

  ngOnInit() {

    this.title = 'Room Availability';
    this.titleService.setTitle('RoomAvailability | IONCUDOS');
    this.title1 = "Building Name";

    this.service.subUrl = 'hostel/hostel/getAvailability';
    this.service.getData().subscribe(response => {
      this.availability = response.json();
    });

  }

  searchroom(roomForm) {

    this.service.subUrl = 'hostel/hostel/getRoomAvailability';
    let postData = roomForm.value; // Text Field/Form Data in Json Format
    this.service.createPost(postData).subscribe(response => {
      this.room = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
      this.roomForm.reset();
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
