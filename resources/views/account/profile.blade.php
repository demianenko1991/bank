@php
/** @var $user \App\Models\User */
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">My profile</div>

                    <div class="card-body">
                        Hello, {{ $user->name }}!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
