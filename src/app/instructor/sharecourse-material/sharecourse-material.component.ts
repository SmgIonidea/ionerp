import { Event } from '@angular/router';
import { Component, OnInit, Injectable, Input, ViewChild, AfterViewInit, ElementRef } from '@angular/core';
import { IMultiSelectOption } from 'angular-2-dropdown-multiselect';
import { IMultiSelectSettings } from 'angular-2-dropdown-multiselect';
import { IMultiSelectTexts } from 'angular-2-dropdown-multiselect';
import { FormsModule } from '@angular/forms';
import { DropdownComponent } from './../../dropdown/dropdown.component';
import { PostService } from './../../services/post.service';
import { Http, Response } from '@angular/http';
import 'rxjs/add/operator/map';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { ContentWrapperComponent } from './../../content-wrapper/content-wrapper.component';
import { CharctersOnlyValidation } from './../../custom.validators';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { Observable } from 'rxjs/Observable';
import { RequestOptions, Headers } from '@angular/http';
import { Title } from '@angular/platform-browser';

@Component({
  selector: 'app-sharecourse-material',
  templateUrl: './sharecourse-material.component.html',
  styleUrls: ['./sharecourse-material.component.css']
})

@Injectable()

export class SharecourseMaterialComponent implements OnInit, AfterViewInit {

  constructor(
    public titleService: Title,
    private service: PostService,
    private http: Http,
    private toast: ToastService) { }

  /* Global Variable Declarations */
  tosterconfig;
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  // listPageHeading = "Department List";
  // operationHeading = "Department Add/Edit";
  posts = [];
  isSaveHide: boolean;
  isUpdateHide: boolean;
  isFileSize: boolean;
  setMatId: any; // Course Material id used to update the details
  setUrlFlag: any;
  @Input('matId') delMatId; // Input binding used to place Course Material id in hidden text box to delete the course material. this is one more way of input binding.
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  editedIds: number[] = [];
  myOptions: IMultiSelectOption[];
  // topicIdMultiValue: Array<any>;
  mySettings: IMultiSelectSettings = {};
  myTexts: IMultiSelectTexts = {};

  topicIdMultiValue = [];

  public type: string;
  //to clear the input field
  clearValue: string = '';
  clearInputField() {
    this.clearValue = '';
    this.isFileSize = false;
    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'userId': this.userId
    };

    this.service.subUrl = 'course_material/Coursematerial/topic';
    this.service.createPost(postData).subscribe(response => {
      this.myOptions = response.json();
    });

    this.addDocForm.reset();
  }

  //disable radio button
  radioButtonDisable = false;
  //base url assigned to variable
  curriculumValueFile: string;
  courseValueFile: string;
  baseUrl: string;
  @Input() multiple: boolean = false;
  @ViewChild('fileInput') inputEl: ElementRef; //file upload
  size: number; //file size
  fileSizeExt: string; //file size with Extension

  title: string; //load title

  dtInstance: DataTables.Api;
  public currentPageVal;

  programType: Array<string>;
  curriculumType: Array<string> = [];
  termType: Array<string> = [];
  courseType: Array<string> = [];
  sectionType: IMultiSelectOption[];
  pgmId: any = 0;
  crclmId: any = 0;
  termId: any = 0;
  crsId: any = 0;
  sectionId: any = 0;
  userId;

  ngOnInit() {

    this.currentPageVal = 'shareCourseMaterial';
    this.title = 'Add Course Material';

    this.userId = localStorage.getItem('id');
    this.service.subUrl = 'configuration/Master/getProgram';
    this.service.createPost(this.userId).subscribe(response => {
      this.programType = response.json();
      if (localStorage.getItem('programDropdownId') != null && localStorage.getItem('programDropdownId') != '0') {
        this.programType.forEach(termElement => {
          if (termElement['pgm_id'] == localStorage.getItem('programDropdownId')) {
            this.pgmId = termElement['pgm_id'];
            let postData = {
              'userId': this.userId,
              'pgmDrop': this.pgmId,
            };
            this.service.subUrl = 'configuration/Master/getCurriculum';
            this.service.createPost(postData).subscribe(response => {
              this.curriculumType = response.json();
              if (localStorage.getItem('currDropdownId') != null && localStorage.getItem('currDropdownId') != '0') {
                this.curriculumType.forEach(termElement => {
                  if (termElement['crclm_id'] == localStorage.getItem('currDropdownId')) {
                    this.crclmId = termElement['crclm_id'];
                    let postData = {
                      'userId': this.userId,
                      'curDrop': this.crclmId,
                    };
                    this.service.subUrl = 'configuration/Master/getTerm';
                    this.service.createPost(postData).subscribe(response => {
                      this.termType = response.json();
                      if (localStorage.getItem('termDropdownId') != null && localStorage.getItem('termDropdownId') != '0') {
                        this.termType.forEach(termElement => {
                          if (termElement['crclm_term_id'] == localStorage.getItem('termDropdownId')) {
                            this.termId = termElement['crclm_term_id'];
                            let postData = {
                              'userId': this.userId,
                              'termDrop': this.termId,
                            };
                            this.service.subUrl = 'configuration/Master/getCourse';
                            this.service.createPost(postData).subscribe(response => {
                              this.courseType = response.json();
                              if (localStorage.getItem('courseDropdownId') != null && localStorage.getItem('courseDropdownId') != '0') {
                                this.courseType.forEach(termElement => {
                                  if (termElement['crs_id'] == localStorage.getItem('courseDropdownId')) {
                                    this.crsId = termElement['crs_id'];
                                    let postData = {
                                      'userId': this.userId,
                                      'crsDrop': this.crsId,
                                    };
                                    this.service.subUrl = 'configuration/Master/getSection';
                                    this.service.createPost(postData).subscribe(response => {
                                      this.sectionType = response.json();
                                      if (localStorage.getItem('sectionDropdownId') != null && localStorage.getItem('sectionDropdownId') != '0') {
                                        this.sectionType.forEach(termElement => {
                                          if (termElement['id'] == localStorage.getItem('sectionDropdownId')) {
                                            this.sectionId = termElement['id'];
                                            this.getdropdowndata();
                                          }
                                        });
                                      }
                                    });
                                  }
                                });
                              }
                            });
                          }
                        });
                      }
                    });
                  }
                });
              }
            });
          }
        });
      }
    });

    // function to display dropdown upwards based on window size
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

  }

  //method to load curriculum dropdown data
  curriculum(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('currDropdownId');
    localStorage.removeItem('termDropdownId');
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');
    this.curriculumType = [];
    this.termType = [];
    this.courseType = [];
    this.sectionType = [];
    this.crclmId = 0;
    this.termId = 0;
    this.crsId = 0;
    this.sectionId = 0;

    this.programType.forEach(programElement => {
      if (programElement['pgm_id'] == event) {
        localStorage.setItem('programDropdown', programElement['pgm_acronym']);
        localStorage.setItem('programDropdownId', programElement['pgm_id']);
      }
    });
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'pgmDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getCurriculum';
    this.service.createPost(postData).subscribe(response => {
      this.curriculumType = response.json();
    });
  }

  //method to load term dropdown data
  term(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('termDropdownId');
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');
    this.termType = [];
    this.courseType = [];
    this.sectionType = [];
    this.termId = 0;
    this.crsId = 0;
    this.sectionId = 0;

    this.curriculumType.forEach(curriculumElement => {
      if (curriculumElement['crclm_id'] == event) {
        localStorage.setItem('currDropdown', curriculumElement['crclm_name']);
        localStorage.setItem('currDropdownId', curriculumElement['crclm_id']);
      }
    });
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'curDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getTerm';
    this.service.createPost(postData).subscribe(response => {
      this.termType = response.json();
    });
  }

  //method to load course dropdown data
  course(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('courseDropdownId');
    localStorage.removeItem('sectionDropdownId');
    this.courseType = [];
    this.sectionType = [];
    this.crsId = 0;
    this.sectionId = 0;

    this.termType.forEach(termElement => {
      if (termElement['crclm_term_id'] == event) {
        localStorage.setItem('termDropdown', termElement['term_name']);
        localStorage.setItem('termDropdownId', termElement['crclm_term_id']);
      }
    });
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'termDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getCourse';
    this.service.createPost(postData).subscribe(response => {
      this.courseType = response.json();
    });
  }

  //method to load section dropdown data
  section(event) {

    this.posts = [];
    this.tableRerender();
    this.dtTrigger.next();
    localStorage.removeItem('sectionDropdownId');
    this.sectionType = [];
    this.sectionId = 0;

    this.courseType.forEach(courseElement => {
      if (courseElement['crs_id'] == event) {
        localStorage.setItem('courseDropdown', courseElement['crs_title']);
        // localStorage.setItem('courseCodeDropdown', courseElement['crs_code']);
        localStorage.setItem('courseDropdownId', courseElement['crs_id']);
      }
    });
    // var userId = localStorage.getItem('id');
    let postData = {
      'userId': this.userId,
      'crsDrop': event,
    };
    this.service.subUrl = 'configuration/Master/getSection';
    this.service.createPost(postData).subscribe(response => {
      this.sectionType = response.json();
    });
  }

  getdropdowndata() {

    this.sectionType.forEach(sectionElement => {
      if (sectionElement['id'] == this.sectionId) {
        localStorage.setItem('sectionDropdown', sectionElement['name']);
        localStorage.setItem('sectionDropdownId', sectionElement['id']);
      }
    });

    this.title = 'Add Course Material';

    this.curriculumValueFile = localStorage.getItem('currDropdown');
    this.courseValueFile = localStorage.getItem('courseDropdown');
    if (localStorage.getItem('currDropdown') && localStorage.getItem('courseDropdown')) {
      this.curriculumValueFile = this.curriculumValueFile.replace(/ /g, '_');
      this.courseValueFile = this.courseValueFile.replace(/ /g, '_');
    }

    this.baseUrl = this.service.baseUrlFile + 'course_materials/' + this.curriculumValueFile + '/' + this.courseValueFile + '/faculty/';
    this.radioButtonDisable = false;
    this.titleService.setTitle('ShareCourseMaterial | IONCUDOS');

    this.type = "Documents"; //default tab selected
    this.size = null; //file size
    this.isFileSize = false;

    // var programValue = localStorage.getItem('programDropdownId');
    // var curriculumValue = localStorage.getItem('currDropdownId');
    // var termValue = localStorage.getItem('termDropdownId');
    // var courseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');
    // var userId = localStorage.getItem('id');

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'userId': this.userId
    };

    this.service.subUrl = 'course_material/Coursematerial/index';
    this.service.createPost(postData).subscribe(response => {
      this.posts = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
    });
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.service.subUrl = 'course_material/Coursematerial/topic';
    this.service.createPost(postData).subscribe(response => {
      this.myOptions = response.json();
    });

    this.myTexts = {
      checkAll: 'Select all',
      uncheckAll: 'Unselect all',
      checked: 'item selected',
      checkedPlural: 'items selected',
      searchPlaceholder: 'Find',
      defaultTitle: 'Select Topics',
      allSelected: 'All selected',
    };
  }

  private addDocForm = new FormGroup({

    addDocFiles: new FormControl('', [
      Validators.required]),

    addUrlFiles: new FormControl('', [
      Validators.required]),

    addDocTopic: new FormControl('', [
    ]),

    addDocInfo: new FormControl('', [
    ])
  });


  get addDocFiles() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.addDocForm.get('addDocFiles');
  }
  get addUrlFiles() {
    return this.addDocForm.get('addUrlFiles');
  }
  get addDocTopic() {
    return this.addDocForm.get('addDocTopic');
  }
  get addDocInfo() {
    return this.addDocForm.get('addDocInfo');
  }

  //course material insert function

  createPost(Form) {

    this.service.subUrl = 'course_material/Coursematerial/createShareCourseMaterial';
    let courseMaterial = Form.value; // Text Field/Form Data in Json Format
    // var shareProgramValue = localStorage.getItem('programDropdownId');
    // var shareCurriculumValue = localStorage.getItem('currDropdownId');
    // var shareTermValue = localStorage.getItem('termDropdownId');
    // var shareCourseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');
    // var userId = localStorage.getItem('id');

    if (this.pgmId && this.crclmId && this.termId && this.crsId && this.sectionId) {

      let postData = {
        'pgmDrop': this.pgmId,
        'curclmDrop': this.crclmId,
        'termDrop': this.termId,
        'courseDrop': this.crsId,
        'crsMat': courseMaterial,
        'secDrop': this.sectionId,
        'userId': this.userId
      };

      this.service.createPost(postData).subscribe(response => {

        if (response.json().status == 'ok') {
          this.service.subUrl = 'course_material/Coursematerial/index';
          this.service.createPost(postData).subscribe(response => {
            this.posts = response.json();
            this.tableRerender();
            this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
          });
          let type = 'success';
          let title = 'Add Success';
          let body = 'New course material added successfully'
          this.toasterMsg(type, title, body);
          this.addDocForm.reset();
          this.getdropdowndata();
          //course material file upload function

          let inputEl: HTMLInputElement = this.inputEl.nativeElement;
          //get the total amount of files attached to the file input.
          let fileCount: number = inputEl.files.length;

          if (fileCount > 0) {
            let file: File = fileCount[0];
            let formData: FormData = new FormData();
            formData.append('userdoc', inputEl.files.item(0));

            let headers = new Headers();
            // No need to include Content-Type in Angular 4 /
            headers.append('Content-Type', 'multipart/form-data');
            headers.append('Accept', 'application/json');

            this.http.post(this.service.baseUrl + 'course_material/Coursematerial/upload/' + this.curriculumValueFile + '/' + this.courseValueFile, formData)

              .subscribe(response => {
                this.service.subUrl = 'course_material/Coursematerial/index';
                this.service.createPost(postData).subscribe(response => {
                  this.posts = response.json();
                  this.tableRerender();
                  this.dtTrigger.next(); // Calling the DT trigger to manually render the table  
                  this.getdropdowndata();
                });

              })
          }
        } else {
          let type = 'error';
          let title = 'Add Fail';
          let body = 'New course material add failed please try again'
          this.toasterMsg(type, title, body);
          this.addDocForm.reset();
          this.getdropdowndata();
        }
      });
    } else {
      let type = 'error';
      let title = 'Add Fail';
      let body = 'Please select dropdown values before you add'
      this.toasterMsg(type, title, body);
    }
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  //course material edit function

  editShareCourseMaterial(editElement: HTMLElement) {

    this.title = 'Edit Course Material';

    this.radioButtonDisable = true;
    let documentUrl = editElement.getAttribute('documentUrl');
    let topicId = editElement.getAttribute('topicId').toString();

    this.topicIdMultiValue = topicId.split(',');

    this.topicIdMultiValue.forEach(element => {

      this.editedIds.push(element);

    })

    let desc = editElement.getAttribute('desc');

    let matId = editElement.getAttribute('matId');

    let urlFlag = editElement.getAttribute('urlFlag');

    if (urlFlag == '0') {
      this.type = "Documents";

      this.setMatId = matId;
      this.setUrlFlag = urlFlag;
      this.isSaveHide = true;
      this.isUpdateHide = false;
    }
    else {
      this.type = "URL";

      this.setMatId = matId;
      this.setUrlFlag = urlFlag;
      this.isSaveHide = true;
      this.isUpdateHide = false;
    }
    this.addDocFiles.setValue(documentUrl);
    this.addUrlFiles.setValue(documentUrl);
    this.addDocTopic.setValue(this.topicIdMultiValue);
    this.addDocInfo.setValue(desc);
  }

  //course material update function

  updatePost(updatePost) {
    this.service.subUrl = 'course_material/Coursematerial/updateShareCourseMaterial';
    updatePost.stringify

    // var shareEditProgramValue = localStorage.getItem('programDropdownId');
    // var shareEditCurriculumValue = localStorage.getItem('currDropdownId');
    // var shareEditTermValue = localStorage.getItem('termDropdownId');
    // var shareEditCourseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');
    // var userId = localStorage.getItem('id');

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'addDocFiles': updatePost.value.addDocFiles,
      'addUrlFiles': updatePost.value.addUrlFiles,
      'addDocTopic': updatePost.value.addDocTopic,
      'addDocInfo': updatePost.value.addDocInfo,
      'matId': this.setMatId,
      'urlFlag': this.setUrlFlag,
      'userId': this.userId
    };
    // let postData = updatePost.value; // Text Field/Form Data in Json Format
    this.service.updatePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'course_material/Coursematerial/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });
        let type = 'success';
        let title = 'Update Success';
        let body = 'Course material updated successfully'
        this.toasterMsg(type, title, body);
        this.addDocForm.reset();
        this.getdropdowndata();

        //course material file upload function

        let inputEl: HTMLInputElement = this.inputEl.nativeElement;
        //get the total amount of files attached to the file input.
        let fileCount: number = inputEl.files.length;
        if (fileCount > 0) {
          let file: File = fileCount[0];
          let formData: FormData = new FormData();
          formData.append('userdoc', inputEl.files.item(0));
          let headers = new Headers();
          // No need to include Content-Type in Angular 4 /
          headers.append('Content-Type', 'multipart/form-data');
          headers.append('Accept', 'application/json');
          this.http.post(this.service.baseUrl + 'course_material/Coursematerial/uploadUpdate/' + this.setMatId + '/' + this.curriculumValueFile + '/' + this.courseValueFile, formData)
            .subscribe(response => {
              this.service.subUrl = 'course_material/Coursematerial/index';
              this.service.createPost(postData).subscribe(response => {
                this.posts = response.json();
                this.tableRerender();
                this.dtTrigger.next(); // Calling the DT trigger to manually render the table    
                this.getdropdowndata();
              });

            })
        }
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Course material update failed please try again'
        this.toasterMsg(type, title, body);
        this.addDocForm.reset();
        this.getdropdowndata();
      }
    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  //course material cancel update function

  cancelUpdate() {
    this.title = 'Add Course Material';
    this.radioButtonDisable = false;
    this.type = "Documents"; //default tab selected
    this.size = null; //file size
    this.clearValue = '';
    this.isFileSize = false;
    this.addDocForm.enable();

    // var programValue = localStorage.getItem('programDropdownId');
    // var curriculumValue = localStorage.getItem('currDropdownId');
    // var termValue = localStorage.getItem('termDropdownId');
    // var courseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');
    // var userId = localStorage.getItem('id');

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'userId': this.userId
    };

    this.service.subUrl = 'course_material/Coursematerial/topic';
    this.service.createPost(postData).subscribe(response => {
      this.myOptions = response.json();
    });
    this.addDocForm.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
    // if (this.type = "Documents") {
    //   this.inputEl.nativeElement.value = "";
    //   this.clearValue = '';
    //   this.isFileSize = false;
    // }
  }

  //course material reset function

  reset() {
    this.clearInputField();
    this.addDocForm.reset();
    this.radioButtonDisable = false;
    this.addDocForm.enable();
    // if (this.type = "Documents") {
    //   this.inputEl.nativeElement.value = "";
    //   this.clearValue = '';
    //   this.isFileSize = false;
    // }

  }

  //clear previous selected file

  clearBrowse(){
    this.inputEl.nativeElement.value = "";
      this.clearValue = '';
      this.isFileSize = false;
  }

  //course material delete warning function

  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {
    let matId = deleteElement.getAttribute('matId');
    let delMatId;
    this.delMatId = matId;
    (<any>jQuery('#courseMaterialDeleteModal')).modal('show');
  }

  //course material delete function

  deleteCourseMaterialData(matIdInput: HTMLInputElement) {
    console.log(matIdInput.value);
    this.service.subUrl = 'course_material/Coursematerial/deleteShareCourseMaterial';

    // var shareDelProgramValue = localStorage.getItem('programDropdownId');
    // var shareDelCurriculumValue = localStorage.getItem('currDropdownId');
    // var shareDelTermValue = localStorage.getItem('termDropdownId');
    // var shareDelCourseValue = localStorage.getItem('courseDropdownId');
    // var sectionValue = localStorage.getItem('sectionDropdownId');

    let postData = {
      'pgmDrop': this.pgmId,
      'curclmDrop': this.crclmId,
      'termDrop': this.termId,
      'courseDrop': this.crsId,
      'secDrop': this.sectionId,
      'matId': matIdInput.value
    };
    this.service.deletePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {

        this.service.subUrl = 'course_material/Coursematerial/index';
        this.service.createPost(postData).subscribe(response => {
          this.posts = response.json();
          this.tableRerender();
          this.dtTrigger.next(); // Calling the DT trigger to manually render the table     
        });

        let type = 'success';
        let title = 'Delete Success';
        let body = 'Course material deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#courseMaterialDeleteModal')).modal('hide');
        this.addDocForm.reset();
        this.getdropdowndata();
      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Course material delete failed please try again'
        this.toasterMsg(type, title, body);
        this.addDocForm.reset();
        this.getdropdowndata();
      }

    });
    console.log(this.posts);
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

  onChange() {
    console.log(this.editedIds);
  }

  //get file name to the input field function

  getFileName(replaceElement: HTMLElement, splitElement: HTMLElement) {
    this.radioButtonDisable = false;
    this.addDocForm.enable();
    //get filepath 
    var value = JSON.stringify($('#userdoc').val());
    //get filepath replace forward slash with backward slash
    var filePath = value.replace(/\\/g, "/");
    //remove backward slash and "
    var path = filePath.split('/').pop();
    path = path.replace('"', '');
    //get the fileName
    var fileName = JSON.stringify($('#addDocFiles').val(path));
    this.addDocFiles.setValue(path);

  }

  //get file size

  getFileSize() {
    let inputEl: HTMLInputElement = this.inputEl.nativeElement;

    let fileCount: number = inputEl.files.length;

    if (fileCount > 0) {

      this.size = inputEl.files.item(0).size;
      if (this.size > 5000000) {
        let type = 'error';
        let title = 'File Upload Fail';
        let body = 'File size should be less than 5 MB'
        this.toasterMsg(type, title, body);
        this.radioButtonDisable = true;
        this.addDocForm.disable();
      }

      var fSExt = new Array('Bytes', 'KB', 'MB', 'GB');
      var fSize = this.size;
      var i = 0;
      while (fSize > 900) {
        fSize /= 1024;
        i++;
      }
      this.fileSizeExt = (Math.round(fSize * 100) / 100) + ' ' + fSExt[i];

      this.isFileSize = true;

    }
  }

}