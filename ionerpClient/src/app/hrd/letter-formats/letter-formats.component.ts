import { Component, OnInit } from '@angular/core';


@Component({
  selector: 'app-letter-formats',
  templateUrl: './letter-formats.component.html',
  styleUrls: ['./letter-formats.component.css']
})
export class LetterFormatsComponent implements OnInit {

  constructor() { }

  maintitle1;
  maintitle2;
  maintitle3;
  editmail1;
  editmail2;
  editmail3;

  ngOnInit() {

    this.maintitle1 = "Short Listed Candidate Email Format";
    this.maintitle2 = "Offer Letter Format";
    this.maintitle3 = "Termination Format";
    this.editmail1 = "Edit Short Listed Candidate Email Format";
    this.editmail2 = "Edit Offer Letter Format";
    this.editmail3 = "Edit Termination Format";

  }
 
}
