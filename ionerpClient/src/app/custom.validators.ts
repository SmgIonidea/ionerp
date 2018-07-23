import { AbstractControl, ValidationErrors } from '@angular/forms';
export class CharctersOnlyValidation {
    static CharctersOnly(control: AbstractControl): ValidationErrors | null {
        if (control.value) {
            if ((control.value as string).match('[a-zA-Z][a-zA-Z ]+[a-zA-Z]$')) {
                return null;

            } else {
                return { CharctersOnly: true };
            }
        } else {
            return null;
        }


    }
    static DigitsOnly(control: AbstractControl): ValidationErrors | null {
        if (control.value) {
            if ((control.value as string).match('^[0-9]*$')) {
                return null;

            } else {
                return { DigitsOnly: true };
            }
        } else {
            return null;
        }


    }
    static DigitsOnlyMobileNumber(control: AbstractControl): ValidationErrors | null {

        if (control.value) {
            // if((control.value as string).match('^[6-9]\d{9}$')){ 
            if ((control.value as string).match('^([+][9][1]|[9][1]|[0]){0,1}([6-9]{1})([0-9]{9})$')) {
                return null;

            } else {
                return { DigitsOnlyMobileNumber: true };
            }
        } else {
            return null;
        }
    }

    static DigitsOnlyStart(control: AbstractControl): ValidationErrors | null {
        if (control.value) {
            if ((control.value as string).match('^[1-9][0-9]*$')) {
                //  if((control.value as string).match('0*[1-9][0-9]*$')){
                return null;

            } else {
                return { DigitsOnlyStart: true };
            }
        } else {
            return null;
        }


    }

    static DigitswithDecimals(control: AbstractControl): ValidationErrors | null {
        if (control.value) {
            if ((control.value as string).match('^[0-9]+(\.[0-9]{1,2})?$')) {

                return null;

            } else {
                return { DigitswithDecimals: true };
            }
        } else {
            return null;
        }


    }

    static nospaceValidator(control: AbstractControl): { [s: string]: boolean } {
        let isWhitespace = (control.value || '').trim().length === 0;
        let isValid = !isWhitespace;
        return isValid ? null : { 'whitespace': true }
    }

}