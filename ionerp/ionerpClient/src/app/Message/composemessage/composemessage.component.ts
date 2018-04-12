import { Component, OnInit } from '@angular/core';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { IMultiSelectSettings } from 'angular-2-dropdown-multiselect';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { FormsModule } from '@angular/forms';
import { FormGroup, FormControl, Validators } from '@angular/forms';

@Component({
  selector: 'app-composemessage',
  templateUrl: './composemessage.component.html',
  styleUrls: ['./composemessage.component.css']
})
export class ComposemessageComponent implements OnInit {

  constructor() { }

  maintitle;
  modalTitle;
  Add_EditTitle;
  btnTitle;

  myTexts: IMultiSelectTexts = {};
  myDept: IMultiSelectTexts = {};
  myPost: IMultiSelectTexts = {};
  myClass: IMultiSelectTexts = {};
  myStu: IMultiSelectTexts = {};

  

  private composemessage = new FormGroup({
    fileinput:new FormControl('',[
      Validators.required
    ]),
    subjectInput: new FormControl('',[
      Validators.required
    ]),
    staffSubInput: new FormControl('',[
      Validators.required
    ])
  })

  get fileinput(){
    return this.composemessage.get('fileinput');
  }

  get subjectInput(){
    return this.composemessage.get('subjectInput');
  }

  get staffSubInput(){
    return this.composemessage.get('staffSubInput');
  }

  getFileName(replaceElement: HTMLElement, splitElement: HTMLElement){
    var value = JSON.stringify($('#userdoc').val());
    
    //get filepath replace forward slash with backward slash
    var filePath = value.replace(/\\/g, "/");
    //remove backward slash and "
    
    var path = filePath.split('/').pop();
    path = path.replace('"', '');
    //get the fileName
    
    var fileName = JSON.stringify($('#fileinput').val(path));
    this.fileinput.setValue(path);
  }

  ngOnInit() {
    this.maintitle = "Compose Message";
    this.modalTitle = "Add Group";    
    this.Add_EditTitle = "Add Group";
    this.myTexts = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select User',
      allSelected: 'All selected',
    };
    this.myDept = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Dept.',
      allSelected: 'All selected',
    };
    this.myPost = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Post',
      allSelected: 'All selected',
    };
    this.myClass = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Class',
      allSelected: 'All selected',
    };
    this.myStu = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Student',
      allSelected: 'All selected',
    };    
  }
  source = [
    {
      "id": 1,
      "name": "Antonito",

    },
    {
      "id": 2,
      "name": "Big Horn",

    },
    {
      "id": 3,
      "name": "Sublette",

    },
    {
      "id": 4,
      "name": "Toltec",

    }
  ]
  group = [   
    {
      "name":"Select"
    },
    {     
      "name":"Group 1"
    },
    {
      "name":"Group 2"
    },
    {      
      "name":"Group 3"
    },
  ]

  group1 = [
    {
      "name":"member 1"
    },
    {
      "name":"member 2"
    },
    {
      "name":"member 3"
    },    
  ]

  group2 = [
    {
      "name":"member 101"
    },
    {
      "name":"member 102"
    },
    {
      "name":"member 103"
    },    
  ]

  group3 = [
    {
      "name":"member 201"
    },
    {
      "name":"member 202"
    },
    {
      "name":"member 203"
    },    
  ]


  selectedGroup = this.group[0].name;

  onChange(group) {

    if(this.selectedGroup == this.group[0].name){

      this.Add_EditTitle = "Add Group"; 
      this.modalTitle = "Add Group";
      this.btnTitle = "Create Group"

    } else {

      this.Add_EditTitle = "Edit Group";
      this.modalTitle = "Edit Group";
      this.btnTitle = "Update Group";
    }
    
  }
}
