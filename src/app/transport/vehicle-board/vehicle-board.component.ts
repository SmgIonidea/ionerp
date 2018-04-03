import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-vehicle-board',
  templateUrl: './vehicle-board.component.html',
  styleUrls: ['./vehicle-board.component.css']
})
export class VehicleBoardComponent implements OnInit {

  constructor() { }
  isActive;
  ngOnInit() {
  }
  toggle() {
    this.isActive = !this.isActive;
  }

}
