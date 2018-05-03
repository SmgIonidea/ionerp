import { Component, OnDestroy, AfterViewInit, EventEmitter, Input, Output } from '@angular/core';
import { ManageAssignmentComponent } from '../../instructor/manage-assignment/manage-assignment.component';
import { TakeAssignmentComponent } from '../../student/take-assignment/take-assignment.component';
import { TakeActivityComponent } from './../../student/take-activity/take-activity.component';
import { ComposemessageComponent } from './../../Message/composemessage/composemessage.component';
declare var tinymce: any;
tinymce.PluginManager.load('jbimages', '/ionerp/ionerpClient/dist/assets/tinymce/plugins/jbimages/plugin.js');
tinymce.PluginManager.load('image', '/ionerp/ionerpClient/dist/assets/tinymce/plugins/image/plugin.js');
tinymce.PluginManager.load('code', '/ionerp/ionerpClient/dist/assets/tinymce/plugins/code/plugin.js');

interface HTMLInputEvent extends Event {
  target: HTMLInputElement & EventTarget;
}

@Component({
  selector: 'simple-tiny',
  template: `<p id="{{elementId}}"></p>`
})
export class TinymceComponent implements AfterViewInit, OnDestroy {
  constructor(public tinymcevalue: ManageAssignmentComponent,
    public takeAssignTinymcevalue: TakeAssignmentComponent,
    public takeActivityTinymcevalue: TakeActivityComponent,
    public takeTinymcevalue:ComposemessageComponent) { }
  @Input() elementId: string;
  @Input() InitialText: string //Or any other special typing
  @Output() onEditorKeyup = new EventEmitter<any>();

  editor;


  ngAfterViewInit() {

    tinymce.init({
      selector: '#' + this.elementId,
      plugins: ['link', 'paste', 'table', 'image', 'code'],
      skin_url: '/ionerp/ionerpClient/dist/assets/skins/lightgray',
      toolbar: "insertfile undo redo |  bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image |code",
      branding: false,
      file_picker_types: 'image',
      // automatic_uploads: true,
      // image_advtab: true,
      file_picker_callback: function (cb, value, meta) {
        var input = document.createElement('input');

        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');


        input.onchange = function (e?: HTMLInputEvent) {

          var file = e.target.files[0];
          var reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = function () {

            var id = 'blobid' + (new Date()).getTime();
            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
            var base64 = reader.result.split(',')[1];
            var blobInfo = blobCache.create(id, file, base64);
            blobCache.add(blobInfo);

            // call the callback and populate the Title field with the file name
            cb(blobInfo.blobUri(), { title: file.name });
          };
        };

        input.click();
      },

      language: "en",
      relative_urls: false,
      forced_root_block: "",
      setup: editor => {

        this.editor = editor;

        editor.on('keyup', () => {
          const content = editor.getContent();
          this.tinymcevalue.textvalue = content;
          this.takeAssignTinymcevalue.takeAssignTextValue = content;
          this.takeActivityTinymcevalue.takeActivityTextValue = content;
          this.onEditorKeyup.emit(content);
          // this.takeassign.ngOnInit()
        });

      },
      init_instance_callback: (editor: any) => {
        editor && this.InitialText && this.editor.setContent(this.InitialText)
      },

    });
  }

  show(id) {

    tinymce.editors[id].show();


  }
  hide(id) {
    // alert(id)

    tinymce.editors[id].hide();


  }

  ngOnChanges() {
    if (this.editor) {
      if (this.InitialText && this.InitialText.length > 0) {
        this.editor.setContent(this.InitialText)
      } else {
        this.editor.setContent('');
      }
    }
  }

  ngOnDestroy() {
    tinymce.remove(this.editor);
  }
}