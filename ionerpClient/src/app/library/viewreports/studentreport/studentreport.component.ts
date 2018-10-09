import { CharctersOnlyValidation } from './../../../custom.validators';
import { PostService } from './../../../services/post.service';
import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ActivatedRoute, Router, NavigationEnd } from '@angular/router';


@Component({
  selector: 'app-studentreport',
  templateUrl: './studentreport.component.html',
  styleUrls: ['./studentreport.component.css']
})

export class StudentreportComponent implements OnInit {
  dtOptions: DataTables.Settings = {};
  dtTrigger = new Subject();
  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtInstance: DataTables.Api;
  usnList;
  MyData;
  studentname;
  className;
  bookData;
  VoucherList;
  ledgerList;
  fineAmount;
  waivedFine;
  sub;
  id
  error: boolean = false;
  bookDetails: Array<any> = [];
  regNo;
  constructor(private service: PostService,
    private activatedRoute: ActivatedRoute,
    private router: Router) { }

  private finePaymentForm = new FormGroup({
    fineAmt: new FormControl('', [CharctersOnlyValidation.DigitsOnly]),
    waivedAmt: new FormControl('', [CharctersOnlyValidation.DigitsOnly]),
    amtinwords: new FormControl('', [CharctersOnlyValidation.CharctersOnly]),
    payMode: new FormControl('', [Validators.required]),
    voucherType: new FormControl('', [Validators.required]),
    ledgerType: new FormControl('', [Validators.required]),
  });


  get fineAmt() {
    return this.finePaymentForm.get('fineAmt');
  }
  get waivedAmt() {
    return this.finePaymentForm.get('waivedAmt');
  }
  get payMode() {
    return this.finePaymentForm.get('payMode');
  }
  get voucherType() {
    return this.finePaymentForm.get('voucherType');
  }
  get ledgerType() {
    return this.finePaymentForm.get('ledgerType');
  }
  get amtinwords() {
    return this.finePaymentForm.get('amtinwords');
  }



  ngOnInit() {

    let userType = "Student"
    this.service.subUrl = "library/Reports/Reports/getId";
    this.service.createPost(userType).subscribe(response => {
      this.usnList = response.json();
    });

    this.service.subUrl = 'transport/MaintenanceDetails/getVoucherList'
    this.service.getData().subscribe(response => {
      this.VoucherList = response.json();
    });

    this.service.subUrl = 'transport/MaintenanceDetails/getLedgerList'
    this.service.getData().subscribe(response => {
      this.ledgerList = response.json();
    });

    this.sub = this.activatedRoute
      .queryParams
      .subscribe(params => {
        // Defaults to 0 if no query param provided.
        this.id = +params['id'] || 0;
        if (this.id > 0) {
          this.getBookData(this.id)
          this.getClassData(this.id)
        }
      });
  }


  getClassData(regId) {
    this.regNo = regId;
    this.studentname = "";
    this.className = "";
    this.service.subUrl = "library/Reports/Reports/getClassDetails";
    this.service.createPost(regId).subscribe(response => {
      this.studentname = response.json().pre_name;
      this.className = response.json().es_classname;
    });
  }


  getBookData(regId) {

    let postData = { 'type': 'Student', 'id': regId }
    this.service.subUrl = "library/Reports/Reports/getBookIssuedDetails";
    this.service.createPost(postData).subscribe(response => {
      this.bookData = response.json();
      this.tableRerender();
      this.dtTrigger.next(); // Calling the DT trigger to manually render the table  
    });
  }


  viewDetails(bookData) {
    this.fineAmount = '';
    this.waivedFine = '';
    this.bookDetails.length = 0;
    this.bookDetails.push(bookData);
  }

  createpost(formData) {
    this.bookDetails.forEach(element => {
      if (parseInt(this.fineAmount) + parseInt(this.waivedFine) == element.libbookfine) {
        this.error = false;
        let postData = { 'fineamount': this.fineAmount, 'waivedamount': this.waivedFine, 'formData': formData.value, 'bookDetails': this.bookDetails ,'userType':"Student" }
        this.service.subUrl = "library/Reports/Reports/updateLibFineData";
        this.service.createPost(postData).subscribe(response => {
          if (response.json() == true) {
            this.getBookData(this.regNo)
            this.finePaymentForm.reset();
          }
        });
      }
      else {
        this.error = true;
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

}
