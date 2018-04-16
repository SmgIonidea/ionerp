import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SmssetupComponent } from './smssetup.component';

describe('SmssetupComponent', () => {
  let component: SmssetupComponent;
  let fixture: ComponentFixture<SmssetupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SmssetupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SmssetupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
