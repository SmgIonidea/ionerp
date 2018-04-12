import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ManageRubricsComponent } from './manage-rubrics.component';

describe('ManageRubricsComponent', () => {
  let component: ManageRubricsComponent;
  let fixture: ComponentFixture<ManageRubricsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ManageRubricsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ManageRubricsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
