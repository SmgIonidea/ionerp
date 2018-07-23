import { Component, OnInit, ElementRef,Input, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { ActivatedRoute, Params, Event } from "@angular/router";
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { Subject } from 'rxjs/Rx';
import { Title } from '@angular/platform-browser';

@Component({
  selector: 'app-route',
  templateUrl: './route.component.html',
  styleUrls: ['./route.component.css']
})
export class RouteComponent implements OnInit {

  constructor(public titleService: Title,
    private service: PostService,
    private activatedRoute: ActivatedRoute,
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
  public routeTitle: any;
  tosterconfig;
  // @Input('routeId') delRouteId;
  routeDelId; //global variable to get delete Id
  routeUpdateId;


  routeData; //variable for route names

  //validation for required fields
  private route = new FormGroup({
    routeName: new FormControl('', [
      Validators.required
    ])
  })

  get routeName() {
    return this.route.get('routeName');
  }

  ngOnInit() {
    this.maintitle = "Route";
    this.subtitle = "Create New Route";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    //function call for initial data fetch
    this.getRouteName();
  }

  //changes the title and button names on click of edit
  editRoute(editElement: HTMLElement) {

    this.subtitle = 'Edit Route';
    this.isSaveHide = true;
    this.isUpdateHide = false;
    let routeTitle = editElement.getAttribute('routeTitle');
    this.routeUpdateId = editElement.getAttribute('routeId');

    this.routeName.setValue(routeTitle);

  }

  //Resets the title and buttons on click of cancel during Updating data
  cancelUpdate() {
    this.maintitle = "Route";
    this.subtitle = "Create New Route";
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  //service to fetch route name
  getRouteName() {

    this.service.subUrl = 'transport/Route/getRouteNames';
    this.service.getData().subscribe(response => {
      this.routeData = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    });
  }

  //To save the route title on click of save button
  saveRouteTitle(route) {

    this.service.subUrl = 'transport/Route/saveRoute';   
    let routeData = route.value;
  
    this.service.createPost(routeData).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'New route name added'
        this.toasterMsg(type, title, body);        
        this.route.reset();
        this.getRouteName();
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New route add failed please try again'
        this.toasterMsg(type, title, body);       
        this.route.reset();
      }
      this.tableRerender();
      this.dtTrigger.next();
    });

  }

  //delete warning to have a popup modal for comfirmation
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {    

     this.routeDelId = deleteElement.getAttribute('routeId');
    (<any>jQuery('#routesDeleteModal')).modal('show');
  }

  //delete functionality on confirmation
  deleteRouteData(delRoutesId){
    
    this.service.subUrl = 'transport/Route/updateRouteDeleteStatus';
    let delId = delRoutesId;

    this.service.updatePost(delId).subscribe(response => {

      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Route deleted successfully'
        this.toasterMsg(type, title, body);
        this.route.reset();                    
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Route delete failed please try again'
        this.toasterMsg(type, title, body);
        this.route.reset();        
      }
      this.getRouteName();
      
    })    
  }

  //function to update route name
  updateRoute(routeForm){
    
    this.service.subUrl = 'transport/Route/updateRouteTitle';
    let postdata = {      
      'route_Id' : this.routeUpdateId,
      'routeName' : routeForm.value
    }
    this.service.updatePost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Update Success';
        let body = 'Route updated successfully'
        this.toasterMsg(type, title, body);
        this.route.reset();
        this.cancelUpdate();    
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Route update failed please try again'
        this.toasterMsg(type, title, body);
        this.route.reset();
        this.cancelUpdate();    
      }
      this.getRouteName();
    })

  }

  //Renders data into datatable
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
