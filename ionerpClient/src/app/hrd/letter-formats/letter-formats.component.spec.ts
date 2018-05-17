import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LetterFormatsComponent } from './letter-formats.component';

describe('LetterFormatsComponent', () => {
  let component: LetterFormatsComponent;
  let fixture: ComponentFixture<LetterFormatsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LetterFormatsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LetterFormatsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
