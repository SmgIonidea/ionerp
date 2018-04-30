import { Http, Response } from '@angular/http';
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';

@Injectable()
export class PostService {
  public baseUrlFile = 'http://10.91.5.13/ionerp/ionerpServer/uploads/'
  public baseUrl = 'http://10.91.5.13/ionerp/ionerpServer/index.php/';
  public subUrl;
  constructor(private http: Http) { }
  //Http Request to get the data
  getData() {
    return this.http.get(this.baseUrl + this.subUrl);
  }
  //Http request to Add data
  createPost(post) {
    return this.http.post(this.baseUrl + this.subUrl, JSON.stringify(post));

  }
  // Http request to update the Data
  updatePost(postData) {
    // alert(JSON.stringify(postData));
    return this.http.put(this.baseUrl + this.subUrl, JSON.stringify(postData));
  }

  /* Http Request to delete Data */
  deletePost(postData) {
    return this.http.post(this.baseUrl + this.subUrl, JSON.stringify(postData));
  }
}
