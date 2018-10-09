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
  selector: 'app-allfines',
  templateUrl: './allfines.component.html',
  styleUrls: ['./allfines.component.css']
})
export class AllfinesComponent implements OnInit {
  bookFines;
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
    // let className= $('.classname').val();
    this.service.subUrl = 'library/Reports/Reports/searchFines';
    this.service.getData().subscribe(response => {
      this.bookFines = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    })
  }

  searchFines(){
    let className= $('.classname').val();
    this.service.subUrl = 'library/Reports/Reports/searchFines';
    this.service.createPost(className).subscribe(response => {
      this.bookFines = response.json();
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
