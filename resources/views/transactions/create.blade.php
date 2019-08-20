@php
/** @var $cardsDictionary array */
/** @var $user \App\Models\User */
@endphp

@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('transactions.index') }}" class="btn"><- Назад</a>
                    <div>Перевод с карты на карту</div>
                </div>

                <div class="card-body">
                    {!! Form::open(['route' => ['transactions.store'], 'method' => 'post', 'onsubmit' => 'return confirm("Вы действительно хотите перевести деньги?");']) !!}
                    <div class="form-group">
                        {!! Form::select('card_from', $cardsDictionary, null, ['class' => 'form-control', 'placeholder' => 'Выберите карту с которой хотите перевести денег']) !!}
                        @error('card_from')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::input('number', 'pin', null, ['max' => 9999, 'placeholder' => 'Pin', 'class' => 'form-control']) !!}
                        @error('pin')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::select('card_to', $cardsDictionary + [0 => 'Ввести номер вручную'], null, ['class' => 'form-control', 'placeholder' => 'Выберите карту для пополнения', 'onchange' => 'showBlockOrNot(this);']) !!}
                        @error('card_to')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="{{ empty(old()) ? 'display:none;' : '' }}" id="custom-number">
                        {!! Form::input('number', 'custom_number', null, ['maxlength' => 16, 'class' => 'form-control', 'placeholder' => 'Укажите номер карты для перевода']) !!}
                        @error('custom_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::input('number', 'amount', 100, ['class' => 'form-control', 'placeholder' => 'Amount']) !!}
                        @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn-sm btn-flat">Пополнить</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
