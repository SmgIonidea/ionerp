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

@Component({
  selector: 'app-de-allocate',
  templateUrl: './de-allocate.component.html',
  styleUrls: ['./de-allocate.component.css']
})
export class DeAllocateComponent implements OnInit {

  title: string; //load title
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
    private http: Http) { }

  ngOnInit() {

    this.title = 'Room De-Allocation';
    this.titleService.setTitle('RoomDeallocation | IONCUDOS');
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

}
