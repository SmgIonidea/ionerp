import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EmployeePayslipComponent } from './employee-payslip.component';

describe('EmployeePayslipComponent', () => {
  let component: EmployeePayslipComponent;
  let fixture: ComponentFixture<EmployeePayslipComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EmployeePayslipComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EmployeePayslipComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
