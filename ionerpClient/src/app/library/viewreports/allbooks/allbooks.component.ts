import { PostService } from './../../../services/post.service';
import { Component, OnInit, ViewChild } from '@angular/core';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';



@Component({
  selector: 'app-allbooks',
  templateUrl: './allbooks.component.html',
  styleUrls: ['./allbooks.component.css']
})
export class AllbooksComponent implements OnInit {
  private selDate: IMyDate = { year: 0, month: 0, day: 0 };
  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true,

    // allowSelectionOnlyInCurrentMonth:true
  };


  public myDatePickerOptionsto: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true,

    // allowSelectionOnlyInCurrentMonth:true
  };


  category;
  subCategory;
  catValue;
  books;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;
  showpopup: boolean = false;
  bookDetails: Array<any> = [{ lbook_dateofpurchase: '', lbook_bilnumber: '', lbook_category: '', lbook_booksubcategory: '', lbook_bookfromno: '' }];
  constructor(private service: PostService,
  ) { }

  private searchBooks = new FormGroup({
    categoryName: new FormControl('', [
      // Validators.required
    ]),
    subCategoryName: new FormControl('', [
      // Validators.required
    ]),
    fromDate: new FormControl('', [
      // Validators.required
    ]),
    toDate: new FormControl('', [
      // Validators.required
    ])
  })

  get categoryName() {
    return this.searchBooks.get('categoryName');
  }
  get subCategoryName() {
    return this.searchBooks.get('subCategoryName');
  }
  get fromDate() {
    return this.searchBooks.get('fromDate');
  }
  get toDate() {
    return this.searchBooks.get('toDate');
  }


  ngOnInit() {

    this.service.subUrl = "library/Reports/Reports/getCategoryData";
    this.service.getData().subscribe(response => {
      this.category = response.json();

    });
    this.service.subUrl = "library/Reports/Reports/getBooksData";
    this.service.getData().subscribe(response => {
      this.books = response.json();

      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table  

    });

  }

  loadSubCategory(event) {

    if (event == 0) {
      this.categoryName.setValue('0');
      this.subCategoryName.setValue('0')
    }
    this.service.subUrl = "library/Reports/Reports/getSubCategoryData";
    this.service.createPost(event).subscribe(response => {
      this.subCategory = response.json();

    });

  }

  filterBooks(formData) {


    this.service.subUrl = "library/Reports/Reports/filterBooks";
    this.service.createPost(formData.value).subscribe(response => {
      this.books = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table  

    });

  }

  getBookDetails(bookId) {


    // this.showpopup = true;
    this.service.subUrl = "library/Reports/Reports/getBookDetails";
    this.service.createPost(bookId).subscribe(response => {
      this.bookDetails = response.json();

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
