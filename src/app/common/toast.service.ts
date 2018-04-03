import { Injectable } from '@angular/core';
import { ToasterContainerComponent, 
  ToasterModule, ToasterService, Toast, ToasterConfig
} from 'angular2-toaster';
@Injectable()
export class ToastService {

  constructor(public toast:ToasterService) { }
  /* Toast Message Variables */
  public toastType;
  public toastTitle;
  public toastBody;
/* Toaster Configuration Variables */
  public showCloseButton:boolean;
  public tapDismiss:boolean;
  public toastAnimation:string;
  public toastPosition:string;
  public timeLimit:number;
/* Toaster Config Declaration */
  public toastconfig  =   new ToasterConfig({});
 
  /* Toast Message Getter Function */
get toastMsg(){
   let toastObj: Toast = {
    type: this.toastType,
    title: this.toastTitle,
    body: this.toastBody
};
return this.toast.pop(toastObj);
}

}
