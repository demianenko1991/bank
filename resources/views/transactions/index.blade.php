@php
/** @var $user \App\Models\User */
/** @var $transactions \App\Models\UserTransaction[]|\Illuminate\Database\Eloquent\Collection */
@endphp

@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(['method' => 'get']) !!}
                    {!! Form::select('card', $cardsDictionary, Request::get('card'), ['placeholder' => '&nbsp;']) !!}
                    {!! Form::submit('Filter') !!}
                    <a href="{{ route('transactions.index') }}" class="btn">Reset</a>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="card">
                <div class="card-header">My transactions</div>
                <div class="card-body">
                    @include('transactions.particles.list')
                    {!! $transactions->appends(request()->only('card'))->links() !!}
                    <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">Перевести деньги</a>
                </div>
            </div>
        </div>
    </div>
@endsection
