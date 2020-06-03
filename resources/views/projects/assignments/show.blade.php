@extends('layouts.master')

@section('content')
    <div id="boxer"
         data-image-key="{{ $image->getKey() }}"
         data-image="{{ asset($image->path) }}"
         data-boxes="{{ $boxes->toJson() }}"
         data-user="{{ Auth::user()->toJson() }}"
         data-catalogs="{{ $catalogs->toJson()  }}"
         data-tree="{{ $tree->toJson()  }}"
    ></div>

    <div class="image-scroll" data-images="{{ $images->toJson() }}"></div>
@endsection
