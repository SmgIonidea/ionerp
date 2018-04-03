import {AbstractControl,ValidationErrors} from '@angular/forms';
export class AppValidators{
    static shouldBeUnique(control:AbstractControl):ValidationErrors|null{
        if((control.value as string) == 'Mritunjay'){
            return {
                shouldBeUnique:true
            }
        }else{
            return null;
        }
    
    }
}