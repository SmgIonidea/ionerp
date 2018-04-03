import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LibraryfineComponent } from './libraryfine.component';

describe('LibraryfineComponent', () => {
  let component: LibraryfineComponent;
  let fixture: ComponentFixture<LibraryfineComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LibraryfineComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LibraryfineComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
