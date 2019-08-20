{!! Form::open(['route' => 'cards.store', 'method' => 'post', 'onsubmit' => 'return confirm("Автоматически будет создана карта. Продолжить?");']) !!}
<button class="btn-sm btn-primary">Создать карту</button>
@if(Route::currentRouteName() !== 'cards.index')
<a href="{{ route('cards.index') }}" class="btn-sm">Все карты</a>
@endif
{!! Form::close() !!}
