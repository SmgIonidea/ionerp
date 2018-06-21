import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PrintLetterComponent } from './print-letter.component';

describe('PrintLetterComponent', () => {
  let component: PrintLetterComponent;
  let fixture: ComponentFixture<PrintLetterComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PrintLetterComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PrintLetterComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});