import { Component, OnInit } from '@angular/core';
import { IMyDpOptions } from 'mydatepicker';

@Component({
  selector: 'app-assignment-head',
  templateUrl: './assignment-head.component.html',
  styleUrls: ['./assignment-head.component.css']
})
export class AssignmentHeadComponent implements OnInit {

  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd.mm.yyyy',
  };
  constructor() { }

  ngOnInit() {
  }

}
