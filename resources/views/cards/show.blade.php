@php
    /** @var $card \App\Models\UserCard */
    /** @var $transactions \App\Models\UserTransaction[]|\Illuminate\Database\Eloquent\Collection */
@endphp

@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {!! Form::open(['route' => ['cards.block', 'id' => $card->id], 'method' => 'patch']) !!}
                    <a href="{{ route('cards.index') }}" class="btn"><- Назад</a>
                    @if($card->blocked())
                        <button class="btn-sm btn-success btn-flat">Разблокировать</button>
                    @else
                        <button class="btn-sm btn-warning btn-flat">Заблокировать</button>
                    @endif
                    {!! Form::close() !!}

                    <div>
                        <strong>{{ $card->card_number }}</strong>
                    </div>
                </div>

                <div class="card-body">
                    <div><strong>Expires at</strong> {{ $card->expiration_date }}</div>
                    <div><strong>CVV</strong>: {{ $card->cvv }}</div>
                    <div><strong>Pin</strong>: {{ $card->pin }}</div>
                    <div><strong>Balance</strong>: {{ $card->balance }}$</div>

                    @if($card->blocked() === false)
                    <div>
                        <h2>Пополнить счет</h2>
                        {!! Form::open(['route' => ['cards.replenish', 'id' => $card->id], 'method' => 'patch']) !!}
                        <div class="form-group">
                            {!! Form::label('pin', 'Pin') !!}
                            {!! Form::input('number', 'pin', null, ['max' => 9999, 'class' => 'form-control']) !!}
                            @error('pin')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {!! Form::label('amount', 'Amount') !!}
                            {!! Form::input('number', 'amount', 100, ['class' => 'form-control']) !!}
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button class="btn-sm btn-flat">Пополнить</button>
                        {!! Form::close() !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                @include('transactions.particles.list')
                <a href="{{ route('transactions.index', ['card' => $card->id]) }}" class="btn btn-sm">Все транзакции</a>
            </div>
        </div>
    </div>
@endsection
