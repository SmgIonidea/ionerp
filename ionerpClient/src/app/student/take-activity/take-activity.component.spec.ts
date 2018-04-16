import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TakeActivityComponent } from './take-activity.component';

describe('TakeActivityComponent', () => {
  let component: TakeActivityComponent;
  let fixture: ComponentFixture<TakeActivityComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TakeActivityComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TakeActivityComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
