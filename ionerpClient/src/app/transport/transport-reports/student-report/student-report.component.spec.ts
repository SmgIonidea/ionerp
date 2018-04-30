import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentReportComponent } from './student-report.component';

describe('StudentReportComponent', () => {
  let component: StudentReportComponent;
  let fixture: ComponentFixture<StudentReportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentReportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});