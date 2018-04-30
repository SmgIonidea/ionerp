import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AllRouteBoardComponent } from './all-route-board.component';

describe('AllRouteBoardComponent', () => {
  let component: AllRouteBoardComponent;
  let fixture: ComponentFixture<AllRouteBoardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AllRouteBoardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AllRouteBoardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
