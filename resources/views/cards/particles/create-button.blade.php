{!! Form::open(['route' => 'cards.store', 'method' => 'post', 'onsubmit' => 'return confirm("Автоматически будет создана карта. Продолжить?");']) !!}
<button class="btn-sm btn-primary">Create card</button>
@if(Route::currentRouteName() !== 'cards.index')
<a href="{{ route('cards.index') }}" class="btn-sm">All cards</a>
@endif
{!! Form::close() !!}
