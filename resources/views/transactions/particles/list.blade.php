@php
/** @var $user \App\Models\User */
/** @var $cards \App\Models\UserCard[]|\Illuminate\Database\Eloquent\Collection */
/** @var $transactions \App\Models\UserTransaction[]|\Illuminate\Database\Eloquent\Collection */
@endphp

<table class="table table-striped">
    <thead>
    <tr>
        @if(Route::currentRouteName() !== 'cards.show')
            <td>Card number</td>
        @endif
        <td>Created at</td>
        <td>Balance</td>
        <td>Description</td>
    </tr>
    </thead>
    <tbody>
    @forelse($transactions as $transaction)
        <tr>
            @if(Route::currentRouteName() !== 'cards.show')
                <td>{{ $transaction->card->hidden_card_number }}</td>
            @endif
            <td>{{ $transaction->creation_date }}</td>
            <td>{{ $transaction->amount }}$</td>
            <td>{{ $transaction->message }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4" align="center">
                You do not have any transactions.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
