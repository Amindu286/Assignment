@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-3">
        <div class="row px-5 py-3">
            <a
              href="{{ route('dashboard', ['tab' => 'categories']) }}"
              class="btn btn-success btn-lg {{ $tab === 'categories' ? '' : 'opacity-75' }}"
            >Add Categories</a>
        </div>
        <div class="row px-5 py-3">
            <a
              href="{{ route('dashboard', ['tab' => 'products']) }}"
              class="btn btn-success btn-lg {{ $tab === 'products' ? '' : 'opacity-75' }}"
            >Add Products</a>
        </div>
    </div>

    <div class="col-sm-9">
        @if($tab === 'categories')
            @include('pages.category')
        @else
            @include('pages.add')
        @endif
    </div>
</div>
@endsection
