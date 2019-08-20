@php
/** @var $user \App\Models\User */
/** @var $cards \App\Models\UserCard[]|\Illuminate\Database\Eloquent\Collection */
/** @var $transactions \App\Models\UserTransaction[]|\Illuminate\Database\Eloquent\Collection */
@endphp

@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Last added cards</div>
                <div class="card-body">
                    @include('cards.particles.list')
                    @include('cards.particles.create-button')
                </div>
            </div>
        </div>
        <div class="col-md-10 mt-4">
            <div class="card">
                <div class="card-header">Last transactions</div>
                <div class="card-body">
                    @include('transactions.particles.list')
                    <a href="{{ route('transactions.create') }}" class="btn btn-sm btn-primary">Transfer money</a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm">All transactions</a>
                </div>
            </div>
        </div>
    </div>
@endsection
