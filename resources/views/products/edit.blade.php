@extends('layouts.app')

@section('content')

<div class="container">

    @component('components.breadcrumb')
        @slot('title') Редактирование новости @endslot
        @slot('parent') Главная @endslot
        @slot('active') Новости @endslot
    @endcomponent

    <hr />
    
    <product-create
        :path="'{{ route('product.update', $product) }}'"
        :index-path="'{{ route('product.index') }}'"
        :product-fields="{{ $product }}"
        :downloads="{{ $product->downloads ?? '' }}"
    >
    </product-create>
</div>

@endsection
