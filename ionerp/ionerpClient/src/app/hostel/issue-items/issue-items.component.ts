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
  selector: 'app-issue-items',
  templateUrl: './issue-items.component.html',
  styleUrls: ['./issue-items.component.css']
})
export class IssueItemsComponent implements OnInit {

  title: string; //load title
  title1: string; //load title
   private fieldArray: Array<any> = [];
    private newAttribute: any = {};

  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http) { }

  ngOnInit() {

    this.title = 'Person Items';
    this.titleService.setTitle('IssueItems | IONCUDOS');
    
  }

  addFieldValue() {
        this.fieldArray.push(this.newAttribute)
        this.newAttribute = {};
    }

    deleteFieldValue(index) {
        this.fieldArray.splice(index, 1);
    }
  

}
