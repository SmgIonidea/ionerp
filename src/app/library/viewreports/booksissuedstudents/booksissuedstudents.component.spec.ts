import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BooksissuedstudentsComponent } from './booksissuedstudents.component';

describe('BooksissuedstudentsComponent', () => {
  let component: BooksissuedstudentsComponent;
  let fixture: ComponentFixture<BooksissuedstudentsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BooksissuedstudentsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BooksissuedstudentsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
