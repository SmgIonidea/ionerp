import { Component, OnInit } from '@angular/core';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
// import { DataTableDirective } from 'angular-datatables';

@Component({
  selector: 'app-admission-form',
  templateUrl: './admission-form.component.html',
  styleUrls: ['./admission-form.component.css']
})
export class AdmissionFormComponent implements OnInit {
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

  constructor() { }

  ngOnInit() {
  }
  //function to disable the date untill selected start date
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
