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
  titlehead: any;
  buildingList;
  preapreBillList;

  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http) { }

  ngOnInit() {

    this.title = 'Bills';
    this.titleService.setTitle('PrepareBill | IONCUDOS');
    this.titlehead = "Prepare Hostel Bills";

      /* Get the list of  Building Names */
      this.service.subUrl = "hostel/PrepareBill/getBuildingList";
      this.service.getData().subscribe(response => {
        this.buildingList = response.json();
      });
  }


  
  /* Hostel Charge Details Validation */
  private prepareBillForm = new FormGroup({

    buildName: new FormControl('', [
      Validators.required
    ]),

    selectYear: new FormControl('', [
      Validators.required
    ]),

    selectMonth: new FormControl('', [
      Validators.required
    ]),

  });


  /* property to access the 
   formGroup Controles. which is used to validate the form */

  get buildName() {
    return this.prepareBillForm.get('buildName');
  }
  get selectYear() {
    return this.prepareBillForm.get('selectYear');
  }
  get selectMonth() {
    return this.prepareBillForm.get('selectMonth');
  }
 


preparebill(prepareBillForm){
  let preapreBill = {
  'buildId':prepareBillForm.value.buildName,
  'yearId':prepareBillForm.value.selectYear,
  'monthId':prepareBillForm.value.selectMonth,
  };
  alert(JSON.stringify(preapreBill));

  this.service.subUrl = "hostel/PrepareBill/prepareBillDetails";
    this.service.createPost(preapreBill).subscribe(response => {
      this.preapreBillList = response.json();
    });
}

}
