import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ComposemessageComponent } from './composemessage.component';

describe('ComposemessageComponent', () => {
  let component: ComposemessageComponent;
  let fixture: ComponentFixture<ComposemessageComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ComposemessageComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ComposemessageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
