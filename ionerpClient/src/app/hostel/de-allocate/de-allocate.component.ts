import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { ActivatedRoute, Params, Router } from "@angular/router";

@Component({
  selector: 'app-de-allocate',
  templateUrl: './de-allocate.component.html',
  styleUrls: ['./de-allocate.component.css']
})
export class DeAllocateComponent implements OnInit {

  title: string; //load title
  private sub: any;
  roomallotid;
  persontype;
  personid;
  deallocatedetails;
  roomno;
  personname;
  buildname;
  allot;
  tosterconfig

  private selDate: IMyDate = { year: 0, month: 0, day: 0 };
  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true,

    // allowSelectionOnlyInCurrentMonth:true
  };


  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http, private route: ActivatedRoute,private router: Router) {
    let d: Date = new Date();
    this.selDate = {
      year: d.getFullYear(),
      month: d.getMonth() + 1,
      day: d.getDate()
    };
  }

  ngOnInit() {

    this.title = 'Room De-Allocation';
    this.titleService.setTitle('RoomDeallocation | IONCUDOS');
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
          'roomid': this.roomallotid,
        }
        this.service.subUrl = 'hostel/RoomAllocation/deallocate';
        this.service.createPost(postData).subscribe(response => {
          this.deallocatedetails = response.json();
          this.deallocatedetails.forEach(element => {
            this.roomno = element.room_no
            this.personname = element.name
            this.buildname = element.buld_name
            this.allot = element.alloted_date
          })
        });
      });
  }
  private deallocateForm = new FormGroup({
    deallocationdate: new FormControl('', [
      Validators.required
    ])
  });

  get deallocationdate() {
    return this.deallocateForm.get('deallocationdate');
  }
  searchroom(deallocateform) {

    let newdata = deallocateform.value;
    let postdata = {
      'deallocatedata': newdata,
      'roomallotid': this.roomallotid,
      'allocateddate': this.allot,
    }

    this.service.subUrl = 'hostel/RoomAllocation/checkdates';
    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/RoomAllocation/deallocateroom';
        this.service.createPost(postdata).subscribe(response => {
          if (response.json().status == 'ok') {
            let type = 'success';
            let title = 'success';
            let body = 'Deallocated room successfully'
            this.toasterMsg(type, title, body);
          } else {
            let type = 'error';
            let title = 'Fail';
            let body = 'Deallocate Fail'
            this.toasterMsg(type, title, body);
          }
        });
      }
      else {
        let type = 'error';
        let title = 'Deallocate Fail';
        let body = 'Deallocation date should be bigger than allocation date';
        this.toasterMsg(type, title, body);

      }
    });
    this.router.navigate(['/content',{outlets: { appCommon: ['issuetoroom']}}]);
  }
  onDateChanged(event: IMyDateModel) {

    //console.log('onDateChanged(): ', event.date, ' - jsdate: ', new Date(event.jsdate).toLocaleDateString(), ' - formatted: ', event.formatted, ' - epoc timestamp: ', event.epoc);
    //let startDate = this.lessonForm.controls['actualStartDate'].value;
    if (event.formatted == "") {
      this.myDatePickerOptions.disableUntil.day = 0;
      this.myDatePickerOptions.disableUntil.month = 0;
      this.myDatePickerOptions.disableUntil.year = 0;
      // this.selDate = event.date;
    } else {
      //  if(event == )
      this.myDatePickerOptions.disableUntil.day = event.date.day - 1;
      this.myDatePickerOptions.disableUntil.month = event.date.month;
      this.myDatePickerOptions.disableUntil.year = event.date.year;
      this.selDate = event.date;
    }
    // event properties are: event.date, event.jsdate, event.formatted and event.epoc
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
