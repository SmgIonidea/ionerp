import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DriverVehicleComponent } from './driver-vehicle.component';

describe('DriverVehicleComponent', () => {
  let component: DriverVehicleComponent;
  let fixture: ComponentFixture<DriverVehicleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DriverVehicleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DriverVehicleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
