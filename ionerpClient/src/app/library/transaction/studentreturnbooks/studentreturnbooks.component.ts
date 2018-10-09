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
  selector: 'app-studentreturnbooks',
  templateUrl: './studentreturnbooks.component.html',
  styleUrls: ['./studentreturnbooks.component.css']
})
export class StudentreturnbooksComponent implements OnInit {
  textValue;
  title;
  maintitle;
  studName;
  studClass;
  regNo;
  studentDetails = [];
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
    this.title = "Issue/Return Books[Student]";
    this.maintitle = "Issue / Return Books[Student]";
    this.showTables = false;

    //to get roll no
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/getRegNo';
    this.service.getData().subscribe(response => {
      this.regNo = response.json();
    })

    //to load book list
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/booksList';
    this.service.getData().subscribe(response => {
      this.booksList = response.json();
      this.showColValue = false;
      this.tableRerender();
      this.dtTrigger.next();
    })


    //to load category
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/getCategory';
    this.service.getData().subscribe(response => {
      this.category = response.json();
    })
    // let dateFormat = require('dateformat');
    // this.currentDate = date(this.today, "yyyy-mm-dd"); 
    //return string
    var returnDate = "";
    //get datetime now
    var today = new Date();
    //split
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //because January is 0! 
    var yyyy = today.getFullYear();
    //Interpolation date
    if (dd < 10) {
      returnDate += `0${dd}.`;
    } else {
      returnDate += `${dd}.`;
    }

    if (mm < 10) {
      returnDate += `0${mm}.`;
    } else {
      returnDate += `${mm}.`;
    }
    returnDate += yyyy;
    return returnDate;

  }
  //form validation
  private studform = new FormGroup({
    regno: new FormControl('', [

    ]),
    regno_select: new FormControl('', [
      Validators.required
    ]),
    studname: new FormControl('', [


    ]),
    studclass: new FormControl('', [

    ]),
    bookcategory: new FormControl('', [

    ]),
    subcategory: new FormControl('', [

    ]),
  })

  get regno() {
    return this.studform.get('regno');
  }
  get regno_select() {
    return this.studform.get('regno_select');
  }
  get studname() {
    return this.studform.get('studname');
  }
  get studclass() {
    return this.studform.get('studclass');
  }
  get bookcategory() {
    return this.studform.get('bookcategory');
  }
  get subcategory() {
    return this.studform.get('subcategory');
  }


  onSearchChange(searchValue: string) {
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/getRegNoFilter';
    this.service.createPost(searchValue).subscribe(response => {
      this.regNo = response.json();
    })
  }


  getStudentData(id) {

    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/getStudentDetails';
    this.service.createPost(id).subscribe(response => {
      this.studentDetails = response.json();
      this.studentDetails.forEach(element => {
        this.studName = element.pre_name;
        this.studClass = element.es_classname;
      })
    })
  }

  loadSubCategory(event) {

    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/getSubCategory';
    this.service.createPost(event).subscribe(response => {

      this.subCategory = response.json();


    })
  }

  searchBooks(bookslist) {
    var regno = bookslist.value.regno_select;
    localStorage.setItem('regno', regno);
    var student_name = $('#studname').val();
    var student_class = $('#studclass').val();
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/bookSearchList';
   

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

      this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/loadIssueBooksTable';
      this.service.createPost(regno).subscribe(response => {

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

  issueBook(issueBookElement: HTMLElement) {
    let bookid = issueBookElement.getAttribute('bookId');
    let category = issueBookElement.getAttribute('category');
    let subcategory = issueBookElement.getAttribute('subcategory');

    localStorage.setItem('bk_id', bookid);
    let regno = localStorage.getItem('regno');
    let Data = {
      'bookid': bookid,
      'regno': regno
    }
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/issueBooks';
    this.service.createPost(Data).subscribe(response => {

      if (response.json().status == 'ok') {
        
        this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/loadIssueBooksTable';
        this.service.createPost(regno).subscribe(response => {
  
          this.issueBookData = response.json();
          if (this.issueBookData.length != 0) {
            this.showTables = true;
            // this.studform.reset();
          }
          this.tableRerender();
          this.dtTrigger.next();
        })
       
          //to load book list
        this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/booksList';
        this.service.getData().subscribe(response => {
          this.booksList = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })

        let type = 'success';
        let title = 'Add Success';
        let body = 'Book Issued to Student'
        this.toasterMsg(type, title, body);
        //this.boardList.reset();
        // this.cancelUpdate();

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Book Issued to Student failed please try again'
        this.toasterMsg(type, title, body);
        //  this.boardList.reset();
      }

    });


  }

  returnBook(returnBookElement: HTMLElement) {
    let bookid = returnBookElement.getAttribute('bookId');
    let issuedate = returnBookElement.getAttribute('issuedate');
    let fine = returnBookElement.getAttribute('libfine');
    let returndate = returnBookElement.getAttribute('returndate');
    localStorage.setItem('bk_id', bookid);
    let regno = localStorage.getItem('regno');
    let Data = {
      'bookid': bookid,
      'regno': regno,
      'issuedate': issuedate,
      'returndate': returndate,
      'fine': fine
    }
    this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/returnBooks';
    this.service.createPost(Data).subscribe(response => {

      // this.issueBooks = response.json();
      if (response.json().status == 'ok') {


        this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/loadIssueBooksTable';
        this.service.createPost(regno).subscribe(response => {
          this.issueBookData = response.json();
          if (this.issueBookData.length == 0) {
            this.showTables = false;
          }
          this.tableRerender();
          this.dtTrigger.next();
        })
        //to load book list
        this.service.subUrl = 'library/transaction_records/IssueReturnBookStudent/booksList';
        this.service.getData().subscribe(response => {
          this.booksList = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })
        let type = 'success';
        let title = 'Add Success';
        let body = 'Book Returned '
        this.toasterMsg(type, title, body);
        //this.boardList.reset();
        // this.cancelUpdate();

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Book Returned  failed please try again'
        this.toasterMsg(type, title, body);
        //  this.boardList.reset();
      }

    });


  }

  cancelUpdate() {
    // this.flag = 0;
    // this.title = "Create New Ledger";
    this.studform.reset();
    // this.isSaveHide = false;
    // this.isUpdateHide = true;
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
