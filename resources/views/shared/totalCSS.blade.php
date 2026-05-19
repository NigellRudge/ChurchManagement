@include('shared.datatable_css')
@include('shared.selectCss')
@include('shared.daterange-pickerCSS')
@include('shared.sweet-alert-css')
@include('shared.toastrCSS')
@include('shared.fullcalendar_css')
<style rel="stylesheet" href="{{ asset('vendor/boostrap-multiselect/multiselect.min.css') }}"></style>
<style>

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .today {
        background: var(--teal)!important;
        color:white;
    }

    table > thead > tr > th {
        font-weight: bold!important;
    }
    .nav.nav-tabs > .nav-item > .nav-link {
        border-bottom-color: #0f6848 !important;
        color: var(--secondary)!important;
        font-weight: 400!important;
    }
    .nav.nav-tabs > .nav-item > .nav-link.active {
        border-color: #0f6848 !important;
        background: var(--teal) !important;
        color: white!important;
        font-weight: bold!important;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .nav.nav-tabs {
        border-bottom-color: var(--teal) !important;
    }
    label.is-invalid {
        padding-top:5px;
        font-size: 0.95rem;
        color: #ff4141
    }

    input[type=file]::file-selector-button {
        border: 1px solid #0f6848;
        padding: .2em .4em;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        border-bottom: none;
        color: #0f6848;
        font-weight: bold;
        font-size: 0.9rem;
        background-color: white;
        transition: 100ms;
        cursor: pointer;
    }

    input[type=file]::file-selector-button:hover {
        background: var(--teal);
        border: 1px solid #0f6848;
        color: white;
    }
</style>
