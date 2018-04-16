import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';
import { PostService } from './../../services/post.service';
import { Title } from '@angular/platform-browser';
import { DataTableDirective } from "angular-datatables";

@Component({
  selector: 'app-accounting',
  templateUrl: './accounting-group.component.html',
  styleUrls: ['./accounting-group.component.css']
})
export class AccountingGroupComponent implements OnInit {

  constructor(
    public titleService: Title,
    public service: PostService,
    // private toast: ToastService,
    private activatedRoute: ActivatedRoute,
    private router: Router,)  {
    
   }

  ngOnInit() {
  }

}
