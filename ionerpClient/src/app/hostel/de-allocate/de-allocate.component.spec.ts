import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeAllocateComponent } from './de-allocate.component';

describe('DeAllocateComponent', () => {
  let component: DeAllocateComponent;
  let fixture: ComponentFixture<DeAllocateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeAllocateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeAllocateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
