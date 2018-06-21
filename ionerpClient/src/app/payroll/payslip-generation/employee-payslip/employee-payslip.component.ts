import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-employee-payslip',
  templateUrl: './employee-payslip.component.html',
  styleUrls: ['./employee-payslip.component.css']
})
export class EmployeePayslipComponent implements OnInit {

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

  showEmpDetails(){
    $('#employeeDiv').css('display','block');
  }
}
