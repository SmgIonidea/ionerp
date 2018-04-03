import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { PostService } from './../../services/post.service';
import { Http, Response } from '@angular/http';
import 'rxjs/add/operator/map';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';

@Component({
  selector: 'app-collect-items',
  templateUrl: './collect-items.component.html',
  styleUrls: ['./collect-items.component.css']
})
export class CollectItemsComponent implements OnInit, AfterViewInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) { }

  title: string; //load title
  heading: string;

  buildingLists = [];
  posts = [];

  /* Global Variable Declarations */
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;

  ngOnInit() {

    this.title = 'Building Name';
    this.heading = 'Collect Returnable Items';

    this.service.subUrl = 'hostel/hostel/selectBuildingItems';
    this.service.getData().subscribe(response => {
      this.buildingLists = response.json();
    });

  }

  private itemsForm = new FormGroup({

    selectBuilding: new FormControl('', [
      Validators.required])

  });

  get selectBuilding() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.itemsForm.get('selectBuilding');
  }

  getReturnableItems(Form){

    this.service.subUrl = 'hostel/hostel/returnableItemsLists';
    let hostelRoom = Form.value;
    this.service.createPost(hostelRoom).subscribe(response => {
       this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); 
    });

  }

  print(): void {
    let printContents, popupWin;
    printContents = document.getElementById('print-section').innerHTML;
    popupWin = window.open('', '_blank', 'top=0,left=0,height=100%,width=auto');
    popupWin.document.open();
    popupWin.document.write(`
      <html>
        <head>
          <title>Print tab</title>
          <style>
          //........Customized style.......
          </style>
        </head>
    <body onload="window.print();window.close()">${printContents}</body>
      </html>`
    );
    popupWin.document.close();
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
