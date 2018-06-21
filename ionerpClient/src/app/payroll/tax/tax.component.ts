import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, FormArray } from '@angular/forms';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';

@Component({
  selector: 'app-tax',
  templateUrl: './tax.component.html',
  styleUrls: ['./tax.component.css']
})
export class TaxComponent implements OnInit {
   // to get date in this format
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
  public myDatePickerOptions1: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true,
  };
  public invoiceForm: FormGroup;
  
// words2 = [{value: ''}, {value: ''}, {value: ''}];

  constructor(private _fb: FormBuilder) { }

  

ngOnInit() {
  this.invoiceForm = this._fb.group({
    itemRows: this._fb.array([this.initItemRows()]) // here
  });
}

initItemRows() {
  return this._fb.group({
      // list all your form controls here, which belongs to your form array
      from: [''],
      to: [''],
      rate: ['']
  });
}
addNewRow() {
  // control refers to your formarray
  const control = <FormArray>this.invoiceForm.controls['itemRows'];
  // add new formgroup
  control.push(this.initItemRows());
}

deleteRow(index: number) {
  // control refers to your formarray
  const control = <FormArray>this.invoiceForm.controls['itemRows'];
  // remove the chosen row
  control.removeAt(index);
}

}
