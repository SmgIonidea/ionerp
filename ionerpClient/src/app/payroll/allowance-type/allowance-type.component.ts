import { Component, OnInit } from '@angular/core';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';

@Component({
  selector: 'app-allowance-type',
  templateUrl: './allowance-type.component.html',
  styleUrls: ['./allowance-type.component.css']
})
export class AllowanceTypeComponent implements OnInit {

  constructor() { }
  maintitle;
  myOptions: IMultiSelectOption[]
  public myDatePickerOptions: IMyDpOptions = {
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
  ngOnInit() {
    this.maintitle = "Allowance Type";
    this.myOptions = [
      { id: 1, name: 'HST ENGLISH' },
      { id: 2, name: 'HST KANNADA' },
      { id: 3, name: 'HST HINDI' },
    ];
  }

}
