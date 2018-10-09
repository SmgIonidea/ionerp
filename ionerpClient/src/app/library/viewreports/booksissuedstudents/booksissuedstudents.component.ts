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
  selector: 'app-booksissuedstudents',
  templateUrl: './booksissuedstudents.component.html',
  styleUrls: ['./booksissuedstudents.component.css']
})
export class BooksissuedstudentsComponent implements OnInit {
  classList;
  issuedStudentList;
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
    //to load classes
    this.service.subUrl = 'library/Reports/Reports/getClasses';
    this.service.getData().subscribe(response => {
      this.classList = response.json();
    })

     //to load student list
     this.service.subUrl = 'library/Reports/Reports/getIssuedStudentList';
     this.service.getData().subscribe(response => {
       this.issuedStudentList = response.json();
       this.tableRerender();
       this.dtTrigger.next();
     })
  }

  searchClass(){
    let className= $('.classname').val();
    this.service.subUrl = 'library/Reports/Reports/searchClass';
    this.service.createPost(className).subscribe(response => {
      this.issuedStudentList = response.json();
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
