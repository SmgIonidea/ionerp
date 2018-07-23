import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from '../../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import 'rxjs/add/operator/map';

@Component({
  selector: 'app-balancesheet',
  templateUrl: './balancesheet.component.html',
  styleUrls: ['./balancesheet.component.css']
})
export class BalancesheetComponent implements OnInit {

  title;
  maintitle;
  year: Array<any>;
  searchresult: Array<any>;
  paidoutresult: Array<any>;
  totalinresult;
  totaloutresult;

  constructor(
    public titleService: Title,
    public service: PostService,
    private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router, ) {

  }
  //  Rx.Observable.from(beers)   // <1>
  //   .filter(beer => beer.price < 8)   // <2>
  //   .map(beer => beer.name + ": $" + beer.price) // <3>
  //   .subscribe(    // <4>
  //       beer => console.log(beer),
  //       err => console.error(err),
  //       () => console.log("Streaming is over")
  // );

  ngOnInit() {
    this.maintitle = "Balance Sheet"
    this.service.subUrl = "accounting/balance/getFinancialYear"
    this.service.getData().subscribe(response => {
      this.year = response.json();
    });
    // this.service.getData().
    // map(response => response.fi_startdate + "To" + response.fi_enddate)
    // .subscribe(response => {
    //   this.year = response.json();
    // })

  }
  private balanceForm = new FormGroup({
    financeyear: new FormControl('', [
    ]),
  })

  get financeyear() {
    return this.balanceForm.get('financeyear');
  }

  searchdata(balanceForm) {
    this.service.subUrl = "accounting/balance/index"
    let searcbalancedata = balanceForm.value;
    
    let postdata = {
      'searchfinancedata': searcbalancedata,
    }

    this.service.createPost(postdata).subscribe(response => {
      this.searchresult = response.json();
    });

      this.service.subUrl = "accounting/balance/getpaidout"
    this.service.createPost(postdata).subscribe(response => {
      this.paidoutresult = response.json();
    });
    
     this.service.subUrl = "accounting/balance/getPaidinTotal"
    this.service.createPost(postdata).subscribe(response => {
      this.totalinresult = response.json();
    });
    
      this.service.subUrl = "accounting/balance/getPaidoutTotal"
    this.service.createPost(postdata).subscribe(response => {
      this.totaloutresult = response.json();
    });
  }

}
