import { Component, OnInit } from '@angular/core';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';

@Component({
  selector: 'app-issue-loan',
  templateUrl: './issue-loan.component.html',
  styleUrls: ['./issue-loan.component.css']
})
export class IssueLoanComponent implements OnInit {
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
  //for bank
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

  //for employee div
  employee(select_emp){
    if (select_emp == "a") {
      // this.hiddenDiv.style.visibility='visible';
    $('#employeeDiv').css('display','block');
    $('#wholeDiv').css('display','block');
    // Form.fileURL.focus();
  } 
  else{
    // this.hiddenDiv.style.visibility='hidden';
    $('#wholeDiv').css('display','none');
    $('#loanDiv').css('display','none');
    $('#loan').val('selLoan');
  }
  }

  //for loan div
  loan(select_loan){
    if (select_loan == "loanA") {
      // this.hiddenDiv.style.visibility='visible';
    $('#loanDiv').css('display','block');
    // Form.fileURL.focus();
  } 
  else{
    // this.hiddenDiv.style.visibility='hidden';
    $('#loanDiv').css('display','none');
  }
  }
}
