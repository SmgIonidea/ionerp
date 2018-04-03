/**
    ----------------------------------------------
 * @package     :IonDelivery
 * @Component   :ManageRubricsComponent
 * @Description :To add,edit & delete rubrics criteria, to Finalize rubrics criteria
 * @author      :Shashidhar Naik
 * @Created     :18-01-2018
 * @Modification History
 *  Date            Description	               Modified By
    ----------------------------------------------
 */

import { Component, OnInit, ViewChild } from '@angular/core';
import { IMultiSelectOption, IMultiSelectSettings, IMultiSelectTexts } from "angular-2-dropdown-multiselect";
import * as $ from 'jquery';
import { RouterModule } from '@angular/router';
import { ActivatedRoute, Params } from "@angular/router";
import { PostService } from '../../services/post.service';
import { FormGroup, FormControl, Validators, FormGroupDirective } from '@angular/forms';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig, ToasterService } from 'angular2-toaster';
import { Title } from '@angular/platform-browser';
import { CharctersOnlyValidation } from '../../custom.validators';

@Component({
  selector: 'app-manage-rubrics',
  templateUrl: './manage-rubrics.component.html',
  styleUrls: ['./manage-rubrics.component.css']
})
export class ManageRubricsComponent implements OnInit {
  /* Constructor */
  constructor(
    private route: ActivatedRoute,
    private service: PostService,
    private toast: ToastService,
    public titleService: Title,
    private toasterService: ToasterService) { }

  /* Global variables declaration */
  title = "Add Criteria";
  rangeArrayVal: string[] = ["0-2", "3-5", "6-8", "9-11", "12-14", "15-16", "17-18", "19-20", "21-22"];
  scaleArrayVal: string[] = ["", "", "", "", "", "", "", "", ""];
  criteriaDescArrayVal: string[] = ["", "", "", "", "", "", "", "", ""];
  rubricsCol: number = 0;
  invalidInput: boolean = false;
  mySettings: IMultiSelectSettings = {};
  courseOutcomeList: IMultiSelectOption[];
  optionsCO: number[] = [];
  piList: IMultiSelectOption[];
  optionsPI: number[] = [];
  tloList: IMultiSelectOption[];
  optionsTLO: number[] = [];
  dropdownShow = {};
  dropdownOrder = {};
  sectionName: Array<any>;
  private sub: any;
  activityName;
  id;
  tosterconfig;
  colspanScaleAssessment: number = 0;
  rubricsRange;
  rangeExist: number = 0;
  criteriaData = [];
  editedIds: number[] = [];
  setCriteriaId: any;
  isSaveHide = false;
  isUpdateHide = true;
  criteriaDescIdArray = [];
  delCriteriaId;
  rubricsFinalizeStatus: number = 0;
  editedCriteriaData;
  @ViewChild(FormGroupDirective)
  formGroupDirective: FormGroupDirective;

  // CO Dropdown Settings configuration
  myCO: IMultiSelectTexts = {
    defaultTitle: 'Select COs',
    checked: 'CO selected',
    checkedPlural: 'COs selected'
  };

  cloSettings: IMultiSelectSettings = {
    dynamicTitleMaxItems: 2,
    displayAllSelectedText: true,
    maxHeight: '150px'
  };

  // PI Dropdown Settings configuration
  myPI: IMultiSelectTexts = {
    defaultTitle: 'Select PIs',
    checked: 'PI selected',
    checkedPlural: 'PIs selected'
  };

  piSettings: IMultiSelectSettings = {
    dynamicTitleMaxItems: 1,
    displayAllSelectedText: true,
    maxHeight: '150px'
  };

  // TLO Dropdown Settings configuration
  myTLO: IMultiSelectTexts = {
    defaultTitle: 'Select TLOs',
    checked: 'TLO selected',
    checkedPlural: 'TLOs selected'
  };

  tloSettings: IMultiSelectSettings = {
    dynamicTitleMaxItems: 2,
    displayAllSelectedText: true,
    maxHeight: '150px'
  };

  programValue = localStorage.getItem('programDropdown');
  curriculumValue = localStorage.getItem('currDropdown');
  termValue = localStorage.getItem('termDropdown');
  courseValue = localStorage.getItem('courseDropdown');
  sectionValue = localStorage.getItem('sectionDropdownId');

  rubricsForm = new FormGroup({
    rubricCriteria: new FormControl('', [Validators.required, CharctersOnlyValidation.nospaceValidator])
  });
  get rubricCriteria() {
    return this.rubricsForm.get('rubricCriteria');
  }
  get addRubricsCO() {
    return this.rubricsForm.get('addRubricsCO');
  }
  get addRubricsPI() {
    return this.rubricsForm.get('addRubricsPI');
  }
  get addRubricsTLO() {
    return this.rubricsForm.get('addRubricsTLO');
  }

  // Function get called on initialization
  ngOnInit() {
    $('#loader').hide();
    this.titleService.setTitle('Manage Rubrics | IONCUDOS');

    var prg = localStorage.getItem('programDropdownId');
    var crclm = localStorage.getItem('currDropdownId');
    var term = localStorage.getItem('termDropdownId');
    var course = localStorage.getItem('courseDropdownId');
    var section = localStorage.getItem('sectionDropdownId');

    // To get Section name
    this.service.subUrl = 'activity/faculty/ManageRubrics/getSectionName';
    this.service.createPost(section).subscribe(response => {
      this.sectionName = response.json();
    });

    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        // To get Activity name
        this.service.subUrl = 'activity/faculty/ManageRubrics/getActivityName';
        this.service.createPost(this.id).subscribe(response => {
          this.activityName = response.json()[0]['ao_method_name'];
        });

        // To list Rubrics criteria
        this.service.subUrl = 'activity/faculty/ManageRubrics/listCreteria';
        this.service.createPost(this.id).subscribe(response => {
          this.criteriaData = response.json();
        });

        // To get Rubrics Finalize status
        this.service.subUrl = 'activity/faculty/ManageRubrics/getRubricsFinalizeStatus';
        this.service.createPost(this.id).subscribe(response => {
          if (response.json()[0]['dlvry_finalize_status'] == '1')
            this.rubricsFinalizeStatus = 1;
          else
            this.rubricsFinalizeStatus = 0;
        });

        // To get Rubrics criteria range & scale
        this.service.subUrl = 'activity/faculty/ManageRubrics/getCreteriaRange';
        this.service.createPost(this.id).subscribe(response => {
          if (response.json().status == 'ok') {
            this.rubricsRange = response.json().rubrics_data;
            this.GenerateRubricsTable(response.json().num_rows);
            this.colspanScaleAssessment = response.json().num_rows;
            this.rangeExist = 1;
            let i = 1;
            for (let range of this.rubricsRange) {
              this.scaleArrayVal[i - 1] = range.criteria_range_name;
              this.rangeArrayVal[i - 1] = range.criteria_range;
              i++;
            }
          } else {
            this.rangeArrayVal = ["0-2", "3-5", "6-8", "9-11", "12-14", "15-16", "17-18", "19-20", "21-22"];
            this.scaleArrayVal = ["", "", "", "", "", "", "", "", ""];
            this.rubricsRange = [];
            this.rubricsCol = 0;
            this.colspanScaleAssessment = 0;
            this.rangeExist = 0;
          }
        });
      })

    // To genarate CO,PO,TLO dropdowns & order these
    this.service.subUrl = 'activity/faculty/ManageRubrics/getDeliveryConfig';
    this.service.getData().subscribe(response => {
      for (var i = 0; i <= 2; i++) {
        this.dropdownShow[response.json()[i]['entity_id']] = response.json()[i]['iondelivery_config'];
        this.dropdownOrder[response.json()[i]['entity_id']] = response.json()[i]['iondelivery_config_orderby'];
      }
      if (this.dropdownShow[11] == 1)
        this.rubricsForm.addControl('addRubricsCO', new FormControl('', Validators.required));
      if (this.dropdownShow[22] == 1)
        this.rubricsForm.addControl('addRubricsPI', new FormControl(''));
      if (this.dropdownShow[12] == 1)
        this.rubricsForm.addControl('addRubricsTLO', new FormControl(''));
    });

    let postdata = {
      'pgrValue': prg,
      'crclmValue': crclm,
      'termValue': term,
      'courseValue': course,
      'sectionValue': section
    }

    //To fetch CO dropdown values
    this.service.subUrl = 'activity/faculty/ManageRubrics/getCloDropdown';
    this.service.createPost(postdata).subscribe(response => {
      this.courseOutcomeList = response.json();
    });
  }

  /**
   * Function to generate text area fields to add rubrics criteria
   * Params: Number of columns for rubrics criteria
   * Return:
   */
  GenerateRubricsTable(colForRubrics) {
    if (colForRubrics >= 1 && colForRubrics <= 9 && (/^\d$/.test(colForRubrics))) {
      this.invalidInput = false;
      for (var i = Number(colForRubrics) + 1; i <= this.rubricsCol; i++) {
        this.rubricsForm.removeControl('rubricScale' + i);
        this.rubricsForm.removeControl('rubricRange' + i);
        this.rubricsForm.removeControl('rubricCriteria' + i);
      }
      this.rubricsCol = colForRubrics;
      for (let i = 1; i <= this.rubricsCol; i++) {
        this.rubricsForm.addControl('rubricScale' + i, new FormControl(''));
        this.rubricsForm.addControl('rubricRange' + i, new FormControl('', [Validators.required, CharctersOnlyValidation.nospaceValidator]));
        this.rubricsForm.addControl('rubricCriteria' + i, new FormControl('', [Validators.required, CharctersOnlyValidation.nospaceValidator]));
      }
    } else {
      this.rubricsCol = 0;
      this.invalidInput = true;
    }
  }

  /**
   * Function to create an array of natural number till the last number is given
   * Params: Number
   * Return: Array of natural number
   */
  createRange(number) {
    var items: number[] = [];
    for (var i = 1; i <= number; i++) {
      items.push(i);
    }
    return items;
  }

  /**
   * Function to validate dinamic generated rubricCriteria field
   * Params: rubricCriteria field's index(slno)
   * Return: Get access to formgroup element
   */
  validateCriteria(index) {
    return this.rubricsForm.get('rubricCriteria' + index);
  }

  /**
   * Function to validate dinamic generated rubrics Range field
   * Params: rubrics Range field's index(slno)
   * Return: Get access to formgroup element
   */
  validateRange(index) {
    return this.rubricsForm.get('rubricRange' + index);
  }

  /**
   * Function to show warning message if Rubrics finalized while adding new rubrics criteria
   * Params: Rubrics form data
   * Return: 
   */
  createRubricsCriteriaWarning(rubricsForm) {
    this.editedCriteriaData = rubricsForm;
    (<any>jQuery('#finalizedCriteriaAdd')).modal('show');
  }

  /**
   * Function to add new rubrics criteria
   * Params: Rubrics form data
   * Return: 
   */
  createRubricsCriteria(rubricsForm) {
    $('#loader').show();
    if (this.rubricsFinalizeStatus) {
      (<any>jQuery('#finalizedCriteriaAdd')).modal('hide');
      this.deleteFinalizeRubricsData();
    }
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        this.service.subUrl = 'activity/faculty/ManageRubrics/createRubricsCriteria/' + this.id;
        this.service.createPost(rubricsForm.value).subscribe(response => {
          if (response.json().status == 'ok') {
            let type = 'success';
            let title = 'Add Success';
            let body = 'New Cretaria Added Successfully.';
            $('#loader').hide();
            this.toasterMsg(type, title, body);
            this.formGroupDirective.resetForm();
            this.ngOnInit();
          } else {
            let type = 'error';
            let title = 'Add Fail';
            let body = 'New Cretaria Add Failed Please Try Again.';
            $('#loader').hide();
            this.toasterMsg(type, title, body);
            this.formGroupDirective.resetForm();
            this.ngOnInit();
          }
        });
      });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  /**
   * Function to show warning message if Rubrics finalized while editing rubrics criteria
   * Params: Rubrics form data
   * Return: 
   */
  editCriteriaWarning(data) {
    this.editedCriteriaData = data;
    (<any>jQuery('#editCriteria')).modal('show');
  }

  /**
   * Function to set values to all the fields after click of edit rubrics criteria icon
   * Params: Rubrics form data
   * Return: 
   */
  editCriteria(data) {
    if (this.rubricsFinalizeStatus) {
      (<any>jQuery('#editCriteria')).modal('hide');
    }
    this.title = "Edit Criteria";
    this.setCriteriaId = data.rubrics_criteria_id;
    this.rubricCriteria.setValue(data.criteria);
    if (this.dropdownShow[11] == 1 && data.co.length > 0) {
      var coIdMultiValue = [];
      coIdMultiValue = data.co[0]['clo_id'].split(',');
      coIdMultiValue.forEach(element => {
        this.editedIds;
      });
      this.addRubricsCO.setValue(coIdMultiValue);
    }
    if (this.dropdownShow[22] == 1 && data.pi.length > 0) {
      var piIdMultiValue = [];
      piIdMultiValue = data.pi[0]['pi_id'].split(',');
      piIdMultiValue.forEach(element => {
        this.editedIds;
      });
      this.addRubricsPI.setValue(piIdMultiValue);
    }
    if (this.dropdownShow[12] == 1 && data.tlo.length > 0) {
      var tloIdMultiValue = [];
      tloIdMultiValue = data.tlo[0]['tlo_id'].split(',');
      tloIdMultiValue.forEach(element => {
        this.editedIds;
      });
      this.addRubricsTLO.setValue(tloIdMultiValue);
    }

    for (var i = 0; i < this.rubricsCol; i++) {
      this.criteriaDescArrayVal[i] = data.criteriaDesc[i]['criteria_description'];
      this.criteriaDescIdArray[i] = data.criteriaDesc[i]['criteria_description_id'];
    }

    this.isSaveHide = true;
    this.isUpdateHide = false;
  }

  /**
   * Function to edit rubrics criteria
   * Params: Rubrics form data
   * Return: 
   */
  updateRubricsCriteria(rubricsForm) {
    $('#loader').show();
    if (this.rubricsFinalizeStatus) {
      this.deleteFinalizeRubricsData();
    }
    let postData = {
      'criteriaId': this.setCriteriaId,
      'criteriaDescId': this.criteriaDescIdArray,
      'activityDetails': rubricsForm.value
    };
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        this.service.subUrl = 'activity/faculty/ManageRubrics/editRubricsCriteria/' + this.id;
        this.service.createPost(postData).subscribe(response => {
          if (response.json().status == 'ok') {
            let type = 'success';
            let title = 'Update Success';
            let body = 'Rubrics Cretaria Updated Successfully.';
            $('#loader').hide();
            this.toasterMsg(type, title, body);
            this.formGroupDirective.resetForm();
            this.ngOnInit();
          } else {
            let type = 'error';
            let title = 'Add Fail';
            let body = 'Cretaria Update Failed Please Try Again.';
            $('#loader').hide();
            this.toasterMsg(type, title, body);
            this.formGroupDirective.resetForm();
            this.ngOnInit();
          }
        });
      })
    this.title = "Add Criteria";
    this.isSaveHide = false;
    this.isUpdateHide = true;
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  /**
   * Function to show warning message if Rubrics finalized while deleting rubrics criteria
   * Params: Rubrics criteria id
   * Return: 
   */
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    let criteriaId = deleteElement.getAttribute('criteriaId');
    this.delCriteriaId = criteriaId;
    if (this.rubricsFinalizeStatus)
      (<any>jQuery('#finalizedCriteriaDelete')).modal('show');
    else
      (<any>jQuery('#criteriaDelete')).modal('show');
  }

  /**
   * Function to delete rubrics criteria
   * Params: 
   * Return: 
   */
  deleteCriteriaData() {
    $('#loader').show();
    if (this.rubricsFinalizeStatus) {
      (<any>jQuery('#finalizedCriteriaDelete')).modal('hide');
      this.deleteFinalizeRubricsData();
    } else
      (<any>jQuery('#criteriaDelete')).modal('hide');
    this.service.subUrl = 'activity/faculty/ManageRubrics/deleteCriteria';
    this.service.deletePost(this.delCriteriaId).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Criteria Deleted Successfully.';
        $('#loader').hide();
        this.toasterMsg(type, title, body);
        this.formGroupDirective.resetForm();
        this.ngOnInit();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Criteria Delete Failed Please Try Again.';
        $('#loader').hide();
        this.toasterMsg(type, title, body);
      }
    });
    this.title = "Add Criteria";
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  /**
   * Function to show confirmation modal while finalizing rubrics criteria
   * Params: 
   * Return: 
   */
  finalizeRubricsDataWarning() {
    (<any>jQuery('#finalizeRubricsData')).modal('show');
  }

  /**
   * Function to finalize rubrics criteria
   * Params: 
   * Return: 
   */
  finalizeRubricsData() {
    $('#loader').show();
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        this.service.subUrl = 'activity/faculty/ManageRubrics/finalizeRubricsData';
        this.service.createPost(this.id).subscribe(response => {
          if (response.json().status == 'ok') {
            let type = 'success';
            let title = 'Finalize Success';
            let body = 'Rubrics finalized successfully.';
            $('#loader').hide();
            this.toasterMsg(type, title, body);
            (<any>jQuery('#finalizeRubricsData')).modal('hide');
            this.ngOnInit();
          } else {
            let type = 'error';
            let title = 'Finalize Fail';
            let body = 'Rubrics Finalize Failed Please Try Again.';
            $('#loader').hide();
            this.toasterMsg(type, title, body);
            this.ngOnInit();
          }
        });
      })
  }

  /**
   * Function to delete finalized rubrics data
   * Params: 
   * Return: 
   */
  deleteFinalizeRubricsData() {
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        this.service.subUrl = 'activity/faculty/ManageRubrics/DeleteFinalizeRubricsData';
        this.service.deletePost(this.id).subscribe(response => {
        });
      })
  }

  /**
   * Function to fetch PI dropdown values
   * Params: 
   * Return: 
   */
  getPI(event) {
    this.optionsPI = [];
    if (this.optionsCO) {
      var id = this.optionsCO;
      this.service.subUrl = 'activity/faculty/ManageRubrics/getPIDropdown';
      this.service.createPost(id).subscribe(response => {
        this.piList = response.json();
      });
    }
  }

  /**
   * Function to fetch TLO dropdown values
   * Params: 
   * Return: 
   */
  gettlo(event) {
    this.optionsTLO = [];
    
    if (this.optionsCO) {
      this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        this.service.subUrl = 'activity/faculty/ManageRubrics/getTloDropdown';
        this.service.createPost(this.id).subscribe(response => {
          this.tloList = response.json();
        });
      })
    }
  }

  /**
   * Function to set configuration of toast message
   * Params: type(Ex:success,error), title, body of toast message
   * Return: 
   */
  toasterMsg(type, title, body) {
    this.toast.toastType = type;
    this.toast.toastTitle = title;
    this.toast.toastBody = body;
    this.tosterconfig = new ToasterConfig({
      positionClass: 'toast-bottom-right',
      tapToDismiss: true,
      showCloseButton: true,
      animation: 'slideDown',
      mouseoverTimerStop: true
    });
    this.toast.toastMsg;
  }

  /**
   * Function to show confirmation modal while regenarating rubrics criteria
   * Params:
   * Return: 
   */
  regenarateRubricScaleWarning() {
    (<any>jQuery('#regenarateRubricScale')).modal('show');
  }

  /**
   * Function to regenarate rubrics criteria
   * Params:
   * Return: 
   */
  regenarateRubricScale() {
    if (this.rubricsFinalizeStatus)
      this.deleteFinalizeRubricsData();
    for (var i = 1; i <= this.rubricsCol; i++) {
      this.rubricsForm.removeControl('rubricScale' + i);
      this.rubricsForm.removeControl('rubricRange' + i);
      this.rubricsForm.removeControl('rubricCriteria' + i);
    }
    this.rubricsCol = 0;

    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        this.service.subUrl = 'activity/faculty/ManageRubrics/regenarateRubricScale';
        this.service.createPost(this.id).subscribe(response => {
          if (response.json().status == 'ok') {
            (<any>jQuery('#regenarateRubricScale')).modal('hide');
            this.ngOnInit();
          }
        });
      })
  }
}
