import { Injectable, Inject } from '@angular/core';
import { Router, CanActivate, RouterStateSnapshot, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs/Observable';
import { DOCUMENT } from '@angular/platform-browser';
import { PostService } from '../services/post.service';


@Injectable()
export class AuthGuard implements CanActivate {
    baseUrl;
    constructor(private router: Router, @Inject(DOCUMENT) private document: any, private service: PostService) {
     }

    canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        let millisecond = new Date().valueOf()-JSON.parse(localStorage.getItem('loginTime'));
        // console.log(millisecond/3600000);
        if(millisecond/3600000 > 6){       //Expires Localstorage after 6 hours of login
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
        }
        if (localStorage.getItem('isLoggedIn')) {
            // logged in so return true
            return true;
        }

        // not logged in so redirect to login page
        this.service.subUrl = 'Login/getBaseUrl';
        this.service.getData().subscribe(response => {
          this.baseUrl = response.json()[0]['base_url'];
          this.document.location.href = this.baseUrl+'login';
        });
        // this.router.navigate(['/login'], { queryParams: { returnUrl: state.url }});
        return false;
    }
}