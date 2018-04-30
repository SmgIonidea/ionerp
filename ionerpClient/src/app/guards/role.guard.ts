import { Injectable } from '@angular/core';
import { Router, CanActivate, RouterStateSnapshot, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs/Observable';

@Injectable()
export class RoleGuard implements CanActivate {
    constructor(private router: Router) { }
    is_student: boolean = false;
    is_admin: boolean = false;
    is_chairman: boolean = false;
    is_program_owner: boolean = false;
    is_course_owner: boolean = false;

    canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {

            if (localStorage.getItem('role') == "Student")
                this.is_student = true;

            if (localStorage.getItem('isAdmin') == "Admin")
                this.is_admin = true;

            if (localStorage.getItem('isChairman') == "Chairman")
                this.is_chairman = true;

            if (localStorage.getItem('program_owner') == "true")
                this.is_program_owner = true;

            if (localStorage.getItem('course_owner') == "true")
                this.is_course_owner = true;

            //Code accessing the page according to Role
            if(next.data.isAdmin && this.is_admin)
                return true;
            else if(next.data.isChairman && this.is_chairman)
                return true;
            else if(next.data.isProgramOwner && this.is_program_owner)
                return true;
            else if(next.data.isCourseOwner && this.is_course_owner)
                return true;
            else if(next.data.isStudent && this.is_student)
                return true;

            this.router.navigate(['/content']);
            return false;  
    }
}