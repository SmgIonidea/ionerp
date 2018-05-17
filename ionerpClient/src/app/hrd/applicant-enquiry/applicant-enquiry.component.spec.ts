import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ApplicantEnquiryComponent } from './applicant-enquiry.component';

describe('ApplicantEnquiryComponent', () => {
  let component: ApplicantEnquiryComponent;
  let fixture: ComponentFixture<ApplicantEnquiryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ApplicantEnquiryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ApplicantEnquiryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
