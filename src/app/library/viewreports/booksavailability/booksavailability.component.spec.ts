import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { BooksavailabilityComponent } from './booksavailability.component';

describe('BooksavailabilityComponent', () => {
  let component: BooksavailabilityComponent;
  let fixture: ComponentFixture<BooksavailabilityComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ BooksavailabilityComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(BooksavailabilityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
