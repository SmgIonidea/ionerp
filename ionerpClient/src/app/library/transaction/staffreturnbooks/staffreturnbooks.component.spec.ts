import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StaffreturnbooksComponent } from './staffreturnbooks.component';

describe('StaffreturnbooksComponent', () => {
  let component: StaffreturnbooksComponent;
  let fixture: ComponentFixture<StaffreturnbooksComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StaffreturnbooksComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StaffreturnbooksComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
