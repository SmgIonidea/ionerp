import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-messageinbox',
  templateUrl: './messageinbox.component.html',
  styleUrls: ['./messageinbox.component.css']
})
export class MessageinboxComponent implements OnInit {

  constructor() { }

  maintitle;


  ngOnInit() {
    this.maintitle = "Messages Inbox / Recieved";
  }

}
