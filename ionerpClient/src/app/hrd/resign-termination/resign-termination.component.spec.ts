import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ResignTerminationComponent } from './resign-termination.component';

describe('ResignTerminationComponent', () => {
  let component: ResignTerminationComponent;
  let fixture: ComponentFixture<ResignTerminationComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ResignTerminationComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ResignTerminationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
