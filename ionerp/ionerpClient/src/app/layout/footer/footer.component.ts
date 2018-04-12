import { Component, OnInit } from '@angular/core';
import * as $ from 'jquery';
// import { CookieService } from 'angular2-cookie/core';
@Component({
  selector: 'erp-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css'],
  providers: [
    // CookieService
  ]
})
export class FooterComponent  {
  language: string = localStorage.getItem('locale_lang');
  constructor(
    // private _cookieService: CookieService
  ) { }
  // change:string = 'click';
  value: string;
  program: string;

  ngOnInit() {
    var language = this.language;
    var program = this.program;
    // var value = this.value;
    if (language != null) {

      $('#lg_dropdown').unbind("change");
      $('#lg_dropdown option[value="' + language + '"]').prop('selected', true);
      $('#lg_dropdown').bind("change", null, null);
    }
    $("#lg_dropdown").change(function (e) {
      // jQuery.type( "value" ) === "string";
      var value = $(this).val();
      // alert(value);
      // localStorage.setItem("locale_lang", value );
      // $("html").attr("lang", value);
      //console.log(language);
      var url = "http://localhost/ION_DELIVERY/iondelivery_v1.0/iondeliveryServer/locale/" + 'locale-' + value + ".json";
      $.getJSON(url, function (data) {
        $("*[data-key]").each(function () {
          if ($(this).is("input")) {
            //alert('is input');
            $(this).attr("placeholder", data[$(this).data('key')]);
          }
          else if ($(this).is("textarea")) { $(this).attr("placeholder", data[$(this).data('key')]); }
          else {
            // program = data('key');
            $(this).text(data[$(this).data('key')]);
          }
        });
        e.preventDefault();
      });
    });
  }
}
