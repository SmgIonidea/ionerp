import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-smssetup',
  templateUrl: './smssetup.component.html',
  styleUrls: ['./smssetup.component.css']
})
export class SmssetupComponent implements OnInit {

  constructor() { }

  maintitle;

  ngOnInit() {
    this.maintitle = "SMS Setup";
  }

}
