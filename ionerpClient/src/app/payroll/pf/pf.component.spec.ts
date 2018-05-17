import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PFComponent } from './pf.component';

describe('PFComponent', () => {
  let component: PFComponent;
  let fixture: ComponentFixture<PFComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PFComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PFComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
