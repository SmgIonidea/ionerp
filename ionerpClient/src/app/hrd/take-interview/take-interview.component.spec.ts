import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TakeInterviewComponent } from './take-interview.component';

describe('TakeInterviewComponent', () => {
  let component: TakeInterviewComponent;
  let fixture: ComponentFixture<TakeInterviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TakeInterviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TakeInterviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
