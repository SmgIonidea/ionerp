import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LedgersummaryComponent } from './ledgersummary.component';

describe('LedgersummaryComponent', () => {
  let component: LedgersummaryComponent;
  let fixture: ComponentFixture<LedgersummaryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LedgersummaryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LedgersummaryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
