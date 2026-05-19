@include('shared.datatable_js')
@include('shared.sweet-alert-js')
@include('shared.validator')
@include('shared.select2-js')
@include('shared.date-ranger-picker-js')
@include('shared.toastrJS')
@include('shared.fullcalendar_js')
<script src="{!! asset('vendor/currency/currency.js') !!}"></script>
<script src="{!! asset('vendor/multiselect/jquery.multi-select.js') !!}"></script>
<script>

    function onReloadComplete(json, buttonId='exportBtn'){
        let length = json.data.length
        if(length !== 0){
            $(`#${buttonId}`).prop('disabled',false)
        }
        if(length === 0 ){
            $(`#${buttonId}`).prop('disabled',true)
        }
    }

    function getStartOfWeek(){
        let curr = new Date;
        let temp = curr.getDate() - curr.getDay();
        return new Date(curr.setDate(temp))
    }

    function getEndOfWeek(){
        let curr = new Date;
        let temp = (curr.getDate() - curr.getDay()) + 6;
        return new Date(curr.setDate(temp))
    }

    function clearInput(inputId){
        let input = $(`#${inputId}`)
        input.val(null)
        input.removeClass('is-valid');
        input.removeClass('is-invalid');
    }

    function clearForm(formId, disableSubmitButton=false) {
        const elements = $(`#${formId} input, #${formId} select, #${formId} textarea`)
        if (disableSubmitButton) {
            $(`#${formId} button:submit`).prop('disabled', true)
        }
        for (let item of elements) {
            if (!item.disabled) {
                switch (item.tagName) {
                    case "SELECT":
                        item.value = "";
                        break
                    case "INPUT":
                        if (item.type !== 'hidden') {
                            item.value = null
                        }
                        break;
                    default:
                        item.value = null;
                        break;
                }
            }
            item.classList.remove('is-valid')
            item.classList.remove('is-invalid')
            let errorLabel = $(`#${item.id}-error`)
            errorLabel.remove()
        }
        $('.customError').html('')
    }

    function formatDate(date,  subtractYear=0,separator='-', reverse=false) {
        let day = String(date.getDate());
        if (day.length === 1)
            day = '0' + day
        let month = String((date.getMonth() + 1));
        if (month.length === 1)
            month = '0' + month
        return reverse ? `${month}${separator}${day}${separator}${date.getFullYear() - subtractYear}` : `${date.getFullYear() - subtractYear}${separator}${month}${separator}${day}`;
    }

    function checkIfZero(input){
        if(input === undefined || input === '' || isNaN(input)){
            return 0.00;
        }
        return input
    }

</script>
