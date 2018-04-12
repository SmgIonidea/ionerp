import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StaffreportComponent } from './staffreport.component';

describe('StaffreportComponent', () => {
  let component: StaffreportComponent;
  let fixture: ComponentFixture<StaffreportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StaffreportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StaffreportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
