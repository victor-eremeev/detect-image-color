@extends('layouts.app')

@section('content')

<div class="container">

    @component('components.breadcrumb')
        @slot('title') Создание товара @endslot
        @slot('parent') Главная @endslot
        @slot('active') Товары @endslot
    @endcomponent

    <hr />

    <product-create
        :path="'{{ route('product.store') }}'"
        :index-path="'{{ route('product.index') }}'"
    ></product-create>

</div>

@endsection
