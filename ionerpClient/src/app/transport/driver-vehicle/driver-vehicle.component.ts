import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-driver-vehicle',
  templateUrl: './driver-vehicle.component.html',
  styleUrls: ['./driver-vehicle.component.css']
})
export class DriverVehicleComponent implements OnInit {

  constructor() { }
  isActive;
  ngOnInit() {
  }
  toggle() {
    this.isActive = !this.isActive;
  }

}
