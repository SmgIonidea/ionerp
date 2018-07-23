import { Component, OnInit,ElementRef,ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { CharctersOnlyValidation } from '../../custom.validators';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';

@Component({
  selector: 'app-route-list',
  templateUrl: './route-list.component.html',
  styleUrls: ['./route-list.component.css']
})
export class RouteListComponent implements OnInit {

  constructor(private service: PostService,
    private toast: ToastService) { }


  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();

  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  tosterconfig;

  routeData; //inital data 
  routeListData;
  routeListId;

  private routeList = new FormGroup({
    routeNameList:new FormControl('',[
      Validators.required
    ]),
    routeTitle:new FormControl('',[
      Validators.required
    ]),
    routeAmount:new FormControl('',[
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
      
    ])
  })

  get routeNameList(){
    return this.routeList.get('routeNameList');
  }

  get routeTitle(){
    return this.routeList.get('routeTitle');
  }

  get routeAmount(){
    return this.routeList.get('routeAmount');
  }

  ngOnInit() {

    this.maintitle = "Route List";
    this.subtitle = "Create New Route List";
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.getExistingRoutes();   
    this.service.subUrl = 'transport/Route_list/getExistingRouteNames';
    this.service.getData().subscribe(response => {
      this.routeListData = response.json(); 
    })
    
  }

  //fetch initial data
  getExistingRoutes(){

    this.service.subUrl = 'transport/Route_list/fetchRouteListDetails';
    this.service.getData().subscribe(response => {
      this.routeData = response.json();      
      this.tableRerender();
      this.dtTrigger.next();
    });
  }

  //to save routes and via route list with amount
  saveRouteList(routeListForm){

    this.service.subUrl = 'transport/Route_list/saveRouteListData';

    let postdata ={
      'route' : routeListForm.value.routeNameList,
      'route_title' : routeListForm.value.routeTitle,
      'amount' : routeListForm.value.routeAmount
    }

    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New route list added'
        this.toasterMsg(type, title, body);        
        this.routeList.reset();
        this.getExistingRoutes();
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New route list add failed please try again'
        this.toasterMsg(type, title, body);       
        this.routeList.reset();
      }
      this.tableRerender();
      this.dtTrigger.next();
    });
    
  }

  updateRouteList(routeListForm){

    this.service.subUrl = 'transport/Route_list/updateRouteList';
    let postdata = {
      'routeId': this.routeListId,
      'route': routeListForm.value.routeNameList,
      'route_title' : routeListForm.value.routeTitle,
      'amount' : routeListForm.value.routeAmount 
    }
    
    // alert(JSON.stringify(postdata));

    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Route updated successfully'
        this.toasterMsg(type, title, body);
        this.routeList.reset();   
        this.cancelUpdate();   
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Route update failed please try again'
        this.toasterMsg(type, title, body);
        this.routeList.reset();
        this.cancelUpdate();
      }
      this.getExistingRoutes();
      
    })

  }

  editRouteList(editElement: HTMLElement){

    this.routeListId = editElement.getAttribute('routeListId');
    // alert(this.routeListId);
    this.subtitle = 'Edit Route List';
    this.isSaveHide = true;
    this.isUpdateHide = false;
    let route = editElement.getAttribute('route');
    let routeTitle = editElement.getAttribute('routeVia');
    let routeAmount = editElement.getAttribute('amount');

    this.routeNameList.setValue(route); 
    this.routeTitle.setValue(routeTitle);
    this.routeAmount.setValue(routeAmount);
  }

  cancelUpdate() {
    this.maintitle = "Route List";
    this.subtitle = "Create New Route List";
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  //delete warning to have a popup modal for comfirmation
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {    

    this.routeListId = deleteElement.getAttribute('routeListId');
   (<any>jQuery('#routeListDeleteModal')).modal('show');

 }

 deleteRouteListData(routeListdelId){

  this.service.subUrl = 'transport/Route_list/deleteStatusUpdate';
  let deleteRouteId = routeListdelId;
  // alert(JSON.stringify(deleteRouteId));

  this.service.updatePost(deleteRouteId).subscribe(response => {

    if (response.json().status == 'ok') {
      let type = 'success';
      let title = 'Delete Success';
      let body = 'Route deleted successfully'
      this.toasterMsg(type, title, body);
      this.routeList.reset();                    
    } else {
      let type = 'error';
      let title = 'Delete Fail';
      let body = 'Route delete failed please try again'
      this.toasterMsg(type, title, body);
      this.routeList.reset();        
    }
    this.getExistingRoutes();
    
  })  

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
