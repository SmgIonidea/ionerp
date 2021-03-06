import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StudentreportComponent } from './studentreport.component';

describe('StudentreportComponent', () => {
  let component: StudentreportComponent;
  let fixture: ComponentFixture<StudentreportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StudentreportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StudentreportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
