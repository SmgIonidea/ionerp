import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VehicleBoardComponent } from './vehicle-board.component';

describe('VehicleBoardComponent', () => {
  let component: VehicleBoardComponent;
  let fixture: ComponentFixture<VehicleBoardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VehicleBoardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VehicleBoardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
