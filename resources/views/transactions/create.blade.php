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
                    <a href="{{ route('transactions.index') }}" class="btn"><- Back</a>
                    <div>Transfer from card to card</div>
                </div>

                <div class="card-body">
                    {!! Form::open(['route' => ['transactions.store'], 'method' => 'post', 'onsubmit' => 'return confirm("Do you really want to transfer money?");']) !!}
                    <div class="form-group">
                        {!! Form::select('card_from', $cardsDictionary, null, ['class' => 'form-control', 'placeholder' => 'Choose your card']) !!}
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
                        {!! Form::select('card_to', $cardsDictionary + [0 => 'Enter custom card number'], null, ['class' => 'form-control', 'placeholder' => 'Choose your card to donate', 'onchange' => 'showBlockOrNot(this);']) !!}
                        @error('card_to')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="{{ ((int)old('card_to') === 0 && old('card_to') !== null) ? '' : 'display:none;' }}" id="custom-number">
                        {!! Form::input('number', 'custom_number', null, ['maxlength' => 16, 'class' => 'form-control', 'placeholder' => 'Enter card number to transfer money']) !!}
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

                    <button class="btn-sm btn-flat">Transfer</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
