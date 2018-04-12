import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewHostelPersonsComponent } from './view-hostel-persons.component';

describe('ViewHostelPersonsComponent', () => {
  let component: ViewHostelPersonsComponent;
  let fixture: ComponentFixture<ViewHostelPersonsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewHostelPersonsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewHostelPersonsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
