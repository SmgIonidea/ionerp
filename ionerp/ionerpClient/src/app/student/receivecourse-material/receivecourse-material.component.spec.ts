import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ReceivecourseMaterialComponent } from './receivecourse-material.component';

describe('ReceivecourseMaterialComponent', () => {
  let component: ReceivecourseMaterialComponent;
  let fixture: ComponentFixture<ReceivecourseMaterialComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ReceivecourseMaterialComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReceivecourseMaterialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
