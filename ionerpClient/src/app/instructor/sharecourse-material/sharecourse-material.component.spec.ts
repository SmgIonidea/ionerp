import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SharecourseMaterialComponent } from './sharecourse-material.component';

describe('SharecourseMaterialComponent', () => {
  let component: SharecourseMaterialComponent;
  let fixture: ComponentFixture<SharecourseMaterialComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SharecourseMaterialComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SharecourseMaterialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
