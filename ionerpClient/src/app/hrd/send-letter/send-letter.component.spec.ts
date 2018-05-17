import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SendLetterComponent } from './send-letter.component';

describe('SendLetterComponent', () => {
  let component: SendLetterComponent;
  let fixture: ComponentFixture<SendLetterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SendLetterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SendLetterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
