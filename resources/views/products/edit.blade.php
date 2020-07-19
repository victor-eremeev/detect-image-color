@extends('layouts.app')

@section('content')

<div class="container">

    @component('components.breadcrumb')
        @slot('title') Редактирование товара @endslot
        @slot('parent') Главная @endslot
        @slot('active') Товары @endslot
    @endcomponent

    <hr />
    
    <product-create
        :path="'{{ route('product.update', $product) }}'"
        :index-path="'{{ route('product.index') }}'"
        :product-fields="{{ $product }}"
        :downloads="{{ $product->downloads ?? '' }}"
        :method="'put'"
    >
    </product-create>
</div>

@endsection
