import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'main-header',
  templateUrl: './main-header.component.html',
  styleUrls: ['./main-header.component.css']
})
export class MainHeaderComponent {


  title = localStorage.getItem('programDropdown');
  Id = localStorage.getItem('programDropdownId');

  programType: Array<{ id: number, text: string }> = [{ id: 1, text: "College of Engineering" },
  { id: 2, text: "College of Nursing Science" },
  { id: 3, text: "School of Commerce" },
  { id: 4, text: 'College of Management Studies' },
  ];

  submit(event) {

    var programData;
    programData = JSON.parse(JSON.stringify(this.programType));

    programData.forEach(programElement => {
      if (programElement.id == event) {
        localStorage.setItem('programDropdown', programElement.text);
        localStorage.setItem('programDropdownId', programElement.id);



      }
    });

  }

  reload(){
    // this.head.title;
    // location.reload();
    // window.location.reload();
    this.title = localStorage.getItem('programDropdown')
  }
}