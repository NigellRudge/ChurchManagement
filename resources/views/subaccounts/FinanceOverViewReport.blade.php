<div>{{config('constants.CHURCH_NAME')}}</div><br/>
<div>{{$period}}</div>
<br>
<div>
    {{trans('common.income')}}
</div>
<br/>
<table>
    <thead>
    <tr >
        <th style="font-weight: bold">{{trans('common.account')}}</th>
        <th style="font-weight: bold">{{trans('common.currency_label')}}</th>
        <th style="font-weight: bold">{{trans('common.amount_label')}}</th>
        <th style="font-weight: bold">{{trans('common.amount_base_currency')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($income_accounts as $account)
        <tr>
            <td>{{ $account->name }}</td>
            <td>{{ $account->currency }}</td>
            <td>{{ $account->balance/100 }}</td>
            <td>{{ $account->balance_srd/100 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<br/>
<div>
    {{trans('common.expenses')}}
</div>
<br/>
<table>
    <thead>
    <tr>
        <th style="font-weight: bold">{{trans('common.account')}}</th>
        <th style="font-weight: bold">{{trans('common.currency_label')}}</th>
        <th style="font-weight: bold">{{trans('common.amount_label')}}</th>
        <th style="font-weight: bold">{{trans('common.amount_base_currency')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($expense_accounts as $account)
        <tr>
            <td>{{ $account->name }}</td>
            <td>{{ $account->currency }}</td>
            <td>{{ $account->balance/100 }}</td>
            <td>{{ $account->balance_srd/100 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
