// import { Component, OnInit } from '@angular/core';

// @Component({
//   selector: 'app-ledgersummary',
//   templateUrl: './ledgersummary.component.html',
//   styleUrls: ['./ledgersummary.component.css']
// })
// export class LedgersummaryComponent implements OnInit {

//   constructor() { }

//   ngOnInit() {
//   }

// }

import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import * as $ from 'jquery';
import { IMyDpOptions, IMyDateModel, IMyDate } from 'mydatepicker';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';

@Component({
  selector: 'app-ledgersummary',
  templateUrl: './ledgersummary.component.html',
  styleUrls: ['./ledgersummary.component.css']
})
export class LedgersummaryComponent implements OnInit {
  title;
  ledgertype: Array<any>;
  result: Array<any>;
  openbalance: Array<any>;
  total: Array<any>;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
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
    this.title = "Ledger Summary";
    this.service.subUrl = "accounting/Ledgersummary/index";
    this.service.getData().subscribe(response => {
      this.ledgertype = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
    });


  }
  private legsummaryForm = new FormGroup({
    ledgertyp: new FormControl('', [
    ]),
    Cheque: new FormControl('', []),
  });

  // get Cheque() {
  //   return this.legsummaryForm.get('Cheque');
  // }

  searchresult(legsummaryForm) {
    this.service.subUrl = "accounting/Ledgersummary/getledgersummary";
    let ledgerdata = legsummaryForm.value;
    let postdata = {
      'ledgerdata': ledgerdata,

    }

    this.service.createPost(postdata).subscribe(response => {
      this.result = response.json();
      this.tableRerender();
      this.dtTrigger.next();

    });

    this.service.subUrl = "accounting/Ledgersummary/getOpeningBalance";
    this.service.createPost(postdata).subscribe(response => {
      this.openbalance = response.json();

    });

    this.service.subUrl = "accounting/Ledgersummary/gettotal";
    this.service.createPost(postdata).subscribe(response => {
      this.total = response.json();
      
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

