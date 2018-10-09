import { Component, OnInit,ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToastService } from '../../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { PostService } from '../../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';

@Component({
  selector: 'app-subcategory',
  templateUrl: './subcategory.component.html',
  styleUrls: ['./subcategory.component.css']
})
export class SubcategoryComponent implements OnInit {

  constructor(private service: PostService,private toast: ToastService) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;

  categoryData;
  subCategoryData;
  subCategoryEditId;
  subCategoryDelId;
  categoryname;
  tosterconfig;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  private SubcategoryForm = new FormGroup({
    catname:new FormControl('',[
      Validators.required
    ]),
    SubcategoryName:new FormControl('',[
      Validators.required
    ]),
    SubcategoryDesc:new FormControl('',[
      Validators.required
    ]),

  })

  get catname(){
    return this.SubcategoryForm.get('catname');
  }
  get SubcategoryName(){
    return this.SubcategoryForm.get('SubcategoryName');
  }
  get SubcategoryDesc(){
    return this.SubcategoryForm.get('SubcategoryDesc');
  }

  ngOnInit() {

    this.maintitle = "Sub Category";
    this.subtitle = "Add Sub Category";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.getSubCategoryData();

    this.service.subUrl = 'library/master_records/category/getCategoryDetails';
    this.service.getData().subscribe(response => {
      this.categoryData = response.json();            
    });
    

  }

  // getCategoryforSubcategory(){

  //   this.service.subUrl = 'library/category/getCategoryDetails';
  //   this.service.getData().subscribe(response => {
  //     this.categoryData = response.json();      
  //     this.tableRerender();
  //     this.dtTrigger.next();
  //   });

  // }

  getSubCategoryData(){

    this.service.subUrl = 'library/master_records/subcategory/getSubCategoryData';
    this.service.getData().subscribe(response => {
      this.subCategoryData = response.json();     
      // alert(JSON.stringify(this.subCategoryData)); 
      this.tableRerender();
      this.dtTrigger.next();
    });

  }

  editSubcategory(editElement: HTMLElement){

    this.subtitle = 'Edit Sub Category';
    this.isSaveHide = true;
    this.isUpdateHide = false;

    this.subCategoryEditId = editElement.getAttribute('subCatId');
    // alert(JSON.stringify(this.subCategoryEditId));
    let categoryname = editElement.getAttribute('category');
    let subcategoryname = editElement.getAttribute('subcatname');
    let subcatdesc = editElement.getAttribute('subcatdesc');

    this.catname.setValue(categoryname);
    this.SubcategoryName.setValue(subcategoryname);
    this.SubcategoryDesc.setValue(subcatdesc);

  }

  saveSubCategory(SubcategoryForm){

    this.service.subUrl = 'library/master_records/subcategory/saveSubCategory';

    let postdata = {

      'category' : SubcategoryForm.value.catname,
      'subCatName' : SubcategoryForm.value.SubcategoryName,
      'subCatDesc' : SubcategoryForm.value.SubcategoryDesc
    }

    // alert(JSON.stringify(postdata));

    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        
        let type = 'success';
        let title = 'Add Success';
        let body = 'New Sub Category added'
        this.toasterMsg(type, title, body);        
        this.SubcategoryForm.reset(); 
             
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New Sub Category add failed please try again'
        this.toasterMsg(type, title, body);       
        this.SubcategoryForm.reset();
      }
      this.getSubCategoryData(); 
      this.tableRerender();
      this.dtTrigger.next();
    });

  }

  updateSubCategoryList(SubcategoryForm){

    this.service.subUrl = 'library/master_records/subcategory/updateSubCategoryDetails';

    let postdata = {

      'subcategoryId' : this.subCategoryEditId,
      'category' : SubcategoryForm.value.catname,
      'subCatName' : SubcategoryForm.value.SubcategoryName,
      'subCatDesc' : SubcategoryForm.value.SubcategoryDesc 
    }

    

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Sub Category updated successfully'
        this.toasterMsg(type, title, body);
        this.SubcategoryForm.reset();   
        this.cancelUpdate();   
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Sub Category update failed please try again'
        this.toasterMsg(type, title, body);
        this.SubcategoryForm.reset();
        this.cancelUpdate();
      }
      this.getSubCategoryData();        
    })

  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {    

    this.subCategoryDelId = deleteElement.getAttribute('subCatId');
   (<any>jQuery('#subcategoryListDeleteModal')).modal('show');

 }


  deleteSubCategory(subCategoryDelId){

    this.service.subUrl = 'library/master_records/subcategory/deleteSubCategory';
    
    this.service.createPost(subCategoryDelId).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Sub Category deleted successfully'
        this.toasterMsg(type, title, body);
        this.SubcategoryForm.reset();                    
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Sub Category delete failed please try again'
        this.toasterMsg(type, title, body);
        this.SubcategoryForm.reset();        
      }
      this.getSubCategoryData(); 
      
    })

  }

  cancelUpdate() {
    this.maintitle = "Sub Category";
    this.subtitle = "Add Sub Category";
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
