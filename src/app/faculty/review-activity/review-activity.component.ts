/**
    ----------------------------------------------
 * @package     :IonDelivery
 * @Component   :ReviewActivityComponent
 * @Description :To download,upload & accept student's activity marks
 * @author      :Shashidhar Naik
 * @Created     :19-01-2018
 * @Modification History
 *  Date            Description	               Modified By
    ----------------------------------------------
 */

import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { PostService } from '../../services/post.service';
import { ActivatedRoute, Params } from "@angular/router";
import { RequestOptions, Http } from '@angular/http';
import { ToastService } from "../../common/toast.service";
import { ToasterConfig, ToasterService } from 'angular2-toaster';

@Component({
  selector: 'app-review-activity',
  templateUrl: './review-activity.component.html',
  styleUrls: ['./review-activity.component.css']
})
export class ReviewActivityComponent implements OnInit {
  /* Global variables declaration */
  sub;
  id;
  tosterconfig;
  activityName;
  initialDate;
  endDate;
  studGetActDetails;
  marksPerQsn;
  csvData;
  tempTableData;
  realTableData = [];
  @ViewChild('fileInput') inputEl: ElementRef; //file upload

  constructor(
    private route: ActivatedRoute,
    private service: PostService,
    private http: Http,
    private toast: ToastService
  ) { }

  programValue = localStorage.getItem('programDropdown');
  curriculumValue = localStorage.getItem('currDropdown');
  termValue = localStorage.getItem('termDropdown');
  courseValue = localStorage.getItem('courseDropdown');
  sectionValue = localStorage.getItem('sectionDropdown');

  ngOnInit() {
    $('#loader').hide();
    this.sub = this.route
      .queryParams
      .subscribe(params => {
        this.id = +params['id'] || 0;
        // To get Activity name, dates
        this.service.subUrl = 'activity/faculty/ReviewActivity/getActivityDetails';
        this.service.createPost(this.id).subscribe(response => {
          this.activityName = response.json()[0]['ao_method_name'];
          this.initialDate = response.json()[0]['initiate_date'];
          this.endDate = response.json()[0]['end_date'];
        });

        // To get Students number who viewed or submitted the activity
        this.service.subUrl = 'activity/faculty/ReviewActivity/getStudGetActDetails';
        this.service.createPost(this.id).subscribe(response => {
          this.studGetActDetails = response.json();
        });

        this.service.subUrl = 'activity/faculty/ReviewActivity/getMarksPerQsn';
        this.service.createPost(this.id).subscribe(response => {
          this.marksPerQsn = response.json();
          this.csvData = this.marksPerQsn.csv_details;
        });

        this.service.subUrl = 'activity/faculty/ReviewActivity/getStudAssData';
          this.service.createPost(this.id).subscribe(response => {
            for (let i = 0; i < response.json().length; i++) {
              this.realTableData[i] = [];
              for (let key in response.json()[i]) {
                if (response.json()[i].hasOwnProperty(key)) {
                  this.realTableData[i].push(response.json()[i][key]);
                }
              }
            }
          })
      })
  }

  /**
   * Function to download CSV File
   * Params:
   * Return:
   */
  downloadCSV() {
    var csvData = this.ConvertToCSV(this.csvData);
    var a = document.createElement("a");
    a.setAttribute('style', 'display:none;');
    document.body.appendChild(a);
    var blob = new Blob([csvData], { type: 'text/csv' });
    var url = window.URL.createObjectURL(blob);
    a.href = url;
    a.download = localStorage.getItem('currDropdown') + '_' + localStorage.getItem('termDropdown') + '_' + this.activityName + '.csv';
    a.click();
  }

  /**
   * Function to convert object to CSV data
   * Params: csv data (object)
   * Return: string (CSV format)
   */
  ConvertToCSV(objArray) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
    var str = '';
    var row = "";

    for (var index in objArray[0]) {
      //Now convert each value to string and comma-separated
      row += index + ',';
    }
    row = row.slice(0, -1);
    //append Label row with line break
    str += row + '\r\n';

    for (var i = 0; i < array.length; i++) {
      var line = '';
      for (var index in array[i]) {
        if (line != '') line += ','

        line += array[i][index];
      }
      str += line + '\r\n';
    }
    return str;
  }

  /**
   * Function to read CSV file & convert it as array
   * Params:
   * Return:
   */
  uploadCSV(event): void {
    let inputEl: HTMLInputElement = this.inputEl.nativeElement;
    let fileCount: number = inputEl.files.length;

    if (fileCount > 0) {
      let formData: FormData = new FormData();
      formData.append('csvfile', inputEl.files.item(0));

      let headers = new Headers();
      headers.append('Content-Type', 'multipart/form-data');
      headers.append('Accept', 'application/json');

      this.sub = this.route
        .queryParams
        .subscribe(params => {
          this.id = +params['id'] || 0;
          let fileName = localStorage.getItem('currDropdown') + '_' + localStorage.getItem('termDropdown') + '_' + this.activityName + '.csv';
          this.http.post(this.service.baseUrl + 'activity/faculty/ReviewActivity/createTempTable/' + this.id + '/' + fileName, formData).subscribe(
            response => {
              if (response.json().error) {
                (<any>jQuery('#modalContent')).html(response.json().message);
                (<any>jQuery('#UploadError')).modal('show');
              } else {
                this.tempTableData = response.json().tableData;
              }
            })
        });
    }
  }

  /**
   * Function to accept CSV file & insert assessment data
   * Params:
   * Return:
   */
  acceptCSV() {
    $('#loader').show();
    if (this.tempTableData) {
      this.sub = this.route
        .queryParams
        .subscribe(params => {
          this.id = +params['id'] || 0;
          this.service.subUrl = 'activity/faculty/ReviewActivity/acceptTempData';
          this.service.createPost(this.id).subscribe(response => {
            if (response.json().error) {
              $('#loader').hide();
              (<any>jQuery('#modalContent')).html(response.json().message);
              (<any>jQuery('#UploadError')).modal('show');
            } else {
              for (let i = 0; i < response.json().marks_list.length; i++) {
                this.realTableData[i] = [];
                for (let key in response.json().marks_list[i]) {
                  if (response.json().marks_list[i].hasOwnProperty(key)) {
                    this.realTableData[i].push(response.json().marks_list[i][key]);
                  }
                }
              }
              this.tempTableData = [];
              let type = 'success';
              let title = 'Import Status';
              let body = 'Student assessment data has been successfully uploaded.';
              $('#loader').hide();
              this.toasterMsg(type, title, body);
            }
          })
        });
    } else {
      $('#loader').hide();
      (<any>jQuery('#modalContent')).html('Upload file before accepting');
      (<any>jQuery('#UploadError')).modal('show');
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

}
