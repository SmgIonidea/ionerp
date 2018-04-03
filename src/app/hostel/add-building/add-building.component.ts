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
  selector: 'app-add-building',
  templateUrl: './add-building.component.html',
  styleUrls: ['./add-building.component.css']
})


export class AddBuildingComponent implements OnInit, AfterViewInit {

  title: any;
  title1: any;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @Input('buildId') delBuildId; // Input binding used to place building  id in hidden text box to delete the building name. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  tosterconfig;
  dtInstance: DataTables.Api;

  build: Array<any>;
  buildingnameEditData: Array<any>;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  setBuildingtId: any;


  constructor(public titleService: Title,
    private service: PostService,
    private toast: ToastService,
    private http: Http) { }



  // validation for form
  private buildForm = new FormGroup({
    buildname: new FormControl('', [
      Validators.required,

    ])

  });
  get buildname() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.buildForm.get('buildname');
  }


  ngOnInit() {

    this.title = 'Add Building';
    this.titleService.setTitle('AddBuilding | IONCUDOS');
    this.title1 = "Create Building / Hall";

    //To list building name
    this.service.subUrl = 'hostel/hostel/getBuild';
    this.service.getData().subscribe(response => {
      this.build = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
    });
    this.isSaveHide = false;
    this.isUpdateHide = true;

  }

  //Building insert function
  
  createPost(buildForm) {
    this.service.subUrl = 'hostel/hostel/insertBuild';
    let postData = buildForm.value; // Text Field/Form Data in Json Format
    this.service.createPost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/hostel/getBuild';
        this.service.getData().subscribe(response => {
          this.build = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
        });
        let type = 'success';
        let title = 'Delivered';
        let body = 'New building name added successfully'
        this.toasterMsg(type, title, body);
        this.buildForm.reset();
      } else {
        let type = 'error';
        let title = 'Delivery Fail';
        let body = 'New building name add failed please try again'
        this.toasterMsg(type, title, body);
        this.buildForm.reset();
      }
    });
  }

  //To get form data

  editbuilding(editElement: HTMLElement) {
    this.title1 = "Edit Building Name";
    let buildingId = editElement.getAttribute('buildId');
    this.service.subUrl = 'hostel/hostel/geteditbuildingName';
    this.service.createPost(buildingId).subscribe(response => {
      this.buildingnameEditData = response.json();
      let buildingName = editElement.getAttribute('buildName');
      this.buildname.setValue(buildingName);
      this.setBuildingtId = buildingId;
    });

    this.isSaveHide = true;
    this.isUpdateHide = false;
  }


  // update building name

  updatePost(updatePost) {
    this.service.subUrl = 'hostel/hostel/updateBuildingName';
    updatePost.stringify

    let postData = {
      'buildname': updatePost.value.buildname,
      'buildingId': this.setBuildingtId
    };

    this.service.updatePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/hostel/getBuild';
        this.service.getData().subscribe(response => {
          this.build = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Building name updated successfully'
        this.toasterMsg(type, title, body);
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Building name update failed please try again'
        this.toasterMsg(type, title, body);
        this.buildForm.reset();

      }
    });
  }

  //Building name delete warning function

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let buildingId = deleteElement.getAttribute('buildId');
    let delBuildId;
    this.delBuildId = buildingId;
    (<any>jQuery('#BuildingNameDeleteModal')).modal('show');
  }


  // delete building

  deleteBuildingData(buildingIdInput: HTMLInputElement) {
    this.service.subUrl = 'hostel/hostel/deleteBuildingName';
    let postData = {
      'buildingId': buildingIdInput.value,
    };
    this.service.deletePost(postData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/hostel/getBuild';
        this.service.getData().subscribe(response => {
          this.build = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table 
        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Building name deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#BuildingNameDeleteModal')).modal('hide');
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Building name delete failed please try again'
        this.toasterMsg(type, title, body);
        this.cancelUpdate();
      }

    });

  }

  // to reset form

  cancelUpdate() {
    this.title1 = "Create Building / Hall";
    this.buildForm.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
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
