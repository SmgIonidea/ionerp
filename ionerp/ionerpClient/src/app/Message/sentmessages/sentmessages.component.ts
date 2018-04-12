import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-sentmessages',
  templateUrl: './sentmessages.component.html',
  styleUrls: ['./sentmessages.component.css']
})
export class SentmessagesComponent implements OnInit {

  constructor() { }

  maintitle;

  ngOnInit() {
    this.maintitle = "Sent Messages";
  }

}
