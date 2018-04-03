import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'charLimiter'
})
export class CharLimiterPipe implements PipeTransform {
    transform(value: string, args: number) {
        let limit = args;

        return value.length > limit ? value.substring(0, limit) + '...' : value;
    }
}