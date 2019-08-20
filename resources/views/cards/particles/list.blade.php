@php
/** @var $user \App\Models\User */
/** @var $cards \App\Models\UserCard[]|\Illuminate\Database\Eloquent\Collection */
@endphp

<table class="table table-striped">
    <thead>
    <tr>
        <td>Card number</td>
        <td>Expires at</td>
        <td>Balance</td>
        <td></td>
    </tr>
    </thead>
    <tbody>
    @forelse($cards as $card)
        <tr class="{{ $card->blocked() ? 'text-danger' : '' }}">
            <td>
                {{ $card->hidden_card_number }}
                @if($card->blocked())
                    <span class="text-secondary">(Blocked)</span>
                @endif
            </td>
            <td>{{ $card->expiration_date }}</td>
            <td>{{ $card->balance }}$</td>
            <td>
                <a href="{{ route('cards.show', ['id' => $card->id]) }}">
                    View
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" align="center">
                You do not have any cards.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
