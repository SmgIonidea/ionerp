import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../common/toast.service';
import { ContentWrapperComponent } from './../content-wrapper/content-wrapper.component';
import { PostService } from './../services/post.service';
import { CharctersOnlyValidation } from './../custom.validators';
import { Component, OnInit, Input, ViewChild, AfterViewInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ActivatedRoute, Params } from "@angular/router";
import { Title } from '@angular/platform-browser';


@Component({
  
  selector: 'app-department',
  templateUrl: './department.component.html',
  styleUrls: ['./department.component.css']
})

export class DepartmentComponent implements OnInit,AfterViewInit {
 
  /* Constructor */
  constructor(private service: PostService, private toast:ToastService,private activatedRoute: ActivatedRoute,public titleService:Title) {
  }
 /* Global Variable Declarations */ 
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  listPageHeading = "Department List";
  operationHeading = "Department Add/Edit";
  posts = [];
  isSaveHide: boolean;
  isUpdateHide: boolean;
  setDeptId:any; // department id used to update the details
  @Input('deptId')delDeptId; // Input binding used to place department id in hidden text box to delete the dept. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  
  ngOnInit() {
     this.titleService.setTitle('Department');
    // this.activatedRoute.paramMap.subscribe((params: Params) => {
    //   console.log(params.get("muttu"));
    // });
    this.service.subUrl = 'configuration/Department/index';
    this.service.getData().subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

 private depForm = new FormGroup({
    department: new FormControl('', [
      Validators.required,
      Validators.minLength(3),
      CharctersOnlyValidation.CharctersOnly
    ]),
    description: new FormControl('',[ Validators.required, Validators.maxLength(1000)]),
  });

  get department() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.depForm.get('department');
  }
  get description() {
    return this.depForm.get('description');
  }
  

  createPost(depForm) {
    this.service.subUrl = 'configuration/Department/createDepartment';
    let postData = depForm.value; // Text Field/Form Data in Json Format
    this.service.createPost(postData).subscribe(response => {
      this.tableRerender();
      this.posts = response.json();
      this.dtTrigger.next();// Calling the DT trigger to manually render the table
      if(response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New Department Added Successfully.'
        this.toasterMsg(type,title,body);
        this.depForm.reset();
      }else{
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New Department Add Failed Please Try Again.'
        this.toasterMsg(type,title,body);
        this.depForm.reset();
      }
    });
  }
  editDepartment(editElement: HTMLElement) {
    let deptName = editElement.getAttribute('deptName');
    let deptDesc = editElement.getAttribute('deptDescription');
    let deptId = editElement.getAttribute('deptId');
    this.department.setValue(deptName);
    this.description.setValue(deptDesc);
    this.setDeptId = deptId;
    this.isSaveHide = true;
    this.isUpdateHide = false;
  }
  
  updatePost(updatePost){
    this.service.subUrl = 'configuration/Department/updateDepartment';
    updatePost.stringify
    let postData = {
      'department': updatePost.value.department,
      'description': updatePost.value.description,
      'deptId': this.setDeptId
    };
   // let postData = updatePost.value; // Text Field/Form Data in Json Format
    this.service.updatePost(postData).subscribe(response => {
      this.tableRerender();
      this.posts = response.json();
      if(response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Department Updated Successfully.'
        this.toasterMsg(type,title,body);
        this.depForm.reset();
      }else{
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Department Update Failed Please Try Again.'
        this.toasterMsg(type,title,body);
        this.depForm.reset();
      }
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table
    });
  }

  cencelUpdate(){
    this.depForm.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  deleteWarning(deleteElement:HTMLElement, modalEle:HTMLDivElement){
    let deptId = deleteElement.getAttribute('deptId');
    let delDeptId;
    this.delDeptId = deptId;
    (<any>jQuery('#departmentDeleteModal')).modal('show');
  }

  deleteDeptData(deptIdInput:HTMLInputElement){
    console.log(deptIdInput.value);
    this.service.subUrl = 'configuration/Department/deleteDept';
    let postData = {
      'deptId': deptIdInput.value
    };
    this.service.deletePost(postData).subscribe(response => {
      this.tableRerender();
      if(response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Department Deleted Successfully.'
        this.toasterMsg(type,title,body);
        (<any>jQuery('#departmentDeleteModal')).modal('hide');
        this.depForm.reset();
      }else{
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Department Delete Failed Please Try Again.'
        this.toasterMsg(type,title,body);
        this.depForm.reset();
      }
      this.posts = response.json();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table
    });
    console.log(this.posts);
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

  toasterMsg(type,title,body){
    this.toast.toastType = type;
      this.toast.toastTitle = title;
      this.toast.toastBody = body;
      this.tosterconfig = new ToasterConfig ({
        positionClass:'toast-top-center',
        tapToDismiss: false,
        showCloseButton:true,
        animation:'slideUp'
      });
      this.toast.toastMsg;
  }


}
