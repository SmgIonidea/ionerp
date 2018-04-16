import { Component, OnInit, TemplateRef, ViewChild, Input, AfterViewInit, Injectable, ElementRef } from '@angular/core';
import { PostService } from './../../services/post.service';
import { RouterModule } from '@angular/router';
import { ActivatedRoute, Params } from "@angular/router";
import { Title } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import 'rxjs/add/operator/map';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { Http, Response } from '@angular/http';
import { ToasterConfig } from 'angular2-toaster';
import { ToastService } from './../../common/toast.service';
import { Subject } from 'rxjs/Rx';
import * as $ from 'jquery';
import { TinymceComponent } from '../../thirdparty/tinymce/tinymce.component';
interface HTMLInputEvent extends Event {
  target: HTMLInputElement & EventTarget;
}

// @Injectable()
@Component({
  selector: 'app-take-assignment',
  templateUrl: './take-assignment.component.html',
  styleUrls: ['./take-assignment.component.css']
})
export class TakeAssignmentComponent implements OnInit {

  constructor(private service: PostService,
    private route: ActivatedRoute,
    public titleService: Title,
    private http: Http,
    private toast: ToastService) { }

  /* Global Variable Declarations */
  tosterconfig;

  private sub: any;
  posts = [];
  questionType = [];
  questions = [];
  ids;
  file = [];
  files = [];
  ansFlag = [];
  content = [];
  status = [];

  id;
  takeAssignTextValue;

  tinymcearray: Array<any> = [];

  //disable radio button
  radioButtonDisable = false;

  isFileSize: boolean;
  @ViewChild('fileInput') inputEl: ElementRef; //file upload
  size: number; //file size
  fileSizeExt: string; //file size with Extension

  curriculumValueFile: string;
  courseValueFile: string;
  baseUrl: string;

  curriculumValueFileId: string;
  courseValueFileId: string;

  public type: string;

  TinyMce: boolean = false;
  Button: boolean = true;

  //to clear the input field
  clearValue: string = '';
  clearInputField() {
    this.clearValue = '';
    this.isFileSize = false;
  }

  upload: boolean = false;

  params;

  ngOnInit() {
    this.titleService.setTitle('TakeAssignment | IONCUDOS');



    this.size = null; //file size
    this.isFileSize = false;

    this.curriculumValueFile = localStorage.getItem('currDropdown');
    this.courseValueFile = localStorage.getItem('courseDropdown');
    this.curriculumValueFile = this.curriculumValueFile.replace(/ /g, '_');
    this.courseValueFile = this.courseValueFile.replace(/ /g, '_');
    this.baseUrl = this.service.baseUrlFile + 'Assignment_Questions/' + this.curriculumValueFile + '/' + this.courseValueFile + '/faculty/';

    this.curriculumValueFileId = localStorage.getItem('currDropdownId');
    this.courseValueFileId = localStorage.getItem('courseDropdownId');

    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = {
          'id': +params['id'] || 0,
          'stdId': +params['studId'] || 0,
          'crclmValue': this.curriculumValueFile,
          'courseValue': this.courseValueFile,
          'crclmId': this.curriculumValueFileId,
          'courseId': this.courseValueFileId

        };

        // this.id = +params['id'] || 0;

        this.service.subUrl = 'take_assignment/Takeassignment/index';
        this.service.createPost(this.id).subscribe(response => {
          this.posts = response.json();
        });

        this.service.subUrl = 'take_assignment/Takeassignment/assignmentQuestionType';
        this.service.createPost(this.id).subscribe(response => {
          this.questionType = response.json();
        });

        this.service.subUrl = 'take_assignment/Takeassignment/assignmentQuestions';
        this.service.createPost(this.id).subscribe(response => {
          this.questions = response.json();

          this.ids = this.questions;

          this.ids.AssignmentQuestionsList.forEach(shows => {

            // tinymce.editors.hide();
            // (<any>jQuery('#my-ans-editor-id' + shows.aq_id)).hide();
            // (<any>jQuery('#my-ans-editor-id' + shows.aq_id)).addClass('test');
            // $('#my-ans-editor-id' + shows.aq_id).addClass('test');
            // $("#'my-ans-editor-id' + question.aq_id '").hide();

          })
        });
        this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerFlag';
        this.service.createPost(this.id).subscribe(response => {
          if (response.json().status == 'ok') {

            this.radioButtonDisable = true;

            this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerStatus';
            this.service.createPost(this.id).subscribe(response => {
              this.ansFlag = response.json();
              console.log(this.ansFlag[0]['ans_flag']);
              if (this.ansFlag[0]['ans_flag'] == '1') {
                this.type = "answer";

                this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerSubmitStatus';
                this.service.createPost(this.id).subscribe(response => {
                  this.status = response.json();

                  if (response.json().status == 'ok') {

                    // tinymce.activeEditor.setContent(this.content[0]['ans_content']);
                    this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerTinyMceContent';
                    this.service.createPost(this.id).subscribe(response => {
                      this.content = response.json();

                      this.content.forEach(shows => {
                        if (shows.que_content == '') {
                          tinymce.editors['my-ans-editor-id'].setContent(shows.ans_content);
                        }
                        else {
                          tinymce.editors['my-ans-editor-id' + shows.aq_id].setContent(shows.ans_content);
                        }

                      })

                      this.content.forEach(shows => {
                        if ((shows.status_flag == '2') || (shows.status_flag == '4')) {
                          if (shows.que_content == '') {
                            tinymce.editors['my-ans-editor-id'].setMode('readonly');
                          } else {
                            tinymce.editors['my-ans-editor-id' + shows.aq_id].setMode('readonly');
                          }
                          $("button[name=assignSubmit]").attr("disabled", "disabled");
                          $("button[name=assignSave]").attr("disabled", "disabled");
                        }
                        else {
                          //  $("button[name=assignSubmit]").removeAttr("disabled", "disabled");
                          // $("button[name=assignSave]").removeAttr("disabled", "disabled");
                        }

                      })

                    });

                  }
                  else {
                    this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerTinyMceContent';
                    this.service.createPost(this.id).subscribe(response => {
                      this.content = response.json();
                      this.content.forEach(shows => {
                        if (shows.que_content == '') {
                          tinymce.editors['my-ans-editor-id'].setContent(shows.ans_content);
                        }
                        else {
                          tinymce.editors['my-ans-editor-id' + shows.aq_id].setContent(shows.ans_content);
                        }

                      })
                    });

                  }

                })


              }
              if (this.ansFlag[0]['ans_flag'] == '2') {
                this.type = "upload";
                // this.upload = true;
                // $('#userdoc').addClass("cursor:not-allowed");

                this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerContent';
                this.service.createPost(this.id).subscribe(response => {
                  this.content = response.json();
                  this.content.forEach(shows => {
                    if ((shows.status_flag == '2') || (shows.status_flag == '4')) {
                      $("input[name='userdoc']").attr("disabled", "disabled");
                      // this.upload = true;
                      this.addAssignmentUpload.setValue(shows.ans_content);
                      this.addassignmentForm.disable();
                    }
                    else {
                      // this.upload = false;
                      this.addAssignmentUpload.setValue(shows.ans_content);
                    }

                  })
                });

              } else if (this.ansFlag[0]['ans_flag'] == '3') {
                this.type = "url";

                this.service.subUrl = 'take_assignment/Takeassignment/assignmentAnswerContent';
                this.service.createPost(this.id).subscribe(response => {
                  this.content = response.json();

                  this.content.forEach(shows => {
                    if ((shows.status_flag == '2') || (shows.status_flag == '4')) {
                      this.addAssignmentUrl.setValue(shows.ans_content);
                      this.addassignmentForm.disable();
                    }
                    else {
                      this.addAssignmentUrl.setValue(shows.ans_content);
                    }

                  })

                });
              }
            });
          }
          else {
            this.type = "answer"; //default tab selected
            this.radioButtonDisable = false;
          }
        });


        this.service.subUrl = 'take_assignment/Takeassignment/assignmentQuestionFile';
        this.service.createPost(this.id).subscribe(response => {
          this.file = response.json();
          this.file = Array.of(this.file);
          this.file.forEach(element => {
            this.files = JSON.parse(element);
          })
        });

      });

  }


  //Function to display TinyMce
  showTinyMce(id, btnid) {

    this.TinyMce = true;
    this.Button = false;

  }

  show(id) {


    tinymce.editors['my-ans-editor-id' + id].show(500);

  }

  showbutton(btnid) {
    $('#hide_' + btnid).show(500);
    $('#show_' + btnid).hide(500);
    $('#my-ans-editor-id' + btnid).css("display", 'none');
  }

  hidebutton(btnid) {

    $('#hide_' + btnid).hide(500);
    $('#show_' + btnid).show(500);
    $('#my-ans-editor-id' + btnid).hide();

  }

  hide(id) {

    tinymce.editors['my-ans-editor-id' + id].hide(500);


  }

  //TinyMce validation for Submit Button
  tinymceValidationSubmit(Form) {
    var content = tinymce.activeEditor.getContent();
    if (content === "" || content === null) {
      let type = 'error';
      let title = 'Add Fail';
      let body = 'Please Fill the Answer'
      this.toasterMsg(type, title, body);
    } else {

      this.service.subUrl = 'take_assignment/Takeassignment/assignmentQuestions';
      this.service.createPost(this.id).subscribe(response => {
        this.questions = response.json();

        this.ids = this.questions;
        this.tinymcearray.length = 0;
        this.ids.AssignmentQuestionsList.forEach(shows => {

          if (shows.que_content == '') {
            this.takeAssignTextValue = tinymce.get('my-ans-editor-id').getContent({ format: 'raw' });
          }
          else {
            this.takeAssignTextValue = tinymce.get('my-ans-editor-id' + shows.aq_id).getContent({ format: 'raw' });
          }


          this.tinymcearray.push({ 'id': shows.aq_id, 'content': this.takeAssignTextValue });

        })

        this.createPost(Form);
      });

    }
  }

  //TinyMce validation for Save Button
  tinymceValidationSave(Form) {
    var content = tinymce.activeEditor.getContent();
    if (content === "" || content === null) {
      let type = 'error';
      let title = 'Add Fail';
      let body = 'Please Fill the Answer'
      this.toasterMsg(type, title, body);
    } else {

      this.service.subUrl = 'take_assignment/Takeassignment/assignmentQuestions';
      this.service.createPost(this.id).subscribe(response => {
        this.questions = response.json();

        this.ids = this.questions;
        this.tinymcearray.length = 0;
        this.ids.AssignmentQuestionsList.forEach(shows => {

          if (shows.que_content == '') {
            this.takeAssignTextValue = tinymce.get('my-ans-editor-id').getContent({ format: 'raw' });
          }
          else {
            this.takeAssignTextValue = tinymce.get('my-ans-editor-id' + shows.aq_id).getContent({ format: 'raw' });
          }

          this.tinymcearray.push({ 'id': shows.aq_id, 'content': this.takeAssignTextValue });

        })
        // alert(JSON.stringify(this.tinymcearray));
        this.savePost(Form);
      });
    }
  }


  private addassignmentForm = new FormGroup({

    addAssignmentAnswer: new FormControl('', [
    ]),

    addAssignmentUpload: new FormControl('', [
      Validators.required]),

    addAssignmentUrl: new FormControl('', [
      Validators.required])

  });

  get addAssignmentAnswer() {
    /* property to access the 
    formGroup Controles. which is used to validate the form */
    return this.addassignmentForm.get('addAssignmentAnswer');
  }
  get addAssignmentUpload() {
    return this.addassignmentForm.get('addAssignmentUpload');
  }
  get addAssignmentUrl() {
    return this.addassignmentForm.get('addAssignmentUrl');
  }

  //student assignment insert function

  createPost(Form) {
    this.service.subUrl = 'take_assignment/Takeassignment/createStudentAssignmentAnswer';
    let studentAssignment = Form.value; // Text Field/Form Data in Json Format
    let id = this.id;
    let tinymcevalue = this.tinymcearray;

    let assignData = {
      'id': id,
      'val': tinymcevalue,
      'stdAssign': studentAssignment
    }

    this.service.createPost(assignData).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'Assignment Submitted Successfully'
        this.toasterMsg(type, title, body);
        this.addassignmentForm.reset();
        this.ngOnInit();
        //submit assignment file upload function
        // let inputEl: HTMLInputElement = this.el.nativeElement.querySelector('#userdoc');
        let inputEl: HTMLInputElement = this.inputEl.nativeElement;
        //get the total amount of files attached to the file input.
        let fileCount: number = inputEl.files.length;
        // let fileList: FileList = event.target.files;
        if (fileCount > 0) {
          let file: File = fileCount[0];
          let formData: FormData = new FormData();
          formData.append('userdoc', inputEl.files.item(0));

          let headers = new Headers();
          // No need to include Content-Type in Angular 4 /
          headers.append('Content-Type', 'multipart/form-data');
          headers.append('Accept', 'application/json');

          this.params = jQuery.param(this.id);

          this.http.post(this.service.baseUrl + 'take_assignment/Takeassignment/upload?curr=' + this.curriculumValueFile + '&course=' + this.courseValueFile + '&' + this.params, formData)
            .subscribe(
            data => console.log('success'),
            error => console.log(error)
            )
        }
        this.ngOnInit();

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Assignment submitted failed please try again'
        this.toasterMsg(type, title, body);
        this.addassignmentForm.reset();
      }
      this.ngOnInit();
    });

  }

  //student assignment save function

  savePost(Form) {
    this.service.subUrl = 'take_assignment/Takeassignment/saveStudentAssignmentAnswer';
    let studentAssignment = Form.value; // Text Field/Form Data in Json Format
    let id = this.id;
    let tinymcevalue = this.tinymcearray;

    let assignData = {
      'id': id,
      'val': tinymcevalue,
      'stdAssign': studentAssignment
    }

    this.service.createPost(assignData).subscribe(response => {
      if (response.json().status == 'ok') {
        let type = 'success';
        let title = 'Add Success';
        let body = 'Assignment saved successfully'
        this.toasterMsg(type, title, body);
        this.addassignmentForm.reset();
      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'Assignment saved failed please try again'
        this.toasterMsg(type, title, body);
        this.addassignmentForm.reset();
      }
      this.ngOnInit();
    });

  }

  //get file name to the input field function

  getFileName(replaceElement: HTMLElement, splitElement: HTMLElement) {
    this.addassignmentForm.enable();
    //get filepath 
    var value = JSON.stringify($('#userdoc').val());
    //get filepath replace forward slash with backward slash
    var filePath = value.replace(/\\/g, "/");
    //remove backward slash and "
    var path = filePath.split('/').pop();
    path = path.replace('"', '');
    //get the fileName
    var fileName = JSON.stringify($('#addDocFiles').val(path));
    this.addAssignmentUpload.setValue(path);

  }

  //get file size

  getFileSize() {
    let inputEl: HTMLInputElement = this.inputEl.nativeElement;

    let fileCount: number = inputEl.files.length;

    if (fileCount > 0) {

      this.size = inputEl.files.item(0).size;
      if (this.size > 1000000) {
        let type = 'error';
        let title = 'File Upload Fail';
        let body = 'File size should be less than 1 MB'
        this.toasterMsg(type, title, body);
        this.addassignmentForm.disable();
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
