import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { FormGroup, FormControl, Validators, ReactiveFormsModule } from '@angular/forms';
import { CharctersOnlyValidation } from '../../custom.validators';
import { PostService } from '../../services/post.service';
import { DataTableDirective } from 'angular-datatables';
import { Subject } from 'rxjs/Rx';
import { ToastService } from '../../common/toast.service';
import { ToasterConfig } from 'angular2-toaster';
declare let jsPDF;
@Component({
  selector: 'app-boardlist',
  templateUrl: './boardlist.component.html',
  styleUrls: ['./boardlist.component.css']
})
export class BoardlistComponent implements OnInit {

  constructor(private service: PostService,
    private toast: ToastService) { }

  @ViewChild(DataTableDirective)
  dtElement: DataTableDirective;
  dtOptions: DataTables.Settings = {};
  dtInstance: DataTables.Api;
  dtTrigger = new Subject();
  editBoard = [];
  route_list_id;
  maintitle;
  subtitle;
  isSaveHide: boolean;
  isUpdateHide: boolean;
  tosterconfig;
  RouteNameData;
  BoardData;
  boardId;
  routeInfo;
  routeData = [];
  temp = ''
  //form validation
  private boardList = new FormGroup({
    boardRouteNameList: new FormControl('', [
      Validators.required
    ]),
    boardTitle: new FormControl('', [
      Validators.required
    ]),
    boardCapacity: new FormControl('', [
      Validators.required,
      CharctersOnlyValidation.DigitsOnly
    ])
  })

  get boardRouteNameList() {
    return this.boardList.get('boardRouteNameList');
  }

  get boardTitle() {
    return this.boardList.get('boardTitle');
  }

  get boardCapacity() {
    return this.boardList.get('boardCapacity');
  }



  ngOnInit() {

    this.maintitle = "Board List";
    this.subtitle = "Add Board List";
    this.isSaveHide = false;
    this.isUpdateHide = true;

    this.service.subUrl = 'transport/Board_list/getRouteName';
    this.service.getData().subscribe(response => {
      this.RouteNameData = response.json();
      // alert(JSON.stringify(this.RouteNameData));
    })

    this.service.subUrl = 'transport/Board_list/getBoardList';
    this.service.getData().subscribe(response => {
      this.BoardData = response.json();
      this.tableRerender();
      this.dtTrigger.next();
    })
  }


  //function to save board details
  saveBoardDetails(BoardListForm) {

    this.service.subUrl = 'transport/Board_list/saveBoardDetail';
    let postdata = {
      'route_id': BoardListForm.value.boardRouteNameList,
      'board_title': BoardListForm.value.boardTitle,
      'board_capacity': BoardListForm.value.boardCapacity
    }

    this.service.createPost(postdata).subscribe(response => {
      if (response.json().status == 'ok') {
        this.service.subUrl = 'transport/Board_list/getBoardList';
        this.service.getData().subscribe(response => {
          this.BoardData = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })
        let type = 'success';
        let title = 'Add Success';
        let body = 'New board list added'
        this.toasterMsg(type, title, body);
        this.boardList.reset();
        this.cancelUpdate();

      } else {
        let type = 'error';
        let title = 'Add Fail';
        let body = 'New board list add failed please try again'
        this.toasterMsg(type, title, body);
        this.boardList.reset();
      }

    });
  }

  // function to get board data for edit
  editBoardList(editElement: HTMLElement) {
    let id = editElement.getAttribute('boardId');
    this.service.subUrl = 'transport/Board_list/getEditBoard';
    this.service.createPost(id).subscribe(response => {
      this.editBoard = response.json();
      this.editBoard.forEach(element => {
        this.route_list_id = element.route_id;
      })

      this.subtitle = "Edit Board List";
      let route_title = editElement.getAttribute('routeTitle');
      let board_title = editElement.getAttribute('boardTitle');
      let capacity = editElement.getAttribute('capacity');

      this.boardRouteNameList.setValue(this.route_list_id);
      this.boardTitle.setValue(board_title);
      this.boardCapacity.setValue(capacity);

      this.boardId = id;
      this.isSaveHide = true;
      this.isUpdateHide = false;
    });

  }


  // update board data
  updatePost(updatePost) {
    this.service.subUrl = 'transport/Board_list/updateBoard';
    updatePost.stringify

    let postData = {
      'route_title': updatePost.value.boardRouteNameList,
      'board_title': updatePost.value.boardTitle,
      'capacity': updatePost.value.boardCapacity,
      'boardId': this.boardId,

    };

    this.service.updatePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'transport/Board_list/getBoardList';
        this.service.getData().subscribe(response => {
          this.BoardData = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })
        let type = 'success';
        let title = 'Update Success';
        let body = 'Board data updated successfully'
        this.toasterMsg(type, title, body);
        this.cancelUpdate();
      } else {
        let type = 'error';
        let title = 'Update Fail';
        let body = 'Nothing to Update'
        this.toasterMsg(type, title, body);


      }
    });
    $('html,body').animate({ scrollTop: 0 }, 'slow');
  }

  //delete modal 
  deleteWarning(deleteElement: HTMLElement, modalEle: HTMLDivElement) {

    this.boardId = deleteElement.getAttribute('boardId');

    this.service.subUrl = 'transport/Board_list/getBoardDeleteData';
    this.service.createPost(this.boardId).subscribe(response => {
      if (response.json().status == 'ok') {

        (<any>jQuery('#boardDeleteModal')).modal('show');
      }

    });

  }

  // delete board
  deleteBoardData(deleteBoard) {
    this.service.subUrl = 'transport/Board_list/deleteBoardData';

    let postData = {
      'boardId': deleteBoard

    };

    this.service.deletePost(postData).subscribe(response => {

      if (response.json().status == 'ok') {
        this.service.subUrl = 'transport/Board_list/getBoardList';
        this.service.getData().subscribe(response => {
          this.BoardData = response.json();
          this.tableRerender();
          this.dtTrigger.next();
        })
        let type = 'success';
        let title = 'Delete Success';
        let body = 'Board data deleted successfully'
        this.toasterMsg(type, title, body);
        (<any>jQuery('#boardDeleteModal')).modal('hide');
        this.cancelUpdate();

      } else {
        let type = 'error';
        let title = 'Delete Fail';
        let body = 'Board data delete failed please try again'
        this.toasterMsg(type, title, body);
        this.cancelUpdate();

      }

    });

  }

  cancelUpdate() {
    this.maintitle = "Board List";
    this.subtitle = "Add Board List";
    this.boardList.reset();
    this.isSaveHide = false;
    this.isUpdateHide = true;
  }

  //print the table data in pdf format
  convert() {
    var doc = new jsPDF();
    var col = ["Route", "Route Title", "Board Title", "Capacity"];
    var rows = [];
    this.service.subUrl = 'transport/Board_list/getBoardList';
    this.service.getData().subscribe(response => {
      this.tableRerender();
      this.dtTrigger.next();
    })
    this.BoardData.forEach(element => {
      let route = element.route;
      let route_title = element.route_title;
      let board_title = element.board_title;
      let capacity = element.capacity;
      var temp = [route, route_title, board_title, capacity];
      rows.push(temp);
    })
    doc.autoTable(col, rows);
    doc.save('BoardList.pdf');
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
