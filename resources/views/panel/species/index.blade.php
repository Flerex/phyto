@extends('panel.master')

@section('panel_content')
    <h1 class="title">@lang('panel.label.species')</h1>

    <div class="columns">
        <div class="column is-two-fifth">
            <nav class="panel">
                <p class="panel-heading">@lang('panel.species.hierarchy_filter')</p>
                <div class="panel-block">
                    <p class="control has-icons-left">
                        <input class="input is-small" type="text" placeholder="search">
                        <span class="icon is-small is-left"><i class="fas fa-search" aria-hidden="true"></i></span>
                    </p>
                </div>
                @foreach($domains as $domain)
                    <a class="panel-block"
                       href="{{ route('panel.species.index') . '?mode=domain&model=' . $domain->getKey() }}">
                        <span class="panel-icon"><i class="fas fa-book" aria-hidden="true"></i></span>
                        {{ $domain->name }}
                    </a>

                    @foreach($domain->classis as $class)
                        <a class="panel-block" href="{{ route('panel.species.index') . '?mode=classis&model=' . $class->getKey() }}">
                            <span class="panel-icon"></span>
                            <span class="panel-icon"><i class="fas fa-circle" aria-hidden="true"></i></span>
                            {{ $class->name }}
                        </a>

                        @foreach($class->genera as $genus)
                            <a class="panel-block" href="{{ route('panel.species.index') . '?mode=genus&model=' . $genus->getKey() }}">
                                <span class="panel-icon"></span><span class="panel-icon"></span>
                                <span class="panel-icon"><i class="fas fa-square" aria-hidden="true"></i></span>
                                {{ $genus->name }}
                            </a>
                        @endforeach
                    @endforeach
                @endforeach
            </nav>
        </div>
        <div class="column is-three-fifths">
            <table class="table is-fullwidth">
                <thead>
                <tr>
                    <th>@lang('labels.species.name')</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($species as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection
