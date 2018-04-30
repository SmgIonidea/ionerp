import { AppComponent } from './../../app.component';
import { Component, OnInit, HostListener } from '@angular/core';
@Component({
  selector: 'main-sidenav',
  templateUrl: './main-sidenav.component.html',
  styleUrls: ['./main-sidenav.component.css']
})
export class MainSidenavComponent implements OnInit {
  isScrolled: boolean;
  @HostListener('window:scroll', ['$event']) onScrollEvent($event) {
    // console.log($event);
    let pos = scrollY;
    if (pos > 50) {
      this.isScrolled = true;
      // console.log(pos);
    } else {
      this.isScrolled = false;
      // console.log(pos);
    }
  }
  is_student: boolean = false;
  is_admin: boolean = false;
  is_chairman: boolean = false;
  is_program_owner: boolean = false;
  is_course_owner: boolean = false;
  ngOnInit() {

    // let loginData = JSON.parse(localStorage.getItem('student_loginData'));
    // alert(localStorage.getItem('student'));
    // alert(localStorage.getItem('admin'));

    if (localStorage.getItem('role') == "Student")
      this.is_student = true;

    if (localStorage.getItem('isAdmin') == "Admin")
      this.is_admin = true;

    if (localStorage.getItem('isChairman') == "Chairman")
      this.is_chairman = true;

    if (localStorage.getItem('role') == "Program Owner")
      this.is_program_owner = true;

    if (localStorage.getItem('role') == "Course Owner")
      this.is_course_owner = true;
   
  }
}
