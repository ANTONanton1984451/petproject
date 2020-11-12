export const formDate = {
    filters : {
        formDate(timeStampDate){
            let moment = require('moment');
            return moment.unix(timeStampDate).format("MMM Do YY");
        },
        formNotTimestampDate(Date){
            let moment = require('moment');
            return moment(Date).format("MMM Do YY");
        }
    }
}
