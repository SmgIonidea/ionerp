import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'titleCase'
})
export class TitleCasePipe implements PipeTransform {

  transform(value:string): any {
    if(!value) return null;
    
   let prepositions = [
        'of',
        'the'
      ];
       let word = [];
        word = value.split(' ');
    for(var i=0; i<word.length;i++){
      if(prepositions.includes(word[i]))
        word[i] = word[i].toLowerCase;
      else{
        word[i] = word[i].substr(0,1).toUpperCase +''+ word[i].substr(1).toLowerCase;
      }
    }

    return word;
  }

}
