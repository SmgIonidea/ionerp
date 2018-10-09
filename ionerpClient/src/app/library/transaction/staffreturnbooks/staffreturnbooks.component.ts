import { Component, OnInit, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from '../../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import * as $ from 'jquery';


@Component({
  selector: 'app-staffreturnbooks',
  templateUrl: './staffreturnbooks.component.html',
  styleUrls: ['./staffreturnbooks.component.css']
})
export class StaffreturnbooksComponent implements OnInit {

  textValue;
  title;
  maintitle;
  staffName;
  staffPost;
  staffDept;
  empId;
  staffDetails = [];
  category;
  subCategory;
  catValue;
  bookSearchList;
  booksList;
  issueBooks;
  issueBookData = [];
  showTables: boolean;
  showColValue: boolean;
  currentDate;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  //  @Input('legId') delLegtId;; // Input binding used to place building  id in hidden text box to delete the building name. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;
  constructor(public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router) { }


  ngOnInit() {
    this.title = "Issue/Return Books[Staff]";
    this.maintitle = "Issue / Return Books[Staff]";
    this.showTables = false;

    //to get emp no
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/getEmpId';
    this.service.getData().subscribe(response => {
      this.empId = response.json();
    })

    //to load book list
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/booksList';
    this.service.getData().subscribe(response => {
      this.booksList = response.json();
      this.showColValue = false;
      this.tableRerender();
      this.dtTrigger.next();
    })


    //to load category
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/getCategory';
    this.service.getData().subscribe(response => {
      this.category = response.json();
    })
  }

  //form validation
  private staffForm = new FormGroup({
    empno: new FormControl('', [

    ]),
    empno_select: new FormControl('', [
      Validators.required
    ]),
    staffame: new FormControl('', [


    ]),
    staffdesignation: new FormControl('', [

    ]),
    staffdept: new FormControl('', [

    ]),
    bookcategory: new FormControl('', [

    ]),
    subcategory: new FormControl('', [

    ]),
  })

  get empno() {
    return this.staffForm.get('empno');
  }
  get empno_select() {
    return this.staffForm.get('empno_select');
  }
  get staffname() {
    return this.staffForm.get('staffname');
  }
  get staffdesignation() {
    return this.staffForm.get('staffdesignation');
  }
  get staffdept() {
    return this.staffForm.get('staffdept');
  }
  get bookcategory() {
    return this.staffForm.get('bookcategory');
  }
  get subcategory() {
    return this.staffForm.get('subcategory');
  }

  //filter emp id based on id entered on textbox
  onSearchChange(searchValue: string) {
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/getEmpIdFilter';
    this.service.createPost(searchValue).subscribe(response => {
      this.empId = response.json();
    })
  }

  //function to get staff details(name,designation,department)
  getStaffData(id) {

    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/getStaffDetails';
    this.service.createPost(id).subscribe(response => {
      this.staffDetails = response.json();
      this.staffDetails.forEach(element => {
        this.staffName = element.st_firstname;
        this.staffPost = element.es_postname;
        this.staffDept = element.es_deptname;
      })
    })
  }

  //load subcategory based on category
  loadSubCategory(event) {

    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/getSubCategory';
    this.service.createPost(event).subscribe(response => {

      this.subCategory = response.json();


    })
  }

  //function to search books based on criterias set
  searchBooks(bookslist) {
    var empid = bookslist.value.empno_select;

    localStorage.setItem('empid', empid);
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/bookSearchList';

    let postData = {

      'categoryName': bookslist.value.bookcategory,
      'subCategoryName': bookslist.value.subcategory,

    };

    this.service.createPost(postData).subscribe(response => {

      this.booksList = response.json();
      this.bookcategory.reset()
      this.showColValue = true;
      this.showTables = false;
      let bookid = localStorage.getItem('bk_id');



      this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/loadIssueBooksTable';
      this.service.createPost(empid).subscribe(response => {

        this.issueBookData = response.json();
        if (this.issueBookData.length != 0) {
          this.showTables = true;
        }
        this.tableRerender();
        this.dtTrigger.next();
      })

      this.tableRerender();
      this.dtTrigger.next();

    });

  }

  //function to issue books to the particular employee
  issueBook(issueBookElement: HTMLElement) {
    let bookid = issueBookElement.getAttribute('bookId');
    let category = issueBookElement.getAttribute('category');
    let subcategory = issueBookElement.getAttribute('subcategory');
   
    localStorage.setItem('bk_id', bookid);
    let empid = localStorage.getItem('empid');
    let Data = {
      'bookid': bookid,
      'empid': empid
    }
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/issueBooks';
    this.service.createPost(Data).subscribe(response => {

      if (response.json().status == 'ok') {
        // to load issued book list
        this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/loadIssueBooksTable';
        this.service.createPost(empid).subscribe(response => {

          this.issueBookData = response.json();
          if (this.issueBookData.length != 0) {
            this.showTables = true;
          }
          this.tableRerender();
          this.dtTrigger.next();
        })


        //to load book list
        this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/booksList';
        this.service.getData().subscribe(response => {
          this.booksList = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })

        let type = 'success';
        let title = 'Add Success';
        let body = 'Book Issued to Student'
        this.toasterMsg(type, title, body);

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Book Issued to Student failed please try again'
        this.toasterMsg(type, title, body);

      }

    });


  }

  //function to return book
  returnBook(returnBookElement: HTMLElement) {
    let bookid = returnBookElement.getAttribute('bookId');
    let issuedate = returnBookElement.getAttribute('issuedate');
    let fine = returnBookElement.getAttribute('libfine');
    let returndate = returnBookElement.getAttribute('returndate');
    localStorage.setItem('bk_id', bookid);
    let empid = localStorage.getItem('empid');
    let Data = {
      'bookid': bookid,
      'empid': empid,
      'issuedate': issuedate,
      'returndate': returndate,
      'fine': fine
    }
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/returnBooks';
    this.service.createPost(Data).subscribe(response => {

      if (response.json().status == 'ok') {


        this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/loadIssueBooksTable';
        this.service.createPost(empid).subscribe(response => {
          this.issueBookData = response.json();
          if (this.issueBookData.length == 0) {
            this.showTables = false;
          }
          this.tableRerender();
          this.dtTrigger.next();
        })
        //to load book list
        this.service.subUrl = 'library/transaction_records/IssueReturnBookStaff/booksList';
        this.service.getData().subscribe(response => {
          this.booksList = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })
        let type = 'success';
        let title = 'Add Success';
        let body = 'Book Returned '
        this.toasterMsg(type, title, body);

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Book Returned  failed please try again'
        this.toasterMsg(type, title, body);

      }

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

  // to get success msg on particular add,edit,delete functionality
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





