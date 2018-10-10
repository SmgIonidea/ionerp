import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ToastService } from '../../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';



@Component({
  selector: 'app-books',
  templateUrl: './books.component.html',
  styleUrls: ['./books.component.css']
})
export class BooksComponent implements OnInit {

  constructor(private service: PostService, private toast: ToastService) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  tosterconfig;

  categoryData;
  subCategoryData;
  publisherList;
  supplierList;
  bookList;
  accessionList;

  categoryId;
  bookEditId;
  bookdelId;
  public model_date: any;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  public myDatePickerOptions: IMyDpOptions = {
    // other options...
    dateFormat: 'dd-mm-yyyy',
    showTodayBtn: true, markCurrentDay: true,
    disableUntil: { year: 0, month: 0, day: 0 },
    inline: false,
    showClearDateBtn: true,
    selectionTxtFontSize: '12px',
    editableDateField: false,
    openSelectorOnInputClick: true
  };

  private booksForm = new FormGroup({

    purchasedOn: new FormControl('', [
      Validators.required
    ]),
    bookCategory: new FormControl('', [
      Validators.required
    ]),
    billNumber: new FormControl('', [
      Validators.required
    ]),
    bookSubCategory: new FormControl('', [
      Validators.required
    ]),
    accessionNumber: new FormControl('', [

    ]),
    numOfBooks: new FormControl('', [
      Validators.required
    ]),
    author: new FormControl('', [
      Validators.required
    ]),
    bookTitle: new FormControl('', [
      Validators.required
    ]),
    publisherName: new FormControl('', [
      Validators.required
    ]),
    image: new FormControl('', [
    ]),
    supplierName: new FormControl('', [
    ]),
    edition: new FormControl('', [
    ]),
    year: new FormControl('', [
    ]),
    cost: new FormControl('', [
    ]),
    additionalInfo: new FormControl('', [
    ]),
    pages: new FormControl('', [
    ]),
    volume: new FormControl('', [
    ])
  })

  get purchasedOn() {
    return this.booksForm.get('purchasedOn');
  }
  get bookCategory() {
    return this.booksForm.get('bookCategory');
  }
  get billNumber() {
    return this.booksForm.get('billNumber');
  }
  get bookSubCategory() {
    return this.booksForm.get('bookSubCategory');
  }
  get accessionNumber() {
    return this.booksForm.get('accessionNumber');
  }
  get numOfBooks() {
    return this.booksForm.get('numOfBooks');
  }
  get author() {
    return this.booksForm.get('author');
  }

  get bookTitle() {
    return this.booksForm.get('bookTitle');
  }
  get publisherName() {
    return this.booksForm.get('publisherName');
  }
  get image() {
    return this.booksForm.get('image');
  }
  get supplierName() {
    return this.booksForm.get('supplierName');
  }
  get edition() {
    return this.booksForm.get('edition');
  }
  get year() {
    return this.booksForm.get('year');
  }
  get cost() {
    return this.booksForm.get('cost');
  }
  get additionalInfo() {
    return this.booksForm.get('additionalInfo');
  }
  get pages() {
    return this.booksForm.get('pages');
  }
  get volume() {
    return this.booksForm.get('volume');
  }

  getFileName(replaceElement: HTMLElement, splitElement: HTMLElement) {

    var value = JSON.stringify($('#userdoc').val());

    //get filepath replace forward slash with backward slash
    var filePath = value.replace(/\\/g, "/");
    //remove backward slash and "

    var path = filePath.split('/').pop();
    path = path.replace('"', '');


    //get the fileName
    // console.log(path);
    // var fileName = JSON.stringify($('#addDocFiles').val(path));

    // this.LicenseDoc.setValue(path);
  }

  ngOnInit() {

    this.maintitle = "Books";
    this.subtitle = "Add Books";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.service.subUrl = 'library/master_records/category/getCategoryDetails';
    this.service.getData().subscribe(response => {
      this.categoryData = response.json();
    })



    this.service.subUrl = 'library/master_records/books/getPublisherData';
    this.service.getData().subscribe(response => {
      this.publisherList = response.json();
    })

    this.service.subUrl = 'library/master_records/books/getSupplierData';
    this.service.getData().subscribe(response => {
      this.supplierList = response.json();
    })

    this.service.subUrl = 'library/master_records/books/accessionNumberData';
    this.service.getData().subscribe(response => {
      this.accessionList = response.json();
      this.accessionNumber.setValue(this.accessionList);
    })

    this.getBookDetails();
  }

  loadSubCategory(event) {

    this.service.subUrl = 'library/master_records/books/getSubCategory';
    this.service.createPost(event).subscribe(response => {
      this.subCategoryData = response.json();
    })
  }

  getBookDetails() {

    this.service.subUrl = 'library/master_records/books/getBooksData';
    this.service.getData().subscribe(response => {
      this.bookList = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    })

  }

  saveBookDetails(bookForm) {

    // let filename = bookForm.value.image;
    // let filePath = filename.replace(/\\/g, "/");
    // let path = filePath.split('/').pop();
    // path = path.replace('"', '');

    

    this.service.subUrl = 'library/master_records/books/saveBookData';
    let postdata = {

      'purchasedDate': bookForm.value.purchasedOn,
      'category': bookForm.value.bookCategory,
      'billnum': bookForm.value.billNumber,
      'subcategory': bookForm.value.bookSubCategory,
      'accessionNum': this.accessionList,
      'numberOfBooks': bookForm.value.numOfBooks,
      'author': bookForm.value.author,
      'bookTitle': bookForm.value.bookTitle,
      'publisher': bookForm.value.publisherName,
      'supplier': bookForm.value.supplierName,
      'bookedition': bookForm.value.edition,
      'year': bookForm.value.year,
      'cost': bookForm.value.cost,
      'additionalInfo': bookForm.value.additionalInfo,
      'pages': bookForm.value.pages,
      'volume': bookForm.value.volume,
      // 'image': path
    }
    
    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New Book Details added'
        this.toasterMsg(type, title, body);
        this.booksForm.reset();
        this.service.subUrl = 'library/master_records/books/accessionNumberData';
        this.service.getData().subscribe(response => {
          this.accessionList = response.json();
          this.accessionNumber.setValue(this.accessionList);
        })

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New Book Details add failed please try again'
        this.toasterMsg(type, title, body);
        this.booksForm.reset();
        this.service.subUrl = 'library/master_records/books/accessionNumberData';
        this.service.getData().subscribe(response => {
          this.accessionList = response.json();
          this.accessionNumber.setValue(this.accessionList);
        })
      }
      this.getBookDetails();
      this.tableRerender();
      this.dtTrigger.next();
    });


  }

  editbooklist(editElement: HTMLElement) {

    this.subtitle = 'Edit Books';
    this.isSaveHide = true;
    this.isUpdateHide = false;

    this.bookEditId = editElement.getAttribute('bookId');

    let purchasedate = editElement.getAttribute('purchasedate');
    let year1 = purchasedate.substring(0, 4);
    let month = purchasedate.substring(5, 7);
    let day = purchasedate.substring(8, 10);
    let initial_day = day.replace(/^0+/, '');
    let initial_month = month.replace(/^0+/, '');
    this.model_date = { date: { year: year1, month: initial_month, day: initial_day } };

    let billnumber = editElement.getAttribute('billnumber');
    let accession = editElement.getAttribute('accession');
    let booknumber = editElement.getAttribute('booknumber');
    let author = editElement.getAttribute('author');
    let title = editElement.getAttribute('title');
    let publisher = editElement.getAttribute('publisherName');
    let supplier = editElement.getAttribute('suppliername');
    let year = editElement.getAttribute('year');
    let additionalinfo = editElement.getAttribute('additionalInfo');
    let cat = editElement.getAttribute('category');
    let subcat = editElement.getAttribute('subcategory');
    // let image = editElement.getAttribute('image');
    let bookedition = editElement.getAttribute('edition');
    let cost = editElement.getAttribute('cost');
    let pages = editElement.getAttribute('pages');
    let volume = editElement.getAttribute('volume');

    this.purchasedOn.setValue(this.model_date);
    this.billNumber.setValue(billnumber);
    this.accessionNumber.setValue(accession);
    this.numOfBooks.setValue(booknumber);
    this.author.setValue(author);
    this.bookTitle.setValue(title);
    this.publisherName.setValue(publisher);
    this.supplierName.setValue(supplier);
    this.year.setValue(year);
    this.additionalInfo.setValue(additionalinfo);
    this.bookCategory.setValue(cat);
    this.loadSubCategory(cat);
    this.bookSubCategory.setValue(subcat);
    // this.image.setValue(image);
    this.edition.setValue(bookedition);
    this.cost.setValue(cost);
    this.pages.setValue(pages);
    this.volume.setValue(volume);

  }

  updateBookList(bookForm){

    this.service.subUrl = 'library/master_records/books/updateBookList';


    // let filename = bookForm.value.image;
    // let filePath = filename.replace(/\\/g, "/");
    // let path = filePath.split('/').pop();
    // path = path.replace('"', '');

    let postdata = {

      'bookid': this.bookEditId,
      'purchasedDate': bookForm.value.purchasedOn,
      'category': bookForm.value.bookCategory,
      'billnum': bookForm.value.billNumber,
      'subcategory': bookForm.value.bookSubCategory,
      'accessionNum': this.accessionList,
      'numberOfBooks': bookForm.value.numOfBooks,
      'author': bookForm.value.author,
      'bookTitle': bookForm.value.bookTitle,
      'publisher': bookForm.value.publisherName,
      'supplier': bookForm.value.supplierName,
      'bookedition': bookForm.value.edition,
      'year': bookForm.value.year,
      'cost': bookForm.value.cost,
      'additionalInfo': bookForm.value.additionalInfo,
      'pages': bookForm.value.pages,
      'volume': bookForm.value.volume,
      // 'image': path
    }
    
    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Book updated successfully'
        this.toasterMsg(type, title, body);
        this.booksForm.reset();   
        this.service.subUrl = 'library/master_records/books/accessionNumberData';
        this.service.getData().subscribe(response => {
          this.accessionList = response.json();
          this.accessionNumber.setValue(this.accessionList);
        })
        this.cancelUpdate();   
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Book update failed please try again'
        this.toasterMsg(type, title, body);
        this.booksForm.reset();
        this.service.subUrl = 'library/master_records/books/accessionNumberData';
        this.service.getData().subscribe(response => {
          this.accessionList = response.json();
          this.accessionNumber.setValue(this.accessionList);
        })
        this.cancelUpdate();
      }
      this.getBookDetails();
      
    })

  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    this.bookdelId = deleteElement.getAttribute('bookId');
    (<any>jQuery('#bookListDeleteModal')).modal('show');

  }

  deleteBook(bookid) {

    this.service.subUrl = 'library/master_records/books/deleteBookList';

    bookid = this.bookdelId;

    this.service.updatePost(bookid).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Book deleted successfully'
        this.toasterMsg(type, title, body);
        this.booksForm.reset();
        this.service.subUrl = 'library/master_records/books/accessionNumberData';
        this.service.getData().subscribe(response => {
          this.accessionList = response.json();
          this.accessionNumber.setValue(this.accessionList);
        })
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Book delete failed please try again'
        this.toasterMsg(type, title, body);
        this.booksForm.reset();
        this.service.subUrl = 'library/master_records/books/accessionNumberData';
        this.service.getData().subscribe(response => {
          this.accessionList = response.json();
          this.accessionNumber.setValue(this.accessionList);
        })
      }
      this.getBookDetails();

    })

  }

  cancelUpdate() {

    this.service.subUrl = 'library/master_records/books/accessionNumberData';
    this.service.getData().subscribe(response => {
      this.accessionList = response.json();
      this.accessionNumber.setValue(this.accessionList);
    })

    this.maintitle = "Books";
    this.subtitle = "Add Books";
    this.isSaveHide = false;
    this.isUpdateHide = true;
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

  toasterMsg(type, title, body) {
    this.toast.toastType = type;
    this.toast.toastTitle = title;
    this.toast.toastBody = body;
    this.tosterconfig = new ToasterConfig({
      positionClass: 'toast-bottom-right',
      tapToDismiss: false,
      showCloseButton: true,
      animation: 'slideDown'
    });
    this.toast.toastMsg;
  }

}
