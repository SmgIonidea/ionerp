import { Component, OnInit } from '@angular/core';
import * as $ from 'jquery';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';

@Component({
  selector: 'app-maintenance',
  templateUrl: './maintenance.component.html',
  styleUrls: ['./maintenance.component.css']
})
export class MaintenanceComponent implements OnInit {
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
  constructor() { }

  ngOnInit() {
  }

  selectDiv(select_item){
    if (select_item == "cheque" || select_item == "dd" ) {
      // this.hiddenDiv.style.visibility='visible';
    $('#hiddeDiv').css('display','block');
    // Form.fileURL.focus();
  } 
  else{
    // this.hiddenDiv.style.visibility='hidden';
    $('#hiddeDiv').css('display','none');
  }
  }
}
