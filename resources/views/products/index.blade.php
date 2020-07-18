@extends('layouts.app')

@section('content')

<div class="container">

    @component('components.breadcrumb')
        @slot('title') Список товаров @endslot
        @slot('parent') Главная @endslot
        @slot('active') Товары @endslot
    @endcomponent

    <a href="{{ route('product.create') }}" class="btn btn-primary mb-2">
        Создать
    </a>

    <table class="table table-striped table-borderless">
        <thead class="thead-dark">
            <th>Наименование</th>
            <th class="text-right">Действие</th>
        </thead>
        <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td class="text-right">
                    <form onsubmit="if(confirm('Удалить?')){ return true }else{ return false }" action="{{ route('product.destroy', $product) }}" method="post">
                        @method('DELETE')
                        @csrf
                    
                        <a class="btn btn-primary" href="{{ route('product.edit', $product) }}"><i class="fa fa-edit"></i></a>

                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center"><h2>Товары отсутствуют</h2></td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    {{ $products->links() }}
                </td>
            </tr>
        </tfoot>
    </table>

</div>

@endsection
