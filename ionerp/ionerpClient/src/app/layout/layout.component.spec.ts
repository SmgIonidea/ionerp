import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LayoutModule } from './layout.module';

describe('LayoutComponent', () => {
  let component: LayoutModule;
  let fixture: ComponentFixture<LayoutModule>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LayoutModule ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LayoutModule);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
