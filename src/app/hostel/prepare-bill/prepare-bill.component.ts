import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import { Http, Response } from '@angular/http';

@Component({
  selector: 'app-prepare-bill',
  templateUrl: './prepare-bill.component.html',
  styleUrls: ['./prepare-bill.component.css']
})
export class PrepareBillComponent implements OnInit {

  title: any;
  title1: any;

  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http) { }

  ngOnInit() {

    this.title = 'Bills';
    this.titleService.setTitle('PrepareBill | IONCUDOS');
    this.title1 = "Prepare Hostel Bills";
  }

}
