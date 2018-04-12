import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ManageVocherComponent } from './manage-vocher.component';

describe('ManageVocherComponent', () => {
  let component: ManageVocherComponent;
  let fixture: ComponentFixture<ManageVocherComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ManageVocherComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ManageVocherComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
