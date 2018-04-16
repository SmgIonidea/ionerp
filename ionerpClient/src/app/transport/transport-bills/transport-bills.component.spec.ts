import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TransportBillsComponent } from './transport-bills.component';

describe('TransportBillsComponent', () => {
  let component: TransportBillsComponent;
  let fixture: ComponentFixture<TransportBillsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TransportBillsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TransportBillsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
