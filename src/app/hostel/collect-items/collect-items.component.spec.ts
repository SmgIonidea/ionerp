import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CollectItemsComponent } from './collect-items.component';

describe('CollectItemsComponent', () => {
  let component: CollectItemsComponent;
  let fixture: ComponentFixture<CollectItemsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CollectItemsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CollectItemsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should be created', () => {
    expect(component).toBeTruthy();
  });
});
