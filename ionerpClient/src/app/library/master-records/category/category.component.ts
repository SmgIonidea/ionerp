import { Component, OnInit,Input, ViewChild, AfterViewInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ToastService } from '../../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';

@Component({
  selector: 'app-category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.css']
})
export class CategoryComponent implements OnInit {

  constructor(private service: PostService,private toast: ToastService) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;

  categoryData;
  tosterconfig;
  categoryList;
  categoryId; //edit ID
  categoryid; //delete ID

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  private categoryForm = new FormGroup({
    categoryName:new FormControl('',[
      Validators.required
    ]),
    categoryDesc:new FormControl('',[
      Validators.required
    ]),

  })

  get categoryName(){
    return this.categoryForm.get('categoryName');
  }

  get categoryDesc(){
    return this.categoryForm.get('categoryDesc');
  }

  ngOnInit() {

    this.maintitle = "Category";
    this.subtitle = "Add Category";
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.getCategory();
  }

  editcategory(editElement: HTMLElement){

    this.categoryId = editElement.getAttribute('categoryId');
    
    let categoryname = editElement.getAttribute('categoryname');
    let categorydesc = editElement.getAttribute('categorydesc');
    
    this.categoryName.setValue(categoryname); 
    this.categoryDesc.setValue(categorydesc);

    this.subtitle = 'Edit Category';
    this.isSaveHide = true;
    this.isUpdateHide = false;

  }

  getCategory(){

    this.service.subUrl = 'library/master_records/category/getCategoryDetails';
    this.service.getData().subscribe(response => {
      this.categoryData = response.json();      
      this.tableRerender();
      this.dtTrigger.next();
    });

  }

  saveCategory(categoryForm){

    this.service.subUrl = 'library/master_records/category/saveCategoryListData';

    let postdata = {

      'categoryName' : categoryForm.value.categoryName,
      'categoryDesc' : categoryForm.value.categoryDesc
    }

    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New Category added'
        this.toasterMsg(type, title, body);        
        this.categoryForm.reset();  
             
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New Category add failed please try again'
        this.toasterMsg(type, title, body);       
        this.categoryForm.reset();
      }
      this.getCategory(); 
      this.tableRerender();
      this.dtTrigger.next();
    });

  }

  updateCategory(categoryForm){

    this.service.subUrl = 'library/master_records/category/updateCategory';

    let postdata = {

      'categoryId' : this.categoryId,
      'categoryName' : categoryForm.value.categoryName,
      'categoryDesc' : categoryForm.value.categoryDesc
    }

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Category updated successfully'
        this.toasterMsg(type, title, body);
        this.categoryForm.reset();   
        this.cancelUpdate();   
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Category update failed please try again'
        this.toasterMsg(type, title, body);
        this.categoryForm.reset();
        this.cancelUpdate();
      }
      this.getCategory(); 
      
    })
  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {    

    this.categoryid = deleteElement.getAttribute('categoryId');
   (<any>jQuery('#categoryListDeleteModal')).modal('show');

 }

 deleteCategory(categoryid){
  
  this.service.subUrl = 'library/master_records/category/deleteCategory';
  let categorydelId = categoryid;
  
  this.service.updatePost(categorydelId).subscribe(response => {

    if (response.json().status == 'ok') {
      let type = 'success';
      let title = 'Delete Success';
      let body = 'Category deleted successfully'
      this.toasterMsg(type, title, body);
      this.categoryForm.reset();                    
    } else {
      let type = 'error';
      let title = 'Delete Fail';
      let body = 'Category delete failed please try again'
      this.toasterMsg(type, title, body);
      this.categoryForm.reset();        
    }
    this.getCategory(); 
    
  })  

 }

  cancelUpdate() {
    this.maintitle = "Category";
    this.subtitle = "Add Category";
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
