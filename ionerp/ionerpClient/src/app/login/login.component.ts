import { Component, OnInit, Inject } from '@angular/core';
import { ActivatedRoute, Params, Router } from "@angular/router";
import { PostService } from '../services/post.service';
import { DOCUMENT } from '@angular/platform-browser';

@Component({
  // selector: 'login',
  template: '',
  styleUrls: ['./login.component.css']
})

export class LoginComponent {
  private sub: any;
  baseUrl;
constructor(private route: ActivatedRoute, private router:Router, private service: PostService, @Inject(DOCUMENT) private document: any){}
ngOnInit() {
  this.service.subUrl = 'Login/getBaseUrl';
        this.service.getData().subscribe(response => {
          this.baseUrl = response.json()[0]['base_url'];
        });
this.sub = this.route
      .queryParams
      .subscribe(params => {
        let user_details={
          'identity': params[btoa("username").replace("=", "")],
          'password': params[btoa("password").replace("=", "")],
          'student_portal' : false
        };
        
        this.service.subUrl = 'Login/index';
        this.service.createPost(user_details).subscribe(response => {
           if(response.json().isLoggedIn){

              (response.json().password != null) && localStorage.setItem('password',response.json().password);
              localStorage.setItem('loginTime',JSON.stringify(new Date().valueOf()));
              (response.json().isLoggedIn != null) && localStorage.setItem('isLoggedIn',response.json().isLoggedIn);
              (response.json().admin != null) && localStorage.setItem('admin',response.json().admin);
              (response.json().course_owner != null) && localStorage.setItem('course_owner',response.json().course_owner);
              (response.json().program_owner != null) && localStorage.setItem('program_owner',response.json().program_owner);
              (response.json().chairman != null) && localStorage.setItem('chairman',response.json().chairman);
              (response.json().student != null) && localStorage.setItem('student',response.json().student);
              (response.json().id != null) && localStorage.setItem('id',response.json().id);
              (response.json().username != null) && localStorage.setItem('username',response.json().username);
              (response.json().email != null) && localStorage.setItem('email',response.json().email);
              (response.json().title != null) && localStorage.setItem('title',response.json().title);
              (response.json().first_name != null) && localStorage.setItem('first_name',response.json().first_name);
              (response.json().last_name != null) && localStorage.setItem('last_name',response.json().last_name);
              (response.json().user_dept_id != null) && localStorage.setItem('user_dept_id',response.json().user_dept_id);
              (response.json().user_dept_name != null) && localStorage.setItem('user_dept_name',response.json().user_dept_name);
              (response.json().base_dept_id != null) && localStorage.setItem('base_dept_id',response.json().base_dept_id);
              (response.json().base_dept_name != null) && localStorage.setItem('base_dept_name',response.json().base_dept_name);
              (response.json().assigned_dept_id != null) && localStorage.setItem('assigned_dept_id',response.json().assigned_dept_id);
              (response.json().assigned_dept_name != null) && localStorage.setItem('assigned_dept_name',response.json().assigned_dept_name);
              (response.json().user_qualification != null) && localStorage.setItem('user_qualification',response.json().user_qualification);
              //Dropdown data starts here
              (response.json().dept_id != null) && localStorage.setItem('deptDropdownId',response.json().dept_id);
              (response.json().dept_name != null) && localStorage.setItem('deptDropdown',response.json().dept_name);
              (response.json().pgm_id != null) && localStorage.setItem('programDropdownId',response.json().pgm_id);
              (response.json().pgm_name != null) && localStorage.setItem('programDropdown',response.json().pgm_name);
              (response.json().crclm_id != null) && localStorage.setItem('currDropdownId',response.json().crclm_id);
              (response.json().crclm_name != null) && localStorage.setItem('currDropdown',response.json().crclm_name);
              (response.json().crclm_term_id != null) && localStorage.setItem('termDropdownId',response.json().crclm_term_id);
              (response.json().term_name != null) && localStorage.setItem('termDropdown',response.json().term_name);
              (response.json().crs_id != null) && localStorage.setItem('courseDropdownId',response.json().crs_id);
              (response.json().crs_name != null) && localStorage.setItem('courseDropdown',response.json().crs_name);
              (response.json().section_id != null) && localStorage.setItem('sectionDropdownId',response.json().section_id);
              (response.json().section_name != null) && localStorage.setItem('sectionDropdown',response.json().section_name);
              
              this.router.navigate(['/content']);
           }else{
             this.document.location.href = this.baseUrl+'login';
           }
        });
      })
}
  
}
