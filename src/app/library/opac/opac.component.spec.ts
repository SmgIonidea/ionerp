import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OpacComponent } from './opac.component';

describe('OpacComponent', () => {
  let component: OpacComponent;
  let fixture: ComponentFixture<OpacComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OpacComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OpacComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
