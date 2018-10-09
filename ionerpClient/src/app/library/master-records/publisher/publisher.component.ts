import { Component, OnInit,ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { CharctersOnlyValidation } from '../../../custom.validators';
import { PostService } from '../../../services/post.service';
import { ToastService } from '../../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import * as $ from 'jquery';
@Component({
  selector: 'app-publisher',
  templateUrl: './publisher.component.html',
  styleUrls: ['./publisher.component.css']
})
export class PublisherComponent implements OnInit {

  constructor(private service: PostService,private toast: ToastService) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;

  publisherList;
  tosterconfig;
  publisherId;
  supplierId;

  pubDelId;
  supDelId;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  private publisherSupplierForm = new FormGroup({

    publisherSelect:new FormControl('',[
      Validators.required
    ]),
    publisherName:new FormControl('',[
      Validators.required
    ]),
    address:new FormControl('',[
      // Validators.required
    ]),
    city:new FormControl('',[
      Validators.required
    ]),
    publisherstate:new FormControl('',[
      Validators.required
    ]),
    country:new FormControl('',[
      // Validators.required
    ]),
    phoneNumber:new FormControl('',[
      Validators.required,
      CharctersOnlyValidation.DigitsOnlyMobileNumber
    ]),
    fax:new FormControl('',[
      // Validators.required
    ]),
    email:new FormControl('',[
      Validators.required
    ]),
    additionalInfo:new FormControl('',[
      // Validators.required
    ]),
  })


  get publisherSelect(){
    return this.publisherSupplierForm.get('publisherSelect');
  }

  get publisherName(){
    return this.publisherSupplierForm.get('publisherName');
  } 

  get address(){
    return this.publisherSupplierForm.get('address');
  }

  get city(){
    return this.publisherSupplierForm.get('city');
  }

  get publisherstate(){
    return this.publisherSupplierForm.get('publisherstate');
  }

  get country(){
    return this.publisherSupplierForm.get('country');
  }

  get phoneNumber(){
    return this.publisherSupplierForm.get('phoneNumber');
  }

  get fax(){
    return this.publisherSupplierForm.get('fax');
  }

  get email(){
    return this.publisherSupplierForm.get('email');
  }

  get additionalInfo(){
    return this.publisherSupplierForm.get('additionalInfo');
  }

  ngOnInit() {
    this.maintitle = "Publisher / Supplier";
    this.subtitle = "Add Publisher / Supplier";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.getPublisherDetails();
  }


  getPublisherDetails(){

    this.service.subUrl = 'library/master_records/publisher/getPublisherDetails';
    this.service.getData().subscribe(response => {
      this.publisherList = response.json(); 
      this.cancelUpdate();         
      this.tableRerender();
      this.dtTrigger.next();
      
    });

  }

  savePublisherSupplierData(publisherForm){

    this.service.subUrl = 'library/master_records/publisher/savePublisherSupplierData';

    let postdata = {

      'publisherSupplier' : publisherForm.value.publisherSelect,
      'publisherSupplierName' : publisherForm.value.publisherName,
      'publisherSupplierAddress' : publisherForm.value.address,
      'publisherSupplierCity' : publisherForm.value.city,
      'publisherSupplierState' : publisherForm.value.publisherstate,
      'publisherSupplierCountry' : publisherForm.value.country,
      'publisherSupplierPhone' : publisherForm.value.phoneNumber,
      'publisherSupplierFax' : publisherForm.value.fax,
      'publisherSupplierEmail' : publisherForm.value.email,
      'publisherSupplierAddinfo': publisherForm.value.additionalInfo
    }

    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New Publisher / Supplier added'
        this.toasterMsg(type, title, body);        
        this.publisherSupplierForm.reset();  
             
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New Publisher / Supplier add failed please try again'
        this.toasterMsg(type, title, body);       
        this.publisherSupplierForm.reset();
      }
      this.getPublisherDetails(); 
      this.cancelUpdate();
      this.tableRerender();
      this.dtTrigger.next();
    });
  }

 

  editPublisherOrSupplier(editElement: HTMLElement,event){

    this.subtitle = 'Edit Publisher / Supplier';
    this.isSaveHide = true;
    this.isUpdateHide = false;

    this.publisherId = editElement.getAttribute('pubId');
    this.supplierId = editElement.getAttribute('supId');
       
    let publisherSupplierName = editElement.getAttribute('pubSupName');
    let publisherSupplierAddress = editElement.getAttribute('pubSupAddress');
    let publisherSupplierCity = editElement.getAttribute('pubSupCity');
    let publisherSupplierState = editElement.getAttribute('pubSupState');
    let publisherSupplierCountry = editElement.getAttribute('pubSupCountry');
    let publisherSupplierMobile = editElement.getAttribute('pubSupMobile');
    let publisherSupplierFax = editElement.getAttribute('pubSupFax');
    let publisherSupplierEmail = editElement.getAttribute('pubSupEmail');
    let publisherSupplierAdditionalInfo = editElement.getAttribute('pubSupAdditionalInfo');

    if( this.publisherId != null){
      this.publisherSelect.setValue('Publisher');
    } else if( this.supplierId != null) {
      this.publisherSelect.setValue('Supplier');
    }
    this.publisherName.setValue(publisherSupplierName);
    this.address.setValue(publisherSupplierAddress);
    this.city.setValue(publisherSupplierCity);
    this.publisherstate.setValue(publisherSupplierState);
    this.country.setValue(publisherSupplierCountry);
    this.phoneNumber.setValue(publisherSupplierMobile);
    this.fax.setValue(publisherSupplierFax);
    this.email.setValue(publisherSupplierEmail);
    this.additionalInfo.setValue(publisherSupplierAdditionalInfo);

  }

  updatePublisherSupplierData(pubSupForm){

    this.service.subUrl = 'library/master_records/publisher/updatePublisherSupplierData';

    let postdata = {

      'publisherId' : this.publisherId,
      'supplierId' : this.supplierId,
      'publisherSupplier' : pubSupForm.value.publisherSelect,
      'publisherSupplierName' : pubSupForm.value.publisherName,
      'publisherSupplierAddress' : pubSupForm.value.address,
      'publisherSupplierCity' : pubSupForm.value.city,
      'publisherSupplierState' : pubSupForm.value.publisherstate,
      'publisherSupplierCountry' : pubSupForm.value.country,
      'publisherSupplierPhone' : pubSupForm.value.phoneNumber,
      'publisherSupplierFax' : pubSupForm.value.fax,
      'publisherSupplierEmail' : pubSupForm.value.email,
      'publisherSupplierAddinfo': pubSupForm.value.additionalInfo
    }

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Publisher / Supplier updated successfully'
        this.toasterMsg(type, title, body);
        this.publisherSupplierForm.reset();   
        // this.cancelUpdate();   
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Publisher / Supplier update failed please try again'
        this.toasterMsg(type, title, body);
        this.publisherSupplierForm.reset();
        
      }
      this.getPublisherDetails(); 
      this.cancelUpdate();
      
    })

  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {    

    this.pubDelId = deleteElement.getAttribute('pubId');
    this.supDelId = deleteElement.getAttribute('supId');
   (<any>jQuery('#publisherSupplierListDeleteModal')).modal('show');

 }

  deletePublisherSupplier(){

    this.service.subUrl = 'library/master_records/publisher/deletePublisherSupplierData';

    let postdata = {
      'publisherId' : this.pubDelId,
      'supplierId' : this.supDelId
    }

    this.service.createPost(postdata).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Category deleted successfully'
        this.toasterMsg(type, title, body);
        this.publisherSupplierForm.reset();                    
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Category delete failed please try again'
        this.toasterMsg(type, title, body);
        this.publisherSupplierForm.reset();        
      }
      this.getPublisherDetails(); 
      this.cancelUpdate();
      
    })  
    

  }

  cancelUpdate() {
    this.maintitle = "Publisher / Supplier";
    this.subtitle = "Add Publisher / Supplier";
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
