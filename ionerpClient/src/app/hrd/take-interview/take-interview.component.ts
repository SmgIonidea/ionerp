import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-take-interview',
  templateUrl: './take-interview.component.html',
  styleUrls: ['./take-interview.component.css']
})
export class TakeInterviewComponent implements OnInit {

  constructor() { }

  maintitle;
  
  ngOnInit() {

    this.maintitle = "Take Interview";
    
  }

}
