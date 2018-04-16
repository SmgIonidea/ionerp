import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BooksissuedstaffComponent } from './booksissuedstaff.component';

describe('BooksissuedstaffComponent', () => {
  let component: BooksissuedstaffComponent;
  let fixture: ComponentFixture<BooksissuedstaffComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BooksissuedstaffComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BooksissuedstaffComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
