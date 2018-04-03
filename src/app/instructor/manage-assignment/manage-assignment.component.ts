import { forEach } from '@angular/router/src/utils/collection';
import { element } from 'protractor';
import { TinymceComponent } from './../../thirdparty/tinymce/tinymce.component';
import { Component, OnInit, ViewChild, AfterViewInit, ElementRef, Input } from '@angular/core';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { IMultiSelectSettings } from 'angular-2-dropdown-multiselect';
import { DataTableDirective } from 'angular-datatables';
import * as $ from 'jquery';
import { RouterModule } from '@angular/router';
import { ActivatedRoute, Params } from "@angular/router";
import { PostService } from '../../services/post.service';
import { FormGroup, FormControl, Validators } from '@angular/forms'; // for form validation
import { Subject } from 'rxjs/Rx';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { Http, Response } from '@angular/http';
import 'rxjs/add/operator/map';
import { Observable } from 'rxjs/Observable';
import { Title } from '@angular/platform-browser';
import { CharctersOnlyValidation } from '../../custom.validators';
import { assertPlatform } from '@angular/core/src/application_ref';
@Component({
  selector: 'app-manage-assignment',
  templateUrl: './manage-assignment.component.html',
  styleUrls: ['./manage-assignment.component.css']
})

export class ManageAssignmentComponent implements OnInit, AfterViewInit {

  // myTexts: IMultiSelectTexts = {
  //   checkedPlural: 'items selected',
  //   defaultTitle: 'Select',
  //   allSelected: 'All selected'
  // };
  /* Constructor */
  constructor(private route: ActivatedRoute,
    private service: PostService,
    private toast: ToastService,
    public titleService: Title,
    private http: Http) { }
  textvalue;
  title: string;
  newTotalMarks;
  quesMarks;
  assign_questId;
  ifQuestions;
  tosterconfig;
  stu_status: Array<any>;
  optionsModel: number[] = [];
  optionsTlo: number[];
  courseList: IMultiSelectOption[] = [];
  performanceList: IMultiSelectOption[] = [];
  topicList: IMultiSelectOption[] = [];
  topiclearningList: IMultiSelectOption[] = [];
  bloomsList: IMultiSelectOption[] = [];
  difficultyList: IMultiSelectOption[] = [];
  questionList: IMultiSelectOption[] = [];
  tinyMceValue: string;
  selectedCourse: number[];
  selectedPerformance: number[];
  selectedTopic: number[];
  selectedTopicLearning: number[];
  selectedBloom: number[];
  selectedDifficulty: number[];
  selectedQuestion: number[];
  myTexts: IMultiSelectTexts = {};
  mySettings: IMultiSelectSettings = {};
  myClo: IMultiSelectTexts = {};
  myPi: IMultiSelectTexts = {};
  myTopics: IMultiSelectTexts = {};
  myTlo: IMultiSelectTexts = {};
  myBl: IMultiSelectTexts = {};
  myDl: IMultiSelectTexts = {};
  myQt: IMultiSelectTexts = {};
  deleteAllAssignId: Array<any> = [];
  checked: Array<any> = [];
  deleteAllId: Array<any> = [];
  curr: "variable";
  private sub: any;

  programValue = localStorage.getItem('programDropdown');
  curriculumValue = localStorage.getItem('currDropdown');
  termValue = localStorage.getItem('termDropdown');
  courseValue = localStorage.getItem('courseDropdown');
  sectionValue = localStorage.getItem('sectionDropdownId');

  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  hideMsg: boolean;
  isUpload: boolean;
  isQuestion: boolean;
  isTinyMce: boolean;
  setAssignmenttQuestionId: any;
  posts = [];
  @Input('assignmentId') delAssignmentId;
  assignmentQuestionEditData = [];
  cloupdate: number[] = [];
  topicupdate: number[] = [];
  piupdate: number[] = [];
  tloupdate: number[] = [];
  bloomupdate: number[] = [];
  questTypeupdate: number[] = [];
  diffupdate: number[] = [];
  sectionName: Array<any>;
  assignmentName;
  assignmentQuest: Array<any>;
  assignmentQuests: Array<any>;
  id;
  status;
  subResult;
  assignQuestId;
  entityData: Array<any>;
  tinyMcedata: string;
  prg;
  crclm;
  term;
  course;
  section;
  tableCO: boolean = true;
  tablePI: boolean = true;
  tableTopic: boolean = true;
  tableTLO: boolean = true;
  tableBloom: boolean = true;
  tableDL: boolean = true;
  tableQT: boolean = true;
  assign_questIds
  showMarks: boolean = true;
  assignData: Array<any> = [];
  @Input() multiple: boolean = false;
  @ViewChild('fileInput') inputEl: ElementRef; //file upload
  @ViewChild('fileInputUpdate') inputE2: ElementRef; //file upload
  // textvalue;
  public manageAssignmentForm = new FormGroup({
    qno: new FormControl('', [Validators.required]),
    entityclo: new FormControl('', []),
    courseOutcome: new FormControl('', []),
    entitypo: new FormControl('', []),
    performanceIndicators: new FormControl('', []),
    entitytopic: new FormControl('', []),
    // courseList: new FormControl('', []),
    topics: new FormControl('', [Validators.required]),
    entitytlo: new FormControl('', []),
    tlo: new FormControl('', []),
    entitybloom: new FormControl('', []),
    bloomLevel: new FormControl('', []),
    entitydifficulty: new FormControl('', []),
    difficultyLevel: new FormControl('', []),
    entityquestion: new FormControl('', []),
    questType: new FormControl('', []),
    marks: new FormControl('', [CharctersOnlyValidation.DigitsOnlyStart]),
    assignQuest: new FormControl('', []),
    totalMarks: new FormControl('', []),
    // description: new FormControl('',[ Validators.required, Validators.maxLength(1000)]),
  });
  newElement: Array<any>;
  newArray: Array<any>;
  cloArray: Array<any> = [];

  ngOnInit() {
    this.hideMsg = true;
    this.title = "Add Assignment";

    this.titleService.setTitle('Manage-Assignment | IONCUDOS');
    this.manageAssignmentForm.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.isUpload = true;
    this.isQuestion = true;
    this.isTinyMce = true;
    this.prg = localStorage.getItem('programDropdownId');
    this.crclm = localStorage.getItem('currDropdownId');
    this.term = localStorage.getItem('termDropdownId');
    this.course = localStorage.getItem('courseDropdownId');
    this.section = localStorage.getItem('sectionDropdownId');

    this.service.subUrl = 'assignment/ManageAssignment/getSectionName';
    this.service.createPost(this.section).subscribe(response => {
      this.sectionName = response.json();

    });


    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        var id = +params['id'] || 0;
        let postdata = {
          'pgrValue': this.prg,
          'crclmValue': this.crclm,
          'termValue': this.term,
          'courseValue': this.course,
          'sectionValue': this.section,
          'assignment_id': id
        }
        this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
        this.service.createPost(postdata).subscribe(response => {
          this.assignmentQuest = response.json();
          this.assignmentQuest = Array.of(this.assignmentQuest);

          this.assignmentQuest.forEach(element => {
            this.assignmentQuests = JSON.parse(element);
          })
          this.tableRerender();
          this.dtTrigger.next();
        });
      });

    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.service.subUrl = 'assignment/ManageAssignment/index';
        this.service.createPost(this.id).subscribe(response => {
          this.assignmentName = response.json();
          let postparams = { 'id': this.id }
          this.service.subUrl = 'assignment/ManageAssignment/getsubmissionStatus';
          this.service.createPost(postparams).subscribe(response => {
            this.subResult = response.json();

            if (this.subResult.status == 'ok') {

              this.deleteAllId = JSON.parse(JSON.stringify(this.assignmentQuests));
              this.deleteAllId.forEach(element => {
                this.assign_questIds = element.aq_id;
                $("#" + element.aq_id).prop('disabled', true);
                // $("#" + element.aq_id).prop('checked', true);
              })
            }
            $("#delete_question").attr("style", "visibility: hidden")
          });
          this.tableRerender();
          this.dtTrigger.next();
        });

      });


    $(document).on("click", ".dropdown", function () {
      // calculate the required sizes, spaces
      var $ul = $(this).children(".dropdown-menu");
      var $button = $(this).children(".dropdown-toggle");
      var ulOffset = $ul.offset();
      // how much space would be left on the top if the dropdown opened that direction
      var spaceUp = (ulOffset.top - $button.height() - $ul.height()) - $(window).scrollTop();
      // how much space is left at the bottom
      var spaceDown = $(window).scrollTop() + $(window).height() - (ulOffset.top + $ul.height());
      // switch to dropup only if there is no space at the bottom AND there is space at the top, or there isn't either but it would be still better fit
      if (spaceDown < 0 && (spaceUp >= 0 || spaceUp > spaceDown))
        $(this).addClass("dropup");
    }).on("hidden.bs.dropdown", ".dropdown", function () {
      // always reset after close
      $(this).removeClass("dropup");
    });

    // API to load Assignment Questions dropdown accroding to configuration
    // Auhtor Avinash P
    // Date 21/11/2017
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.service.subUrl = 'assignment/ManageAssignment/getConfig';
        this.service.createPost(this.id).subscribe(response => {
          this.entityData = response.json();
          this.entityData.forEach(element => {

            if (element.entity_name == 'topic') {
              if (element.iondelivery_config != 1) {
                this.tableTopic = false;
                $('#topic').hide();
                // this.manageAssignmentForm.get('topic').setValidators([]);
                // this.manageAssignmentForm.get('topic').updateValueAndValidity();

              }
            }
            else if (element.entity_name == 'clo') {
              if (element.iondelivery_config != 1) {
                this.tableCO = false;
                $('#clo').hide();
              }
            }
            else if (element.entity_name == 'tlo') {
              if (element.iondelivery_config != 1) {
                this.tableTLO = false;
                $('#tlo').hide();
              }
            }
            else if (element.entity_name == 'po_clo_crs') {
              if (element.iondelivery_config != 1) {
                this.tablePI = false;
                $('#po_clo_crs').hide();
              }
            }
            else if (element.entity_name == "bloom's_level") {
              if (element.iondelivery_config != 1) {
                this.tableBloom = false;
                $('#blooms_level').hide();
              }
            }
            else if (element.entity_name == 'question_type') {
              if (element.iondelivery_config != 1) {
                this.tableQT = false;
                $('#question_type').hide();
              }
            }
            else if (element.entity_name == 'difficulty_level') {
              if (element.iondelivery_config != 1) {
                this.tableDL = false;
                $('#difficulty_level').hide();
              }
            }

          })
        });
      });

    this.toggleMarks();
    this.checkqueType();
    this.setQnum();
    this.initDropdowns();
    this.setTotlaMarks();


  }

  clearTinyMCE() {

    this.tinyMcedata = "";
  }



  toggleMarks() {

    this.service.subUrl = 'assignment/ManageAssignment/getTotalMarks';
    this.service.createPost(this.id).subscribe(response => {
      this.newTotalMarks = response.json();

      let marks = this.newTotalMarks.split('/');
      // alert(marks)
      if (marks[1] == "0.00" || marks[1] == "0") {
        this.showMarks = false;
      }
      else {
        this.showMarks = true;
      }
    });

  }

  setTotlaMarks() {
    this.service.subUrl = 'assignment/ManageAssignment/getTotalMarks';
    this.service.createPost(this.id).subscribe(response => {
      this.newTotalMarks = response.json();
      this.totalMarks.setValue(this.newTotalMarks);
    });
  }

  initDropdowns() {
    this.getTopic();
    let postdata = {
      'pgrValue': this.prg,
      'crclmValue': this.crclm,
      'termValue': this.term,
      'courseValue': this.course,
      'sectionValue': this.section
    }

    this.service.subUrl = 'assignment/ManageAssignment/getCloDropdown';
    this.service.createPost(postdata).subscribe(response => {
      this.courseList = response.json();
    });

    // this.service.subUrl = 'assignment/ManageAssignment/getTopicDropdown';
    // this.service.createPost(postdata).subscribe(response => {
    //   this.topicList = response.json();
    // });
    this.service.subUrl = 'assignment/ManageAssignment/getBloomDropdown';
    this.service.createPost(postdata).subscribe(response => {
      this.bloomsList = response.json();
    });
    this.service.subUrl = 'assignment/ManageAssignment/getDifficultyDropdown';
    this.service.createPost(postdata).subscribe(response => {
      this.difficultyList = response.json();
    });
    this.service.subUrl = 'assignment/ManageAssignment/getQuestionTypeDropdown';
    this.service.createPost(postdata).subscribe(response => {
      this.questionList = response.json();
    });

    this.selectedPerformance = [1];
    this.selectedTopic = [1];
    this.selectedTopicLearning = [1];
    this.selectedBloom = [1];
    this.selectedDifficulty = [1];
    this.selectedQuestion = [1];

    this.myTexts = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      searchEmptyResult: 'Nothing found...',
      searchNoRenderText: 'Type in search box to see results...',
      defaultTitle: 'select',
      allSelected: 'All selected',
    };

    this.myClo = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: "Select CO's",
      allSelected: 'All selected',
    };

    this.myPi = {

      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: "Select PI's",
      allSelected: 'All selected',
    }

    this.myTopics = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Topics',
      allSelected: 'All selected',
    }

    this.myTlo = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select TLO',
      allSelected: 'All selected',
    }

    this.myBl = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: "Select BL's",
      allSelected: 'All selected',
    }
    this.myDl = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: "Select DL's",
      allSelected: 'All selected',
    }
    this.myQt = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: "Select QT's",
      allSelected: 'All selected',
    }


  }


  checkqueType() {
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuestions';
        this.service.createPost(this.id).subscribe(response => {
          this.entityData = response.json();

          if (response.json().status == 'ok') {
            this.isUpload = false;
            this.isQuestion = true;
            this.isTinyMce = true;
            tinymce.editors['my-editor-id'].setMode('design');
          }
          else if (response.json().status == 'no questions') {
            this.isUpload = true;
            this.isQuestion = true;
            this.isTinyMce = true;
            tinymce.editors['my-editor-id'].setMode('design');

          }
          else if (response.json().status == 'fail') {
            this.isUpload = true;
            this.isQuestion = false;
            // this.isTinyMce = false;
            tinymce.editors['my-editor-id'].setMode('readonly');

          }
        })
      });
  }



  getTopic() {
    let postdata = {
      'pgrValue': this.prg,
      'crclmValue': this.crclm,
      'termValue': this.term,
      'courseValue': this.course,
      'sectionValue': this.section
    }
    this.service.subUrl = 'assignment/ManageAssignment/getTopicDropdown';
    this.service.createPost(postdata).subscribe(response => {
      this.topicList = response.json();
    });
  }


  showTableColums() {
    this.tableTopic = true;
    this.tableCO = true;
    this.tableTLO = true;
    this.tablePI = true;
    this.tableBloom = true;
    this.tableQT = true;
    this.tableDL = true;
  }

  setQnum() {
    this.service.subUrl = 'assignment/ManageAssignment/checkQuestionExsist';
    this.service.createPost(this.id).subscribe(response => {
      this.ifQuestions = response.json();
      this.qno.setValue(this.ifQuestions)
    });
  }


  get qno() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('qno');
  }
  get courseOutcome() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('courseOutcome');
  }
  get performanceIndicators() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('performanceIndicators');
  }
  get topics() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('topics');
  }
  get tlo() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('tlo');
  }
  get bloomLevel() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('bloomLevel');
  }
  get difficultyLevel() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('difficultyLevel');
  }
  get questType() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('questType');
  }
  get marks() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('marks');
  }
  get assignQuest() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('assignQuest');
  }
  get totalMarks() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.manageAssignmentForm.get('totalMarks');
  }


  getplo(event) {
    var id = this.optionsModel
    this.service.subUrl = 'assignment/ManageAssignment/getPloDropdown';
    this.service.createPost(id).subscribe(response => {
      this.performanceList = response.json();
    });
  }

  //function to show/hide error message for Topic dropdown
  getErrorMsg() {
    this.hideMsg = false;
  }

  gettlo(event) {
    //this.tloupdate.length = 0;
    var id = this.optionsTlo;

    this.service.subUrl = 'assignment/ManageAssignment/getTloDropdown';
    this.service.createPost(id).subscribe(response => {
      this.topiclearningList = response.json();
    });
  }

  //tinymce validation while save
  tinyMceValidation(manageAssignmentForm) {
    this.getTopic();

    let topic_value = this.topics.value



    var content = tinymce.activeEditor.getContent();
    if ((content == "" || content == null)) {
      $("#error_tinymce").text("This field is required.");
      this.hideMsg = false;
    } else {
      $("#error_tinymce").empty();
      this.inputEl.nativeElement.value = "";
      this.createPost(manageAssignmentForm);

    }
  }



  //tinymce validation while update
  tinyMceUpdateValidation(manageAssignmentForm) {
    this.getTopic();

    let topic_value = this.topics.value

    var content = tinymce.activeEditor.getContent();
    if ((content == "" || content == null)) {

      $("#error_tinymce").text("This field is required.");
      this.hideMsg = false;
    } else {

      $("#error_tinymce").empty();
      this.updatePost(manageAssignmentForm);

    }
  }

  createPost(manageAssignmentForm) {
    // console.log(manageAssignmentForm);
    this.getTopic();

    let topic_value = this.topics.value
    var a_id = this.id;
    let postparams = { 'id': a_id }
    this.service.subUrl = 'assignment/ManageAssignment/getStudentSubmissionStatus';
    this.service.createPost(postparams).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Student(s) have already submitted the assignment'
        this.toasterMsg(type, title, body);

      } else {
        if ((topic_value == null || topic_value == '') || (manageAssignmentForm.invalid)) {
          $("#topic_req").text("This field is required.");
        }
        else {

          var id = this.id;
          let marks = manageAssignmentForm.value.marks;
          let quesData = {
            'id': id,
            'marks': marks
          }
          this.service.subUrl = 'assignment/ManageAssignment/getQuestionMarks';
          this.service.createPost(quesData).subscribe(response => {
            if (response.json().status == 'ok') {
              var tinymcevalue = tinymce.get('my-editor-id').getContent({ format: 'raw' });
              var user_id = localStorage.getItem('id');
              this.service.subUrl = 'assignment/ManageAssignment/createAssignmentQuestions';
              let assignmentQuestionsData = manageAssignmentForm.value;
              let postData = {
                'assignmentQuestionsData': assignmentQuestionsData,
                'tinymcevalue': tinymcevalue,
                'id': id,
                'user_id': user_id
              };
              this.service.createPost(postData).subscribe(response => {

                if (response.json().status == 'ok') {
                  let data = {
                    'pgrValue': this.prg,
                    'crclmValue': this.crclm,
                    'termValue': this.term,
                    'courseValue': this.course,
                    'sectionValue': this.section,
                    'assignment_id': id,
                    'user_id': user_id
                  }
                  this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
                  this.service.createPost(data).subscribe(response => {
                    this.assignmentQuest = response.json();
                    this.assignmentQuest = Array.of(this.assignmentQuest);

                    this.assignmentQuest.forEach(element => {
                      this.assignmentQuests = JSON.parse(element);
                    })
                    this.reset();
                    tinymce.activeEditor.setContent("");
                    this.checkqueType();
                    this.initDropdowns();
                    this.toggleMarks();
                    // this.tableRerender();
                    // this.dtTrigger.next();


                  });
                  let type = 'success';
                  let title = 'Add Success';
                  let body = 'New assignment question added successfully'
                  this.toasterMsg(type, title, body);
                  this.hideMsg = true;

                  // if (topic_value == null || topic_value == '') {
                  //   $("#topic_req").text("This field is required.");
                  // } else {
                  // //file upload function
                  // // let inputEl: HTMLInputElement = this.el.nativeElement.querySelector('#userdoc');
                  // let inputEl: HTMLInputElement = this.inputEl.nativeElement;
                  // //get the total amount of files attached to the file input.
                  // let fileCount: number = inputEl.files.length;
                  // // let fileList: FileList = event.target.files;
                  // if (fileCount > 0) {
                  //   let file: File = fileCount[0];
                  //   let formData: FormData = new FormData();
                  //   formData.append('userdoc', inputEl.files.item(0));
                  //   let headers = new Headers();
                  //   // No need to include Content-Type in Angular 4 /
                  //   headers.append('Content-Type', 'multipart/form-data');
                  //   headers.append('Accept', 'application/json');
                  //   // let options = new RequestOptions({ headers: headers });
                  //   var curriculumValue = localStorage.getItem('currDropdown');
                  //   var courseValue = localStorage.getItem('courseDropdown');
                  //   curriculumValue = curriculumValue.replace(/ /g, '_');
                  //   courseValue = courseValue.replace(/ /g, '_');
                  //   this.http.post(this.service.baseUrl + 'assignment/ManageAssignment/upload/' + curriculumValue + '/' + courseValue, formData)
                  //     .map(res => res.json())
                  //     .catch(error => Observable.throw(error))
                  //     .subscribe(response => {
                  //       let data = {
                  //         'pgrValue': this.prg,
                  //         'crclmValue': this.crclm,
                  //         'termValue': this.term,
                  //         'courseValue': this.course,
                  //         'sectionValue': this.section,
                  //         'assignment_id': id,
                  //         'user_id': user_id
                  //       }
                  //       this.inputEl.nativeElement.value = "";
                  //       this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
                  //       this.service.createPost(data).subscribe(response => {
                  //         this.assignmentQuest = response.json();
                  //         this.assignmentQuest = Array.of(this.assignmentQuest);
                  //         this.assignmentQuest.forEach(element => {
                  //           this.assignmentQuests = JSON.parse(element);
                  //         })
                  //         this.reset();
                  //         tinymce.activeEditor.setContent("");
                  //         this.checkqueType();
                  //         this.initDropdowns();
                  //         this.toggleMarks();
                  //         this.tableRerender();
                  //         this.dtTrigger.next();
                  //       });
                  //     })
                  // }
                  // }
                } else {
                  let type = 'error';
                  let title = 'Add Fail';
                  let body = 'New assignment question add failed please try again'
                  this.toasterMsg(type, title, body);
                }
              });
            }
            else {
              let type = 'error';
              let title = 'Add Fail';
              let body = 'Assignment question marks should be less than total marks'
              this.toasterMsg(type, title, body);

            }
          });
          $('html,body').animate({ scrollTop: 0 }, 'slow');
        }
      }
    });
  }

  // clear the previous selected file

  clearBrowse() {
    this.textvalue = '';
    this.inputEl.nativeElement.value = "";
  }

  //to upload the file

  uploadFile(manageAssignmentForm) {
    this.getTopic();
    let topic_value = this.topics.value
    var a_id = this.id;
    let postparams = { 'id': a_id }
    this.service.subUrl = 'assignment/ManageAssignment/getStudentSubmissionStatus';
    this.service.createPost(postparams).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Student(s) have already submitted the assignment'
        this.toasterMsg(type, title, body);

      } else {
        if ((topic_value == null || topic_value == '') || (manageAssignmentForm.invalid)) {
          $("#topic_req").text("This field is required.");
        }
        else {
          var id = this.id;
          let marks = manageAssignmentForm.value.marks;
          let quesData = {
            'id': id,
            'marks': marks
          }
          this.service.subUrl = 'assignment/ManageAssignment/getQuestionMarks';
          this.service.createPost(quesData).subscribe(response => {
            if (response.json().status == 'ok') {
              let inputEl: HTMLInputElement = this.inputEl.nativeElement;
              let fileCount: number = inputEl.files.length;
              var size = inputEl.files.item(0).size;
              if (size > 1000000) {
                let type = 'error';
                let title = 'File Upload Fail';
                let body = 'File size should be less than 1 MB'
                this.toasterMsg(type, title, body);
              } else {
                var tinymcevalue = this.textvalue;
                var user_id = localStorage.getItem('id');
                this.service.subUrl = 'assignment/ManageAssignment/createAssignmentQuestions';
                let assignmentQuestionsData = manageAssignmentForm.value;
                let postData = {
                  'assignmentQuestionsData': assignmentQuestionsData,
                  'tinymcevalue': tinymcevalue,
                  'id': id,
                  'user_id': user_id
                };
                this.service.createPost(postData).subscribe(response => {
                  if (response.json().status == 'ok') {

                    if (fileCount > 0) {
                      let file: File = fileCount[0];
                      let formData: FormData = new FormData();
                      formData.append('userdoc', inputEl.files.item(0));
                      let headers = new Headers();
                      // No need to include Content-Type in Angular 4 /
                      headers.append('Content-Type', 'multipart/form-data');
                      headers.append('Accept', 'application/json');
                      // let options = new RequestOptions({ headers: headers });
                      var curriculumValue = localStorage.getItem('currDropdown');
                      var courseValue = localStorage.getItem('courseDropdown');
                      curriculumValue = curriculumValue.replace(/ /g, '_');
                      courseValue = courseValue.replace(/ /g, '_');
                      this.http.post(this.service.baseUrl + 'assignment/ManageAssignment/upload/' + curriculumValue + '/' + courseValue, formData)
                        .map(res => res.json())
                        .catch(error => Observable.throw(error))
                        .subscribe(response => {
                          let data = {
                            'pgrValue': this.prg,
                            'crclmValue': this.crclm,
                            'termValue': this.term,
                            'courseValue': this.course,
                            'sectionValue': this.section,
                            'assignment_id': id,
                            'user_id': user_id
                          }

                          this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
                          this.service.createPost(data).subscribe(response => {
                            this.assignmentQuest = response.json();
                            this.assignmentQuest = Array.of(this.assignmentQuest);
                            this.assignmentQuest.forEach(element => {
                              this.assignmentQuests = JSON.parse(element);
                            })
                            this.reset();
                            tinymce.activeEditor.setContent("");
                            this.checkqueType();
                            this.initDropdowns();
                            this.toggleMarks();
                            // this.tableRerender();
                            // this.dtTrigger.next();
                          });
                        })
                    }
                    let type = 'success';
                    let title = 'Add Success';
                    let body = 'New assignment question added successfully'
                    this.toasterMsg(type, title, body);
                  } else {
                    let type = 'error';
                    let title = 'Add Fail';
                    let body = 'New assignment question add failed please try again'
                    this.toasterMsg(type, title, body);
                  }
                });
              }
            }
            else {
              let type = 'error';
              let title = 'Add Fail';
              let body = 'Assignment question marks should be less than total marks'
              this.toasterMsg(type, title, body);
              // this.manageAssignmentForm.reset();
              //this.ngOnInit();
            }
          });

          $('html,body').animate({ scrollTop: 0 }, 'slow');
        }
      }
    });
  }


  editassignmentQuestions(editElement: HTMLElement) {
    this.getTopic();
    var a_id = this.id;
    let postparams = { 'id': a_id }
    this.service.subUrl = 'assignment/ManageAssignment/getStudentSubmissionStatus';
    this.service.createPost(postparams).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'error';
        let title = 'Edit Fail';
        let body = 'Student(s) have already submitted the assignment'
        this.toasterMsg(type, title, body);
        // this.ngOnInit();

      } else {

        this.title = "Edit Assignment";
        let topic
        let clo
        let tlo
        let bloom
        let que_type
        let pi
        let diff
        let marks
        this.piupdate.length = 0;
        this.performanceList.length = 0;
        this.topiclearningList.length = 0;
        this.assignmentQuestionEditData.length = 0;
        this.cloupdate.length = 0;
        this.topicupdate.length = 0;
        this.tloupdate.length = 0;
        this.bloomupdate.length = 0;
        this.questTypeupdate.length = 0;
        this.diffupdate.length = 0;
        var assignmentQuesId = editElement.getAttribute('assignmentQuestioniId');
        let postdata = {
          'pgrValue': this.prg,
          'crclmValue': this.crclm,
          'termValue': this.term,
          'courseValue': this.course,
          'sectionValue': this.section,
          'assignmentQuesId': assignmentQuesId
        }
        // console.log(postdata);
        this.service.subUrl = 'assignment/ManageAssignment/geteditAssignmentQuest';
        this.service.createPost(postdata).subscribe(response => {
          this.assignmentQuestionEditData = response.json();
          this.assignmentQuestionEditData.forEach(element => {
            let aq_id = JSON.parse(element.aq_id)
            let qno = JSON.parse(element.main_que_num);
            marks = JSON.parse(element.que_max_marks);
            if (marks == '0') {
              marks = null;
            }
            this.toggleMarks();

            this.setAssignmenttQuestionId = aq_id;
            this.qno.setValue(qno);
            // this.tinyMcedata = element.que_content;
            tinymce.activeEditor.setContent(element.que_content);

            this.totalMarks.setValue(this.newTotalMarks);
            element.data.forEach(multidrop => {

              if (multidrop.topic != undefined) {
                topic = JSON.parse((JSON.stringify(multidrop.topic)));
                topic.forEach(topics => {
                  this.service.subUrl = 'assignment/ManageAssignment/getTloDropdown';
                  this.service.createPost(topics).subscribe(response => {
                    this.topiclearningList = response.json();
                  });
                  if (this.topicupdate.indexOf(topics.topic_id) == -1) {
                    this.topicupdate.push(topics.topic_id);
                  }
                });
              }
              else if (multidrop.clo != undefined) {

                clo = JSON.parse((JSON.stringify(multidrop.clo)));
                clo.forEach(clos => {
                  this.service.subUrl = 'assignment/ManageAssignment/getPloDropdown';
                  this.service.createPost(clos).subscribe(response => {
                    this.performanceList = response.json();
                  });
                  if (this.cloupdate.indexOf(clos.clo_id) == -1) {
                    this.cloupdate.push(clos.clo_id);
                  }
                });
              }
              else if (multidrop.pi != undefined) {
                pi = JSON.parse((JSON.stringify(multidrop.pi)));
                pi.forEach(pis => {
                  if (this.piupdate.indexOf(pis.msr_id) == -1 || this.piupdate.indexOf(pis.msr_id) != pis.msr_id) {
                    this.piupdate.push(pis.msr_id);
                  }
                });
              }
              else if (multidrop.tlo != undefined) {
                tlo = JSON.parse((JSON.stringify(multidrop.tlo)));
                tlo.forEach(tlos => {
                  if (this.tloupdate.indexOf(tlos.tlo_id) == -1) {
                    this.tloupdate.push(tlos.tlo_id);
                  }
                });
              }
              else if (multidrop.bloom != undefined) {
                bloom = JSON.parse((JSON.stringify(multidrop.bloom)));
                bloom.forEach(blooms => {
                  if (this.bloomupdate.indexOf(blooms.bloom_id) == -1) {
                    this.bloomupdate.push(blooms.bloom_id);
                  }
                });
              }
              else if (multidrop.que_type != undefined) {
                que_type = JSON.parse((JSON.stringify(multidrop.que_type)));
                que_type.forEach(que_types => {
                  if (this.questTypeupdate.indexOf(que_types.mt_details_id) == -1) {
                    this.questTypeupdate.push(que_types.mt_details_id);
                  }
                });
              }

              else if (multidrop.diff != undefined) {
                diff = JSON.parse((JSON.stringify(multidrop.diff)));
                diff.forEach(diff_levels => {
                  if (this.diffupdate.indexOf(diff_levels.mt_details_id) == -1) {
                    this.diffupdate.push(diff_levels.mt_details_id);
                  }
                });
              }
            })
          })
          this.courseOutcome.setValue(this.cloupdate);
          this.performanceIndicators.setValue(this.piupdate);
          this.topics.setValue(this.topicupdate);
          this.tlo.setValue(this.tloupdate);
          this.bloomLevel.setValue(this.bloomupdate);
          this.questType.setValue(this.questTypeupdate);
          this.difficultyLevel.setValue(this.diffupdate);
          this.marks.setValue(marks);
        });
        this.isSaveHide = true;
        this.isUpdateHide = false;
      }

    })
  }


  updatePost(updatePost) {

    this.getTopic();
    let topic_value = this.topics.value

    if ((topic_value == null || topic_value == '') || (updatePost.invalid)) {
      $("#topic_req").text("This field is required.");
    } else {
      var id = this.id;
      let marks = updatePost.value.marks;

      let quesData = {
        'id': id,
        'marks': marks,
        'aq_id': this.setAssignmenttQuestionId,
      }
      this.service.subUrl = 'assignment/ManageAssignment/getquestionmarksedit';
      this.service.createPost(quesData).subscribe(response => {
        if (response.json().status == 'ok') {

          var id = this.id;
          var tinymcevalue = tinymce.get('my-editor-id').getContent({ format: 'raw' })
          var user_id = localStorage.getItem('id');
          this.service.subUrl = 'assignment/ManageAssignment/updateAssignmentQuestion';
          updatePost.stringify

          let postData = {
            'tinymcevalue': tinymcevalue,
            'assignmentquestionnumber': updatePost.value.qno,
            'entityclo': updatePost.value.entityclo,
            'courseoutcome': updatePost.value.courseOutcome,
            'entitypo': updatePost.value.entitypo,
            'performanceIndicators': updatePost.value.performanceIndicators,
            'entitytopic': updatePost.value.entitytopic,
            'topics': updatePost.value.topics,
            'entitytlo': updatePost.value.entitytlo,
            'tlo': updatePost.value.tlo,
            'entitybloom': updatePost.value.entitybloom,
            'bloomLevel': updatePost.value.bloomLevel,
            'entitydifficulty': updatePost.value.entitydifficulty,
            'difficultylevel': updatePost.value.difficultyLevel,
            'questType': updatePost.value.questType,
            'entityquestion': updatePost.value.entityquestion,
            'marks': updatePost.value.marks,
            'aq_id': this.setAssignmenttQuestionId,
            'user_id': user_id
          };
          this.service.updatePost(postData).subscribe(response => {
            if (response.json().status == 'ok') {
              let type = 'success';
              let title = 'Update Success';
              let body = 'Assignment question updated successfully'
              this.toasterMsg(type, title, body);
              this.hideMsg = true;

              let data = {
                'pgrValue': this.prg,
                'crclmValue': this.crclm,
                'termValue': this.term,
                'courseValue': this.course,
                'sectionValue': this.section,
                'assignment_id': id,
                'user_id': user_id
              }
              this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
              this.service.createPost(data).subscribe(response => {
                this.assignmentQuest = response.json();
                this.assignmentQuest = Array.of(this.assignmentQuest);

                this.assignmentQuest.forEach(element => {
                  this.assignmentQuests = JSON.parse(element);
                })
                this.reset();

                tinymce.activeEditor.setContent("");
                this.checkqueType();
                this.initDropdowns();
                this.toggleMarks();
                // this.tableRerender();
                // this.dtTrigger.next();
              });


              // if (topic_value == null || topic_value == '') {
              //   $("#topic_req").text("This field is required.");
              // } else {
              // //file upload function
              // let inputE2: HTMLInputElement = this.inputE2.nativeElement;
              // //get the total amount of files attached to the file input.
              // let fileCount: number = inputE2.files.length;
              // // let fileList: FileList = event.target.files;
              // if (fileCount > 0) {
              //   let file: File = fileCount[0];
              //   let formData: FormData = new FormData();
              //   formData.append('userdoc', inputE2.files.item(0));
              //   let headers = new Headers();
              //   // No need to include Content-Type in Angular 4 /
              //   headers.append('Content-Type', 'multipart/form-data');
              //   headers.append('Accept', 'application/json');
              //   // let options = new RequestOptions({ headers: headers });
              //   var curriculumValue = localStorage.getItem('currDropdown');
              //   var courseValue = localStorage.getItem('courseDropdown');
              //   curriculumValue = curriculumValue.replace(/ /g, '_');
              //   courseValue = courseValue.replace(/ /g, '_');
              //   this.http.post(this.service.baseUrl + 'assignment/ManageAssignment/uploadUpdate/' + this.setAssignmenttQuestionId + '/' + curriculumValue + '/' + courseValue, formData)
              //     .map(res => res.json())
              //     .catch(error => Observable.throw(error))
              //     .subscribe(response => {
              //       let data = {
              //         'pgrValue': this.prg,
              //         'crclmValue': this.crclm,
              //         'termValue': this.term,
              //         'courseValue': this.course,
              //         'sectionValue': this.section,
              //         'assignment_id': id,
              //         'user_id': user_id
              //       }
              //       this.inputEl.nativeElement.value = "";
              //       this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
              //       this.service.createPost(data).subscribe(response => {
              //         this.assignmentQuest = response.json();
              //         this.assignmentQuest = Array.of(this.assignmentQuest);
              //         this.assignmentQuest.forEach(element => {
              //           this.assignmentQuests = JSON.parse(element);
              //         })
              //         this.reset();
              //         tinymce.activeEditor.setContent("");
              //         this.checkqueType();
              //         this.initDropdowns();
              //         this.toggleMarks();
              //         this.tableRerender();
              //         this.dtTrigger.next();
              //       });
              //     })
              // }
              // }
            }
            else {
              let type = 'error';
              let title = 'Update Fail';
              let body = 'Assignment question update failed please try again'
              this.toasterMsg(type, title, body);
              this.manageAssignmentForm.reset();
              this.tinyMceValue = null;
            }
          });
        }
        else {
          let type = 'error';
          let title = 'Update Fail';
          let body = 'Assignment question marks should be less than total marks'
          this.toasterMsg(type, title, body);
          // this.manageAssignmentForm.reset();
          //this.ngOnInit();
        }
      });
      $('html,body').animate({ scrollTop: 0 }, 'slow');
    }
  }

  //clear the previous selected file while updating

  clearBrowseUpdate() {
    this.inputE2.nativeElement.value = "";
  }

  //to update the file

  uploadUpdate(updatePost) {
    this.getTopic();
    let topic_value = this.topics.value

    if ((topic_value == null || topic_value == '') || (updatePost.invalid)) {
      $("#topic_req").text("This field is required.");
    } else {
      var id = this.id;
      let marks = updatePost.value.marks;
      let quesData = {
        'id': id,
        'marks': marks,
        'aq_id': this.setAssignmenttQuestionId,
      }
      this.service.subUrl = 'assignment/ManageAssignment/getquestionmarksedit';
      this.service.createPost(quesData).subscribe(response => {
        if (response.json().status == 'ok') {
          let inputE2: HTMLInputElement = this.inputE2.nativeElement;
          let fileCount: number = inputE2.files.length;
          var size = inputE2.files.item(0).size;
          if (size > 1000000) {
            let type = 'error';
            let title = 'File Upload Fail';
            let body = 'File size should be less than 1 MB'
            this.toasterMsg(type, title, body);
          } else {
            var id = this.id;
            var tinymcevalue = this.textvalue;
            var user_id = localStorage.getItem('id');
            this.service.subUrl = 'assignment/ManageAssignment/updateAssignmentQuestion';
            updatePost.stringify
            let postData = {
              'tinymcevalue': tinymcevalue,
              'assignmentquestionnumber': updatePost.value.qno,
              'entityclo': updatePost.value.entityclo,
              'courseoutcome': updatePost.value.courseOutcome,
              'entitypo': updatePost.value.entitypo,
              'performanceIndicators': updatePost.value.performanceIndicators,
              'entitytopic': updatePost.value.entitytopic,
              'topics': updatePost.value.topics,
              'entitytlo': updatePost.value.entitytlo,
              'tlo': updatePost.value.tlo,
              'entitybloom': updatePost.value.entitybloom,
              'bloomLevel': updatePost.value.bloomLevel,
              'entitydifficulty': updatePost.value.entitydifficulty,
              'difficultylevel': updatePost.value.difficultyLevel,
              'questType': updatePost.value.questType,
              'entityquestion': updatePost.value.entityquestion,
              'marks': updatePost.value.marks,
              'aq_id': this.setAssignmenttQuestionId,
              'user_id': user_id
            };
            this.service.updatePost(postData).subscribe(response => {
              if (response.json().status == 'ok') {

                if (fileCount > 0) {
                  let file: File = fileCount[0];
                  let formData: FormData = new FormData();
                  formData.append('userdoc', inputE2.files.item(0));
                  let headers = new Headers();
                  // No need to include Content-Type in Angular 4 /
                  headers.append('Content-Type', 'multipart/form-data');
                  headers.append('Accept', 'application/json');
                  // let options = new RequestOptions({ headers: headers });
                  var curriculumValue = localStorage.getItem('currDropdown');
                  var courseValue = localStorage.getItem('courseDropdown');
                  curriculumValue = curriculumValue.replace(/ /g, '_');
                  courseValue = courseValue.replace(/ /g, '_');
                  this.http.post(this.service.baseUrl + 'assignment/ManageAssignment/uploadUpdate/' + this.setAssignmenttQuestionId + '/' + curriculumValue + '/' + courseValue, formData)
                    .map(res => res.json())
                    .catch(error => Observable.throw(error))
                    .subscribe(response => {
                      let data = {
                        'pgrValue': this.prg,
                        'crclmValue': this.crclm,
                        'termValue': this.term,
                        'courseValue': this.course,
                        'sectionValue': this.section,
                        'assignment_id': id,
                        'user_id': user_id
                      }

                      this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
                      this.service.createPost(data).subscribe(response => {
                        this.assignmentQuest = response.json();
                        this.assignmentQuest = Array.of(this.assignmentQuest);

                        this.assignmentQuest.forEach(element => {
                          this.assignmentQuests = JSON.parse(element);
                        })
                        this.reset();

                        tinymce.activeEditor.setContent("");
                        this.checkqueType();
                        this.initDropdowns();
                        this.toggleMarks();
                        // this.tableRerender();
                        // this.dtTrigger.next();

                      });
                    })

                }

                let type = 'success';
                let title = 'Update Success';
                let body = 'Assignment question updated successfully'
                this.toasterMsg(type, title, body);
                this.hideMsg = true;

              }
              else {
                let type = 'error';
                let title = 'Update Fail';
                let body = 'Assignment question update failed please try again'
                this.toasterMsg(type, title, body);
                this.manageAssignmentForm.reset();
                this.tinyMceValue = null;

              }
            });
          }
        }
        else {
          let type = 'error';
          let title = 'Update Fail';
          let body = 'Assignment question marks should be less than total marks'
          this.toasterMsg(type, title, body);

        }
      });

      $('html,body').animate({ scrollTop: 0 }, 'slow');
    }
  }

  reset() {

    this.manageAssignmentForm.reset();
    this.initDropdowns();
    this.title = "Add Assignment";
    this.isSaveHide = false;
    this.isUpdateHide = true;
    this.hideMsg = true;
    $("#error_tinymce").empty();
    this.setQnum();
    this.setTotlaMarks();

  }



  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    var a_id = this.id;
    let postparams = { 'id': a_id }
    this.service.subUrl = 'assignment/ManageAssignment/getStudentSubmissionStatus';
    this.service.createPost(postparams).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Student(s) have already submitted the assignment'
        this.toasterMsg(type, title, body);

      } else {

        let assignmentId = deleteElement.getAttribute('assignmentId');
        let delAssignmentId;
        this.delAssignmentId = assignmentId;
        (<any>jQuery('#assignmentDeleteModal')).modal('show');
      }
    })
  }


  delete(assignmentIdInput: HTMLInputElement) {

    var id = assignmentIdInput.value;
    var assignId = this.id
    var res;
    var user_id = localStorage.getItem('id');
    this.service.subUrl = 'assignment/ManageAssignment/deleteQuestion';
    this.service.createPost(id).subscribe(response => {
      if (response.json().status == 'ok') {
        let data = {
          'pgrValue': this.prg,
          'crclmValue': this.crclm,
          'termValue': this.term,
          'courseValue': this.course,
          'sectionValue': this.section,
          'assignment_id': assignId,
          'user_id': user_id
        }
        this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
        this.service.createPost(data).subscribe(response => {
          this.assignmentQuest = response.json();
          this.assignmentQuest = Array.of(this.assignmentQuest);

          this.assignmentQuest.forEach(element => {
            this.assignmentQuests = JSON.parse(element);
          })
          this.reset();
          tinymce.activeEditor.setContent("");
          this.checkqueType();
          this.initDropdowns();
          this.toggleMarks();

          // this.tableRerender();
          // this.dtTrigger.next();

        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Assignment question deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#assignmentDeleteModal')).modal('hide');

      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Assignment question delete failed please try again'
        this.toasterMsg(type, title, body);

      }
    });
  }
  deleteAllWarning(deleteAllElement: HTMLElement, modalEle: HTMLDivElement) {


    this.deleteAllAssignId = this.checked;
    let postData = {
      'deleteAllAssignId': this.deleteAllAssignId
    }
    this.service.subUrl = 'assignment/Assignment/getAssignsubStatusForDeleteAll';
    this.service.createPost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        let delAssignmentId;
        this.delAssignmentId = this.deleteAllAssignId;
        (<any>jQuery('#assignmentDeleteAllModal')).modal('show');
      }
      else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'One or more assignments are submitted already'
        this.toasterMsg(type, title, body);
      }
    });

  }

  deleteSelected(questId) {

    if ($("#" + questId).is(":checked")) {
      if (this.checked.indexOf(questId) == -1) {
        this.checked.push(questId);
        $("#" + questId).prop('checked', true);
      }
      if ((this.checked.length > 1)) {
        $("#delete_question").removeAttr("style")
      }
    }
    else {

      var data = this.checked.indexOf(questId);
      this.checked.splice(data, 1);
      $("#" + questId).prop('checked', false);

      if ((this.checked.length == 1)) {

        $("#delete_question").attr("style", "visibility: hidden")
      }
    }
    this.deleteAllId = JSON.parse(JSON.stringify(this.assignmentQuests));

    if (this.checked.length == this.deleteAllId.length) {
      $("#checkAllQuestions").prop('checked', true);
    } else {
      $("#checkAllQuestions").prop('checked', false);
    }
    console.log(this.checked)
  }

  deleteAllSelected(e) {

    this.deleteAllId = JSON.parse(JSON.stringify(this.assignmentQuests));

    this.deleteAllId.forEach(element => {
      this.assign_questId = element.aq_id;

      if ((e.target.checked) && !($("#" + this.assign_questId).is(":disabled"))) {
        $("#" + this.assign_questId).prop('checked', true);
        if ((this.checked.indexOf(this.assign_questId) == -1)) {
          this.checked.push(this.assign_questId);
          $("#" + this.assign_questId).prop('checked', true);
        }
        if ((this.checked.length > 1)) {

          $("#delete_question").removeAttr("style")
        }
      }
      else if (!(e.target.checked) && !($("#" + this.assign_questId).is(":disabled"))) {
        $("#" + this.assign_questId).prop('checked', false);
        $("#delete_check").prop('checked', false);
        var data = this.checked.indexOf(this.assign_questId);
        this.checked.splice(data, 1);

        if ((this.checked.length == 1)) {

          $("#delete_question").attr("style", "visibility: hidden")
        }
      }
      console.log(this.assign_questId);
    })
  }

  deleteAll() {
    let assignid = this.id;
    var user_id = localStorage.getItem('id');
    let getdata = {
      'pgrValue': this.prg,
      'crclmValue': this.crclm,
      'termValue': this.term,
      'courseValue': this.course,
      'sectionValue': this.section,
      'assignment_id': assignid
    }
    this.deleteAllId = this.checked;
    let postData = {
      'deleteAllId': this.deleteAllId
    }
    this.service.subUrl = 'assignment/ManageAssignment/deleteMultipleQuestion';
    this.service.createPost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        let data = {
          'pgrValue': this.prg,
          'crclmValue': this.crclm,
          'termValue': this.term,
          'courseValue': this.course,
          'sectionValue': this.section,
          'assignment_id': assignid,
          'user_id': user_id
        }
        this.service.subUrl = 'assignment/ManageAssignment/getAssignmentQuest';
        this.service.createPost(data).subscribe(response => {
          this.assignmentQuest = response.json();
          this.assignmentQuest = Array.of(this.assignmentQuest);

          this.assignmentQuest.forEach(element => {
            this.assignmentQuests = JSON.parse(element);
          })
          this.reset();

          tinymce.activeEditor.setContent("");
          this.checkqueType();
          this.initDropdowns();
          this.toggleMarks();
          this.tableRerender();
          // this.setTotlaMarks()
          // this.dtTrigger.next();

        });
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Assignment questions deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#assignmentDeleteAllModal')).modal('hide');

        $("#checkAllQuestions").prop('checked', false);
        $("#delete_question").attr("style", "visibility: hidden")
        this.checked = [];

      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Assignment questions delete failed please try again'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#assignmentDeleteAllModal')).modal('hide');

      }
    });
  }





  tableRerender(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      // Destroy the table first
      dtInstance.destroy();
    });
  }
  ngAfterViewInit(): void {
    this.dtTrigger.next();
  }
  toasterMsg(type, title, body) {
    this.toast.toastType = type;
    this.toast.toastTitle = title;
    this.toast.toastBody = body;
    this.tosterconfig = new ToasterConfig({
      positionClass: 'toast-bottom-right',
      tapToDismiss: false,
      showCloseButton: true,
      animation: 'slideDown'
    });
    this.toast.toastMsg;
  }

}
