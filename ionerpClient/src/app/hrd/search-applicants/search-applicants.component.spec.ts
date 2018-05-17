import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SearchApplicantsComponent } from './search-applicants.component';

describe('SearchApplicantsComponent', () => {
  let component: SearchApplicantsComponent;
  let fixture: ComponentFixture<SearchApplicantsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SearchApplicantsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SearchApplicantsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
