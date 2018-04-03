import { Component, OnInit } from '@angular/core';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { IMultiSelectSettings } from 'angular-2-dropdown-multiselect';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';


@Component({
  selector: 'app-sendsms',
  templateUrl: './sendsms.component.html',
  styleUrls: ['./sendsms.component.css']
})
export class SendsmsComponent implements OnInit {

  constructor() { }

  maintitle;
  modalTitle;
  Add_EditTitle;
  btnTitle;

  groupName = [];

  myTexts: IMultiSelectTexts = {};
  myClass: IMultiSelectTexts = {};
  myStu: IMultiSelectTexts = {};

  ngOnInit() {

    this.maintitle = "Send SMS";
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
      "name": "Select"
    },
    {
      "name": "Group 1"
    },
    {
      "name": "Group 2"
    },
    {
      "name": "Group 3"
    },
  ]

  group1 = [
    {
      "name": "member 1"
    },
    {
      "name": "member 2"
    },
    {
      "name": "member 3"
    },
  ]

  group2 = [
    {
      "name": "member 101"
    },
    {
      "name": "member 102"
    },
    {
      "name": "member 103"
    },
  ]

  group3 = [
    {
      "name": "member 201"
    },
    {
      "name": "member 202"
    },
    {
      "name": "member 203"
    },
  ]

  selectedGroup = this.group[0].name;

  onChange(group) {
        
    if (this.selectedGroup == this.group[0].name) {

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
