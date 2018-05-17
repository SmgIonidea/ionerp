import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LoanIssuedToComponent } from './loan-issued-to.component';

describe('LoanIssuedToComponent', () => {
  let component: LoanIssuedToComponent;
  let fixture: ComponentFixture<LoanIssuedToComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LoanIssuedToComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LoanIssuedToComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
