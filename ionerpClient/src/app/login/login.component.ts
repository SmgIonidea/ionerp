import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl, Validators } from '@angular/forms'; // for form validation
import { ToastService } from '../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { Http, Response } from '@angular/http';
import { PostService } from '../services/post.service';
import { ActivatedRoute, RouterModule, Params, Router } from "@angular/router";
import { toBase64String } from '@angular/compiler/src/output/source_map';
// import { ToasterConfig } from 'angular2-toaster';
// import { ToastService } from '../../common/toast.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  constructor(
    private service: PostService,
    private toast: ToastService,

    private http: Http,
    private router: Router,

  ) { }
  loginData;
  tosterconfig;
  roleName;
  ngOnInit() {
  }

  private studentform = new FormGroup({
    username: new FormControl('', [Validators.required]),
    password: new FormControl('', [Validators.required]),

  });
  get username() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.studentform.get('username');
  }
  get password() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.studentform.get('password');
  }
  studentlogin(formdata) {
    let username = formdata.value.username;
    let password = formdata.value.password;

    let loginData = {

      'identity': username,
      'password': password,
      // 'student_portal': true
    }
    this.service.subUrl = 'Login/index';

    this.service.createPost(loginData).subscribe(response => {
      if (response.json().status == 'ok') {
        this.loginData = response.json();


        if (this.loginData.isLoggedIn) {
          localStorage.setItem('loginTime', JSON.stringify(new Date().valueOf()));
          localStorage.setItem('isLoggedIn', response.json().isLoggedIn);
          // localStorage.setItem('admin', response.json().admin);
          // localStorage.setItem('course_owner', response.json().course_owner);
          // localStorage.setItem('program_owner', response.json().program_owner);
          // localStorage.setItem('chairman', response.json().chairman);
          // localStorage.setItem('student', response.json().student);
          var user_roles = response.json().role.split(',');
          console.log(user_roles);
          user_roles.forEach(roles => {
            if (roles == "admin") {
              localStorage.setItem('isAdmin', roles);
            }
            else if (roles == "BOS") {
              localStorage.setItem('isBos', roles);
            } else if (roles == "Chairman") {
              localStorage.setItem('isChairman', roles);
            }

          });
          if(response.json().role == 'Student' || response.json().role == 'Program Owner' || response.json().role == 'Course Owner'){
          localStorage.setItem('role', response.json().role);
          }
          localStorage.setItem('id', response.json().id);
          localStorage.setItem('username', response.json().username);
          localStorage.setItem('email', response.json().email);
          localStorage.setItem('title', response.json().title);
          localStorage.setItem('first_name', response.json().first_name);
          localStorage.setItem('last_name', response.json().last_name);
          localStorage.setItem('user_dept_id', response.json().user_dept_id);
          // localStorage.setItem('user_dept_name', response.json().user_dept_name);
          localStorage.setItem('base_dept_id', response.json().base_dept_id);
          // localStorage.setItem('base_dept_name', response.json().base_dept_name);
          // localStorage.setItem('assigned_dept_id', response.json().assigned_dept_id);
          // localStorage.setItem('assigned_dept_name', response.json().assigned_dept_name);
          localStorage.setItem('user_qualification', response.json().user_qualification);
          console.log(localStorage)
          this.router.navigate(['/content']);
        }
      }
      else {
        let type = 'error';
        let title = 'Authentication failed';
        let body = 'Try Again'
        this.toasterMsg(type, title, body);
        this.studentform.reset();
      }

    });

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
