import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudenttakeAssignmentComponent } from './studenttake-assignment.component';

describe('StudenttakeAssignmentComponent', () => {
  let component: StudenttakeAssignmentComponent;
  let fixture: ComponentFixture<StudenttakeAssignmentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudenttakeAssignmentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudenttakeAssignmentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
