import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-manage-vocher',
  templateUrl: './manage-vocher.component.html',
  styleUrls: ['./manage-vocher.component.css']
})
export class ManageVocherComponent implements OnInit {

  constructor() { }
  isActive;
  ngOnInit() {
  }
  toggle() {
    this.isActive = !this.isActive;
  }

}
