import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BookanalysisComponent } from './bookanalysis.component';

describe('BookanalysisComponent', () => {
  let component: BookanalysisComponent;
  let fixture: ComponentFixture<BookanalysisComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BookanalysisComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BookanalysisComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
