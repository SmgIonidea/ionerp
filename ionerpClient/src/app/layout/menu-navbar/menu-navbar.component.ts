import { Component, OnInit, Inject } from '@angular/core';
import { ActivatedRoute, Params, Router } from "@angular/router";
import { DOCUMENT } from '@angular/platform-browser';
import { PostService } from "../../services/post.service";

@Component({
  selector: 'menu-navbar',
  templateUrl: './menu-navbar.component.html',
  styleUrls: ['./menu-navbar.component.css']
})
export class MenuNavbarComponent implements OnInit {

  constructor(@Inject(DOCUMENT) private document: any,private service: PostService) { }
  isIn;   // store state
  username:string;
  baseUrl;
  role = '';
  user_role: string;
  is_student: boolean = false;
  is_admin: boolean = false;
  is_chairman: boolean = false;
  is_program_owner: boolean = false;
  is_course_owner: boolean = false;
  toggleState() { // click handler
      let bool = this.isIn;
      this.isIn = bool === false ? true : false; 
  }


  ngOnInit() {

    this.service.subUrl = 'Login/getBaseUrl';
        this.service.getData().subscribe(response => {
          this.baseUrl = response.json()[0]['base_url'];
          
        });

//Onover to profile the caret symbol changes down to upwords

    $("#profile_tooltip div").click(            
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
                $(this).toggleClass('open');
                $('b', this).toggleClass("caret caret-up");                
            },
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
                $(this).toggleClass('open');
                $('b', this).toggleClass("caret caret-up");                
            });

    this.username = localStorage.getItem('title')+localStorage.getItem('first_name');
           
    if (localStorage.getItem('role') == "Student")
      this.is_student = true;

    if (localStorage.getItem('isAdmin') == "admin")
      this.is_admin = true;

    if (localStorage.getItem('isChairman') == "Chairman")
      this.is_chairman = true;

    if (localStorage.getItem('role') == "Program Owner")
      this.is_program_owner = true;

    if (localStorage.getItem('role') == "Course Owner")
      this.is_course_owner = true;

    if (this.is_admin)
      this.role = 'Admin';
    if (this.is_chairman){
      if(this.is_admin)
        this.role += '-';
      this.role += 'Chairman(HoD)';
    }
    if (this.is_program_owner){
      if(this.is_admin || this.is_chairman){
        this.role += '-';
      this.role += 'Program owner';
      }
      else{
        this.role = 'Program Owner';
      }
    }
    if (this.is_course_owner){
      if(this.is_admin || this.is_chairman || this.is_program_owner){
        this.role += '-';
      this.role += 'Course owner';
      }
      else{
        this.role = 'Course Owner';
      }
    }
    if (this.is_student){
      this.role += 'Student';
    }
  }

  navigateToIonCudos(){
    let username = btoa(localStorage.getItem("email")).replace("=", "");
    let password = btoa(localStorage.getItem("password")).replace("=", "");

    let userId = localStorage.getItem('id');
    let deptId = localStorage.getItem('deptDropdownId') || null;
    let pgmId = localStorage.getItem('programDropdownId') || null;
    let currId = localStorage.getItem('currDropdownId') || null;
    let termId = localStorage.getItem('termDropdownId') || null;
    let crsId = localStorage.getItem('courseDropdownId') || null;
    let sectionId = localStorage.getItem('sectionDropdownId') || null;

    let postData = {
      'userId': userId,
      'deptId': deptId,
      'pgmId': pgmId,
      'currId': currId,
      'termId': termId,
      'crsId': crsId,
      'sectionId': sectionId
    };
    this.service.subUrl = 'Login/updateSessionData';
    this.service.createPost(postData).subscribe(response => {    
    });

    //Remove local storage values
    localStorage.clear();
    
    this.document.location.href = this.baseUrl+'login/login_from_iondelivery/'+username+'/'+password;
  }

  logout(){
    let userId = localStorage.getItem('id');
    let deptId = localStorage.getItem('deptDropdownId') || null;
    let pgmId = localStorage.getItem('programDropdownId') || null;
    let currId = localStorage.getItem('currDropdownId') || null;
    let termId = localStorage.getItem('termDropdownId') || null;
    let crsId = localStorage.getItem('courseDropdownId') || null;
    let sectionId = localStorage.getItem('sectionDropdownId') || null;

    let postData = {
      'userId': userId,
      'deptId': deptId,
      'pgmId': pgmId,
      'currId': currId,
      'termId': termId,
      'crsId': crsId,
      'sectionId': sectionId
    };
    
    this.service.subUrl = 'Login/updateSessionData';
    this.service.createPost(postData).subscribe(response => {    
    });

    //Remove local storage values
      localStorage.clear();
    console.log(localStorage)
    this.document.location.href = '/ionerp/ionerpClient/dist/';
  }
}
