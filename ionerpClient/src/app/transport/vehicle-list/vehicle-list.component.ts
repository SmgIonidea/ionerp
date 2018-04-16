import { Component, OnInit } from '@angular/core';
import * as $ from 'jquery';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';

@Component({
  selector: 'app-vehicle-list',
  templateUrl: './vehicle-list.component.html',
  styleUrls: ['./vehicle-list.component.css']
})
export class VehicleListComponent implements OnInit {
  public myDatePickerOptions1: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };
  public myDatePickerOptions2: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };
  public myDatePickerOptions3: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };
  constructor() { }

  ngOnInit() {
  }

}
