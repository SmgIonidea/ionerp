import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PrepareBillComponent } from './prepare-bill.component';

describe('PrepareBillComponent', () => {
  let component: PrepareBillComponent;
  let fixture: ComponentFixture<PrepareBillComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PrepareBillComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PrepareBillComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
