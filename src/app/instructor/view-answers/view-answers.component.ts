import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ActivatedRoute, Params } from "@angular/router";
import { Title } from '@angular/platform-browser';
import { PostService } from '../../services/post.service';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { CharctersOnlyValidation } from '../../custom.validators';

@Component({
  selector: 'app-view-answers',
  templateUrl: './view-answers.component.html',
  styleUrls: ['./view-answers.component.css']
})

export class ViewAnswersComponent implements OnInit {

  constructor(private route: ActivatedRoute,
              public titleService: Title,
              private service: PostService,
              private toast: ToastService) { }

  sub;
  id;
  aid;
  curriculumName;
  termName;
  courseName;
  sectionName;
  stu_list: Array<any>;
  questionList: Array<any>;
  statusList: Array<any>;
  questionFlagList: Array<any>;
  assign_id;
  secName;
  studentName;
  tosterconfig;
  inserData: Array<any>;
  marks: Array<any> = [];
  question;
  mark;
  getStatus;
  headId;
  questflag;
  secmarks;
  totalamount;
  sum;

  public reviewForm = new FormGroup({
    secured_marks: new FormControl('', [Validators.required, CharctersOnlyValidation.DigitsOnly]),
    remarks: new FormControl('', [
      // Validators.required
    ])
  });

  ngOnInit() {

    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.headId = +params['aid'] || 0;
      })

    this.titleService.setTitle('View Assignment | IONCUDOS');
    this.curriculumName = localStorage.getItem('currDropdown');
    this.termName = localStorage.getItem('termDropdown');
    this.courseName = localStorage.getItem('courseDropdown');
    this.sectionName = localStorage.getItem('sectionDropdown');

    var programValue = localStorage.getItem('programDropdownId');
    var curriculumValue = localStorage.getItem('currDropdownId');
    var termValue = localStorage.getItem('termDropdownId');
    var courseValue = localStorage.getItem('courseDropdownId');
    var sectionValue = localStorage.getItem('sectionDropdownId');
    let postData = {
      'pgmDrop': programValue,
      'curclmDrop': curriculumValue,
      'termDrop': termValue,
      'courseDrop': courseValue,
      'secDrop': sectionValue,
    };

//service to student details
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post = {
          'stu_id': this.id,
          'assign_id': this.aid,
        };      
        this.service.subUrl = 'assignment/ReviewAssignment/getViewAnswerId';
        this.service.createPost(post).subscribe(response => {
          this.studentName = response.json();
        });
      });

//service to get question flags
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post_data = {
          'stu_id': this.id,
          'assign_id': this.aid,
        };
        this.service.subUrl = 'assignment/ReviewAssignment/getQuestionFlag';
        this.service.createPost(post_data).subscribe(response => {
          this.questionFlagList = response.json();
        });
      });

 //service to display  question 
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post_data = {
          'stu_id': this.id,
          'assign_id': this.aid,
        };
        this.service.subUrl = 'assignment/ReviewAssignment/getQuestion';
        this.service.createPost(post_data).subscribe(response => {
          this.questionList = response.json();
        });
      });

    //service to disable rework and accept buttons when no question is added
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post_data = {
          'stu_id': this.id,
          'assign_id': this.aid,
        };
        this.service.subUrl = 'assignment/ReviewAssignment/getCorrectionStatus';
        this.service.createPost(post_data).subscribe(response => {
          this.statusList = response.json();
          this.statusList.forEach(status => {
            if (status.status_flag == '1') {
              $("button[name=rework]").prop("disabled", true);
              $("button[name=accept]").prop("disabled", true);
            }
          });
        });
      });

//service to get status and disable marks and remarks fields
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post = {
          'stu_id': this.id,
          'assign_id': this.aid,
        };
        this.service.subUrl = 'assignment/ReviewAssignment/getStatus';
        this.service.createPost(post).subscribe(response => {
          this.getStatus = response.json();
          this.getStatus.forEach(status => {
            if (status.status_flag == '4') {
              $("button[name=rework]").attr("disabled", "disabled");
              $("button[name=accept]").attr("disabled", "disabled");
              this.secmarks = status.aq_secured_marks;
              this.sum = 0;
              this.getStatus.forEach(model => {
                this.sum += parseInt(model.aq_secured_marks);
              });
              $("#remarks").val(status.review_remark).attr("disabled", "disabled");
              console.log(status.stud_tak_asgt_id)
            }
          });
        });
      });
  }


  get secured_marks() {
    return this.secured_marks.get('secured_marks');
  }
  get remarks() {
    return this.remarks.get('remarks');
  }

  //function to update status to Rework
  UpdateReworkStatus() {
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post_data = {
          'stu_id': this.id,
          'assign_id': this.aid,
        };

        this.service.subUrl = 'assignment/ReviewAssignment/updateReworkStatus';
        this.service.createPost(post_data).subscribe(response => {          
          if (response.json().status == 'ok') {

            let type = 'success';
            let title = 'Update Success';
            let body = 'Assignment sent for rework'
            this.toasterMsg(type, title, body);
            this.reviewForm.reset();
            this.ngOnInit();
          } else {
            let type = 'error';
            let title = 'Update Fail';
            let body = 'Couldnot send assignment for rework please try again'
            this.toasterMsg(type, title, body);
            this.reviewForm.reset();
            this.ngOnInit();
          }
        })
      })
  }

  //function to save asignment marks
  SaveReviewAssignment(reviewForm) {
    let reviewAssignmentData = reviewForm.value;
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        this.aid = +params['aid'] || 0;
        let post_data = {
          'stu_id': this.id,
          'assign_id': this.aid,         
        };
        this.marks.length = 0;
        this.service.subUrl = 'assignment/ReviewAssignment/getQuestionId';
        this.service.createPost(post_data).subscribe(response => {
          this.question = response.json();

          this.question.forEach(show => {
            this.mark = $('#marks' + show.stud_tak_asgt_id).val();
            this.questflag = show.que_flag;
            this.marks.push({ 'id': show.stud_tak_asgt_id, 'ans_marks': this.mark })
          });
          let data = {
            'stu_id': this.id,
            'assign_id': this.aid,
            'ans_marks': this.marks,
            'assignData': reviewAssignmentData,
            'quest_id': this.questflag
          };
          this.service.subUrl = 'assignment/ReviewAssignment/saveAssignMarks';
          this.service.createPost(data).subscribe(response => {
            this.inserData = response.json();
            if (response.json().status == 'ok') {
              let type = 'success';
              let title = 'Update Success';
              let body = 'Assignment accepted'
              this.toasterMsg(type, title, body);
              this.ngOnInit();
            } else {
              let type = 'error';
              let title = 'Update Fail';
              let body = 'Marks update failed'
              this.toasterMsg(type, title, body);
              this.ngOnInit();
            }
          });
        })
      });
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
