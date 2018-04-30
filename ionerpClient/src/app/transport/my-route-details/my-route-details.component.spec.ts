import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyRouteDetailsComponent } from './my-route-details.component';

describe('MyRouteDetailsComponent', () => {
  let component: MyRouteDetailsComponent;
  let fixture: ComponentFixture<MyRouteDetailsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyRouteDetailsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyRouteDetailsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
