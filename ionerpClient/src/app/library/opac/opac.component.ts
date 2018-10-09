import { Component, OnInit, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from '../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import * as $ from 'jquery';

@Component({
  selector: 'app-opac',
  templateUrl: './opac.component.html',
  styleUrls: ['./opac.component.css']
})
export class OpacComponent implements OnInit {
  categoryList;
  subCategoryList;
  booksList;
  today: number = Date.now();

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

    //to load category
    this.service.subUrl = 'library/Opac/getCategoryData';
    this.service.getData().subscribe(response => {
      this.categoryList = response.json();
    })

    //to load book list
    this.service.subUrl = 'library/Opac/getBooksAvail';
    this.service.getData().subscribe(response => {
      this.booksList = response.json();
      // this.showColValue = false;
      this.tableRerender();
      this.dtTrigger.next();
    })
  }

  //form validation
  private opacForm = new FormGroup({
    category: new FormControl('', [

    ]),
    subcategory: new FormControl('', [

    ]),
    title: new FormControl('', [

    ]),
    author: new FormControl('', [

    ]),

  })

  get category() {
    return this.opacForm.get('category');
  }
  get subcategory() {
    return this.opacForm.get('subcategory');
  }
  get title() {
    return this.opacForm.get('title');
  }
  get author() {
    return this.opacForm.get('author');
  }

  //load subcategory based on category
  loadSubCategory(event) {
    if (event == 0) {
      this.category.setValue('0');
      this.subcategory.setValue('0')
    }
    this.service.subUrl = 'library/Opac/getSubCategoryData';
    this.service.createPost(event).subscribe(response => {

      this.subCategoryList = response.json();


    })
  }

  searchBooks(bookslist) {
    let categoryName = bookslist.value.category;
    let subcategoryName = bookslist.value.subcategory;
    let bookTitle = bookslist.value.title;
    let bookAuthor = bookslist.value.author;

    let postData = {
      "categoryName": categoryName,
      "subCategoryName": subcategoryName,
      "title": bookTitle,
      "author": bookAuthor,
    }
    this.service.subUrl = 'library/Opac/filterBooks';
    this.service.createPost(postData).subscribe(response => {

      this.booksList = response.json();
      this.tableRerender();
      this.dtTrigger.next();

    })
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
