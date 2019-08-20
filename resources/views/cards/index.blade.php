@php
/** @var $user \App\Models\User */
/** @var $cards \App\Models\UserCard[]|\Illuminate\Database\Eloquent\Collection */
@endphp

@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My cards</div>
                <div class="card-body">
                    @include('cards.particles.list')
                    {!! $cards->links() !!}
                    @include('cards.particles.create-button')
                </div>
            </div>
        </div>
    </div>
@endsection
