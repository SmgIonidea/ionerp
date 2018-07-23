import { Component, OnInit,ViewChild } from '@angular/core';
import { PostService } from '../../services/post.service';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { DataTableDirective } from 'angular-datatables';
import { RouterModule, ActivatedRoute, Params, Event } from '@angular/router';
import * as $ from 'jquery';
import { Subject } from 'rxjs/Rx';
import { FormGroup, FormControl, Validators } from '@angular/forms'; // for form validation

@Component({
  selector: 'app-driver-vehicle',
  templateUrl: './driver-vehicle.component.html',
  styleUrls: ['./driver-vehicle.component.css']
})
export class DriverVehicleComponent implements OnInit {
  vehicleList;
  driverList;
  driverVehicleList;
  tosterconfig;
  driverVehilce = [];
  driverVehicleId;
  size: number = 0;
  isSaveHide:boolean =true;
  isIconHide : boolean = false;
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();
  constructor(private service: PostService, private toast: ToastService,
    private activatedRoute: ActivatedRoute,) { }
 
  ngOnInit() {
    this.service.subUrl = 'transport/DriverVehicle/getDriverVehicleList';
    this.service.getData().subscribe(response => {
      this.driverVehicleList = response.json();
      this.tableRerender();
      this.dtTrigger.next();
      });
    
   

      this.service.subUrl = 'transport/DriverVehicle/getDriverList';
    this.service.getData().subscribe(response => {
      this.driverList = response.json();
     
      });
  }



 
// To show dropdown and buttons(save,cancel) and display course instructor
startEdit(id) {
  this.size++;
  let driverVehicleId = id;
  
  this.service.subUrl = 'transport/DriverVehicle/getDriverVehicle';
  this.service.createPost(driverVehicleId).subscribe(response => {
    this.driverVehilce = response.json();
    // this.driverVehilce.forEach(shows => {
    //   $('#select_' + id).val(shows.id);
    // })
  });
  $('#para_' + id).hide();
  $('#select_' + id).show();
  $('#submit_' + id).show();
  $('#cancel_' + id).show();
  $('#hide' + id).hide();
  $('#para1_' + id).hide();
}

//To hide dropdown and buttons(save,cancel)
save(id) {
  this.size--;
  // $('#para_' + id).show();
  // $('#select_' + id).hide();
  $('#submit_' + id).hide();
  $('#cancel_' + id).hide();
  $('#hide' + id).show();
  // $('#proceed').removeAttr("disabled");
  if (this.size == 0)
    $('#resize').removeClass("col-lg-2");
  // this.ngOnInit();

}

//To hide dropdown and buttons(save,cancel)
cancel(id) {
  this.size--;
  // this.getdropdowndata();
  $('#para_' + id).show();
  $('#select_' + id).hide();
  $('#submit_' + id).hide();
  $('#cancel_' + id).hide();
  $('#hide' + id).show();
  // this.ngOnInit();
  if (this.size == 0)
    $('#resize').removeClass("col-lg-2");

}
//To Get Row Id and Instructor ID
 save_id($event) {
 
   localStorage.setItem('dropIds', $event)
 
}

  // To resize the table when clicks edit
  resize() {
    $('#resize').removeAttr("style");
    $('#resize').addClass("col-lg-2");
    $('#re_size1').removeAttr("style");
    $('#re_size1').addClass("col-lg-1");
  }

  //To save driver 
  proceedDelivery(id) {
    
    // var drops = localStorage.getItem('dropIds');
   let vehicle = $('.vehicle').val();
  let driver = localStorage.getItem('dropIds')
 
    this.service.subUrl = 'transport/DriverVehicle/saveDriverVehicle';
    let postData = {
     'id':id,
     
      'driver':driver
    };
    this.service.createPost(postData).subscribe(response => {
      //  this.posts = response.json();
      // this.tableRerender();
      // this.dtTrigger.next();// Calling the DT trigger to manually render the table
      if (response.json().status == 'ok') {
        this.service.subUrl = 'transport/DriverVehicle/getDriverVehicleList';
        this.service.getData().subscribe(response => {
          this.driverVehicleList = response.json();
          this.tableRerender();
          this.dtTrigger.next();
          });
        let type = 'success';
        let title = 'Add Success';
        let body = 'Driver assigned'
        this.toasterMsg(type, title, body);
        let skillsSelect = $("#select_" + id + " option:selected").text();
        $('#para_' + id).html(skillsSelect);
        $('#select_' + id).hide();
        $('#para_' + id).show();

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Driver assigned add failed please try again'
        this.toasterMsg(type, title, body);

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

  // to get success msg on particular add,edit,delete functionality
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
