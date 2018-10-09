import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ToasterModule } from 'angular2-toaster';
import { CategoryComponent } from './category/category.component';
import { SubcategoryComponent } from './subcategory/subcategory.component';
import { LibraryfineComponent } from './libraryfine/libraryfine.component';
import { PublisherComponent } from './publisher/publisher.component';
import { BooksComponent } from './books/books.component';
import { DataTablesModule } from 'angular-datatables';
import { MyDatePickerModule } from 'mydatepicker';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';

@NgModule({
  imports: [
    CommonModule,
    DataTablesModule,
    MyDatePickerModule,
    FormsModule, ReactiveFormsModule,
    RouterModule,
    ToasterModule
  ],
  declarations: [CategoryComponent,
    SubcategoryComponent,
    LibraryfineComponent,
    PublisherComponent,
    BooksComponent,
   ],
   exports: [CategoryComponent,SubcategoryComponent,
    LibraryfineComponent,
    PublisherComponent,
    BooksComponent],
  bootstrap: [CategoryComponent,SubcategoryComponent,
    LibraryfineComponent,
    PublisherComponent,
    BooksComponent]
})
export class MasterRecordsModule { }
