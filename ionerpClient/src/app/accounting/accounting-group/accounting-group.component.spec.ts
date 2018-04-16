import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AccountingGroupComponent } from './accounting-group.component';

describe('AccountingGroupComponent', () => {
  let component: AccountingGroupComponent;
  let fixture: ComponentFixture<AccountingGroupComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AccountingGroupComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AccountingGroupComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
