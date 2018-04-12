import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LessonScheduleComponent } from './lesson-schedule.component';

describe('LessonScheduleComponent', () => {
  let component: LessonScheduleComponent;
  let fixture: ComponentFixture<LessonScheduleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LessonScheduleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LessonScheduleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
