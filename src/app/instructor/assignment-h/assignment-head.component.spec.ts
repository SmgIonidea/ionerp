import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AssignmentHeadComponent } from './assignment-head.component';

describe('AssignmentHeadComponent', () => {
  let component: AssignmentHeadComponent;
  let fixture: ComponentFixture<AssignmentHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AssignmentHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AssignmentHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
