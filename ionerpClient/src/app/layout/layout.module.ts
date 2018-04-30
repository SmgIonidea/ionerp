import { MainHeaderComponent } from './main-header/main-header.component';
import { MainSidenavComponent } from './main-sidenav/main-sidenav.component';
import { MenuNavbarComponent } from './menu-navbar/menu-navbar.component';
import { FooterComponent } from './footer/footer.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { CharLimiterPipe } from "./../services/char-limiter.pipe";
import { FormsModule } from '@angular/forms';
import {PopoverModule} from "ngx-popover";
@NgModule({
  imports: [
    
    CommonModule,
    RouterModule,
    FormsModule,
    PopoverModule
  ],
  declarations: [
    MainHeaderComponent,
    MainSidenavComponent,
    MenuNavbarComponent,
    FooterComponent,
    CharLimiterPipe
  ],
  exports: [MainHeaderComponent,MainSidenavComponent,MenuNavbarComponent,FooterComponent],
  bootstrap: [MainHeaderComponent,MainSidenavComponent,MenuNavbarComponent,FooterComponent]
})
export class LayoutModule { }
 