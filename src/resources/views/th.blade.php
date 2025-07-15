<th scope="row"
    class="baseColor py-2 {{$column['optionColumn']['class'] ?? ''}} text-dark font-weight-bold"
    style="text-align: center; {{$column['optionColumn']['style'] ?? ''}} @if(isset($column['attribute']) && $column['attribute'] != 'a') cursor:pointer; @endif"
    @if(isset($column['attribute']))
        onclick="sortTable('{{$column['sort']['column']}}','{{$column['state']['column']}}','{{$column['attribute']}}', '{{ request()->get($column['state']['column'])['sortState'] ?? 'ASC' }}')"
        @endif
>
    {{ $column['label'] }}
    @if(isset(request()->get($column['state']['column'])['sortState']) && request()->get($column['state']['column'])['sortState'] == 'ASC')
        @if(isset($column['attribute']) && $column['attribute'] != 'a')
            ▼
        @endif
    @else
        @if(isset($column['attribute']) && $column['attribute'] != 'a')
            ▲
        @endif
    @endif
    @if(isset($column['filter']))
        <br>
        <input type="text" name="{{$column['filter']['name']}}">
    @endif
</th>