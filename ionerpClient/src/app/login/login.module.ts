import { LoginComponent } from './login.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ToasterModule, ToasterService } from 'angular2-toaster';
import { ToastComponent } from '.././toast/toast.component';
@NgModule({
  imports: [
    CommonModule,
    FormsModule, ReactiveFormsModule,
    ToasterModule
  ],
  declarations: [
    LoginComponent
  ],
  bootstrap: [LoginComponent]
})
export class LoginModule { }
