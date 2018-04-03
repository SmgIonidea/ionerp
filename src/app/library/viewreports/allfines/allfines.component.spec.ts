import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AllfinesComponent } from './allfines.component';

describe('AllfinesComponent', () => {
  let component: AllfinesComponent;
  let fixture: ComponentFixture<AllfinesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AllfinesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AllfinesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
