import { PostService } from './../../../services/post.service';
import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';

@Component({
  selector: 'app-booksavailability',
  templateUrl: './booksavailability.component.html',
  styleUrls: ['./booksavailability.component.css']
})
export class BooksavailabilityComponent implements OnInit {

  category;
  subCategory: Array<any> = [];
  catValue;
  books;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;
  constructor(private service: PostService) { }


  private searchBooksAvail = new FormGroup({
    search: new FormControl('', [
      Validators.required
    ]),
    categoryName: new FormControl('', [
      Validators.required
    ]),
    subCategoryName: new FormControl('', [
      Validators.required
    ]),

  })


  get search() {
    return this.searchBooksAvail.get('search');
  }

  get categoryName() {
    return this.searchBooksAvail.get('categoryName');
  }
  get subCategoryName() {
    return this.searchBooksAvail.get('subCategoryName');
  }


  ngOnInit() {

    this.service.subUrl = "library/Reports/Reports/getCategoryData";
    this.service.getData().subscribe(response => {
      this.category = response.json();
      this.subCategory.length = 0

    });

    this.service.subUrl = "library/Reports/Reports/getBooksavail";
    this.service.getData().subscribe(response => {
      this.books = response.json();

      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table  

    });
  }


  loadSubCategory(event) {
    this.service.subUrl = "library/Reports/Reports/getSubCategoryData";
    this.service.createPost(event).subscribe(response => {
      this.subCategory = response.json();

    });

  }
  searchAvail(searchBooksAvail) {

    this.service.subUrl = "library/Reports/Reports/checkAvailability";
    this.service.createPost(searchBooksAvail.value).subscribe(response => {
      this.books = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table  

    });

  }

  tableRerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
    });
  }
  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }

}
