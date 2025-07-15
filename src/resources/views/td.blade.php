<td scope="col"
    @if(isset($column['tdOption']['attributes']))
        @foreach($column['tdOption']['attributes'] as $attr => $val)
            {{$attr . '=' . $val}}
        @endforeach
    @endif
    class="{{$column['tdOption']['class'] ?? ''}}"
    style="{{ $tdOptionStyle ?? '' }}">
    @if(isset($column['attribute']))
        {!! $value !!}
    @else
        {{ $value }}
    @endif
</td>