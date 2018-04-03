import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { IssueItemsComponent } from './issue-items.component';

describe('IssueItemsComponent', () => {
  let component: IssueItemsComponent;
  let fixture: ComponentFixture<IssueItemsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IssueItemsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IssueItemsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
