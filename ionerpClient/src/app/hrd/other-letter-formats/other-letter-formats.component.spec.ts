import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OtherLetterFormatsComponent } from './other-letter-formats.component';

describe('OtherLetterFormatsComponent', () => {
  let component: OtherLetterFormatsComponent;
  let fixture: ComponentFixture<OtherLetterFormatsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OtherLetterFormatsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OtherLetterFormatsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
