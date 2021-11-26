@extends('layout.site', ['title' => 'Личный кабинет'])

@section('content')
    <h1>Личный кабинет</h1>
    <p>Добрый день {{ auth()->user()->name }}!</p>
    <p>Это личный кабинет пользователя сайта.</p>
@role('root')
<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.index') }}">Панель администратора</a> <!-- активная ссылка -->
    </li>
</ul>
@else
<p>не имеет</p>
@endrole

@endsection