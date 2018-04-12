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
  selector: 'app-add-room',
  templateUrl: './add-room.component.html',
  styleUrls: ['./add-room.component.css']
})
export class AddRoomComponent implements OnInit, AfterViewInit {

  constructor(private service: PostService,
    private http: Http,
    private toast: ToastService) { }

  title: string; //load title
  heading: string;

  /* Global Variable Declarations */
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;

  isSaveHide: boolean;
  isUpdateHide: boolean;

  posts = [];
  building = [];
  buildId: any = 0;

  setRoomId: any;
  @Input('roomId') delRoomId;

  ngOnInit() {

    this.title = 'Add Room';
    this.heading = 'Create Room';


    this.service.subUrl = 'hostel/hostel/index';
    this.service.getData().subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });

    this.service.subUrl = 'hostel/hostel/building';
    this.service.getData().subscribe(response => {
      this.building = response.json();
    });

    this.isSaveHide = false;
    this.isUpdateHide = true;

  }

  private addRoomForm = new FormGroup({

    addBuilding: new FormControl('', [
      Validators.required]),

    addRoomNo: new FormControl('', [
      Validators.required]),

    addRoomType: new FormControl('', [
      Validators.required]),

    addRoomCapacity: new FormControl('', [
      Validators.required]),

    addRoomRate: new FormControl('', [
      Validators.required]),
  });


  get addBuilding() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.addRoomForm.get('addBuilding');
  }
  get addRoomNo() {
    return this.addRoomForm.get('addRoomNo');
  }
  get addRoomType() {
    return this.addRoomForm.get('addRoomType');
  }
  get addRoomCapacity() {
    return this.addRoomForm.get('addRoomCapacity');
  }
  get addRoomRate() {
    return this.addRoomForm.get('addRoomRate');
  }

  //hostel room add function

  createPost(Form) {
    this.service.subUrl = 'hostel/hostel/createHostelRoom';
    let hostelRoom = Form.value;

    this.service.createPost(hostelRoom).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/hostel/index';
        this.service.getData().subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });
        let type = 'success';
        let title = 'Add Success';
        let body = 'New hostel room added successfully'
        this.toasterMsg(type, title, body);
        this.addRoomForm.reset();
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New hostel room add failed please try again'
        this.toasterMsg(type, title, body);
        this.addRoomForm.reset();
      }

    });
  }

  //hostel room edit function

  editHostelRoom(editElement: HTMLElement) {

    this.heading = 'Edit Room';

    let buildingId = editElement.getAttribute('eshostelbuldid');
    let roomNo = editElement.getAttribute('roomno');
    let roomType = editElement.getAttribute('roomtype');
    let roomCapacity = editElement.getAttribute('roomcapacity');
    let roomRate = editElement.getAttribute('roomrate');

    let roomId = editElement.getAttribute('eshostelroomid');

    this.setRoomId = roomId;

    this.addBuilding.setValue(buildingId);
    this.addRoomNo.setValue(roomNo);
    this.addRoomType.setValue(roomType);
    this.addRoomCapacity.setValue(roomCapacity);
    this.addRoomRate.setValue(roomRate);

    this.isSaveHide = true;
    this.isUpdateHide = false;

  }

  //hostel room update function

  updatePost(updatePost) {

    this.service.subUrl = 'hostel/hostel/updateHostelRoom';
    updatePost.stringify

    let postData = {
      'addBuilding': updatePost.value.addBuilding,
      'addRoomNo': updatePost.value.addRoomNo,
      'addRoomType': updatePost.value.addRoomType,
      'addRoomCapacity': updatePost.value.addRoomCapacity,
      'addRoomRate': updatePost.value.addRoomRate,
      'roomId': this.setRoomId
    };
    // let postData = updatePost.value; // Text Field/Form Data in Json Format
    this.service.updatePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'hostel/hostel/index';
        this.service.getData().subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Hostel room updated successfully'
        this.toasterMsg(type, title, body);
        this.addRoomForm.reset();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Hostel room update failed please try again'
        this.toasterMsg(type, title, body);
        this.addRoomForm.reset();
      }

    });

  }

  //hostel room cancel update function

  cancelUpdate() {

    this.heading = 'Add Room';
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  //hostel room delete warning function

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let roomId = deleteElement.getAttribute('eshostelroomid');
    this.delRoomId = roomId;
    (<any>jQuery('#hostelRoomDeleteModal')).modal('show');
  }

  //hostel room delete function

  deleteHostelRoomData(roomIdInput: HTMLInputElement) {

    this.service.subUrl = 'hostel/hostel/deleteHostelRoom';

    let roomId = roomIdInput.value

    this.service.deletePost(roomId).subscribe(response => {

      if (response.json().status == 'ok') {

        this.service.subUrl = 'hostel/hostel/index';
        this.service.getData().subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });

        let type = 'success';
        let title = 'Delete Success';
        let body = 'Hostel room deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#hostelRoomDeleteModal')).modal('hide');
        this.addRoomForm.reset();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Hostel room delete failed please try again'
        this.toasterMsg(type, title, body);
        this.addRoomForm.reset();
      }


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
