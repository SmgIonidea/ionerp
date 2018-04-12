import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentreturnbooksComponent } from './studentreturnbooks.component';

describe('StudentreturnbooksComponent', () => {
  let component: StudentreturnbooksComponent;
  let fixture: ComponentFixture<StudentreturnbooksComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentreturnbooksComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentreturnbooksComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
