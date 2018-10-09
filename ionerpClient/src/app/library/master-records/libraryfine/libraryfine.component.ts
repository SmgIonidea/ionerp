import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { CharctersOnlyValidation } from '../../../custom.validators';
import { PostService } from '../../../services/post.service';
import { ToastService } from '../../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';

@Component({
  selector: 'app-libraryfine',
  templateUrl: './libraryfine.component.html',
  styleUrls: ['./libraryfine.component.css']
})
export class LibraryfineComponent implements OnInit {

  constructor(private service: PostService,private toast: ToastService) { }

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;

  tosterconfig;
  libraryFineData;
  libFineEditId;
  libFineDelId;

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  private libraryFineForm = new FormGroup({

    userType:new FormControl('',[
      Validators.required
    ]),
    numberOfDays:new FormControl('',[
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ]),
    fineAmount:new FormControl('',[
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ]),
    duration:new FormControl('',[
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ]),
    
  })

  get userType(){
    return this.libraryFineForm.get('userType');
  }

  get numberOfDays(){
    return this.libraryFineForm.get('numberOfDays');
  }

  get fineAmount(){
     return this.libraryFineForm.get('fineAmount');
  }

  get duration(){
    return this.libraryFineForm.get('duration');
  }

  ngOnInit() {

    this.maintitle = "Library Fine";
    this.subtitle = "Add Library Fine List";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.getLibraryFineDetails();

  }

  getLibraryFineDetails(){

    this.service.subUrl = 'library/master_records/libraryfine/getLibraryFineDetails';
    this.service.getData().subscribe(response => {
      this.libraryFineData = response.json();      
      this.tableRerender();
      this.dtTrigger.next();
    });

  }


  saveLibraryFineDetails(libfineform){

    this.service.subUrl = 'library/master_records/libraryfine/saveLibraryFineList';

    let postdata = {
      
      'usertype' : libfineform.value.userType,
      'noofdays' : libfineform.value.numberOfDays,
      'fineamount': libfineform.value.fineAmount,
      'duration' : libfineform.value.duration      
    }
    
    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New library fine details added'
        this.toasterMsg(type, title, body);        
        this.libraryFineForm.reset();  
             
      } else if(response.json().status == 'update') {

        let type = 'success';
        let title = 'Update Success';
        let body = 'Library fine details Updated'
        this.toasterMsg(type, title, body);        
        this.libraryFineForm.reset();
      } else {

        let type = 'error';
        let title = 'Add Fail';
        let body = 'New library fine details add failed please try again'
        this.toasterMsg(type, title, body);       
        this.libraryFineForm.reset();
      }
      this.getLibraryFineDetails(); 
      this.tableRerender();
      this.dtTrigger.next();
    });

  }


  editlibraryFine(editElement: HTMLElement){

    this.subtitle = 'Edit Library Fine List';
    this.isSaveHide = true;
    this.isUpdateHide = false;

    this.libFineEditId = editElement.getAttribute('libfineId');

    let usertype = editElement.getAttribute('userfine');
    let numofdays = editElement.getAttribute('numofdays');
    let fineamount = editElement.getAttribute('fineamt');
    let duration = editElement.getAttribute('fineduration');

    this.userType.setValue(usertype);
    this.numberOfDays.setValue(numofdays);
    this.fineAmount.setValue(fineamount);
    this.duration.setValue(duration);

  }

  updateLibraryFineDetails(libraryFineForm){

    this.service.subUrl = 'library/master_records/libraryfine/updateLibraryFineList';

    let postdata = {

      'libfineid': this.libFineEditId,
      'usertype' : libraryFineForm.value.userType,
      'noofdays' : libraryFineForm.value.numberOfDays,
      'fineamount': libraryFineForm.value.fineAmount,
      'duration' : libraryFineForm.value.duration      
    }

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Library fine details updated successfully'
        this.toasterMsg(type, title, body);
        this.libraryFineForm.reset();   
        this.cancelUpdate();   
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Library fine details update failed please try again'
        this.toasterMsg(type, title, body);
        this.libraryFineForm.reset();
        this.cancelUpdate();
      }
      this.getLibraryFineDetails(); 
      
    })

  }

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {    

    this.libFineDelId = deleteElement.getAttribute('libFineId');
   (<any>jQuery('#libFineListDeleteModal')).modal('show');

 }

  deleteLibraryFineList(libFineDelId){

    this.service.subUrl = 'library/master_records/libraryfine/deletelibFineList';

    this.service.createPost(libFineDelId).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Library fine details deleted successfully'
        this.toasterMsg(type, title, body);
        this.libraryFineForm.reset();                    
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Library fine details delete failed please try again'
        this.toasterMsg(type, title, body);
        this.libraryFineForm.reset();        
      }
      this.getLibraryFineDetails(); 
      
    })  


  }

  cancelUpdate() {
    this.maintitle = "Library Fine";
    this.subtitle = "Add Library Fine List";
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
