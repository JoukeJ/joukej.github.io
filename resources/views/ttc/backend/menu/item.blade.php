@foreach($items as $title => $item)
    <li class="sub-menu @if($item['active']) active toggled @endif">
        <a href=""><i class="{{ $item['class'] }}"></i> {{ $title }}</a>

        <ul>
            @foreach($item['children'] as $link => $name)
                <li>
                    <a @if(\Request::url() == $link) class="active" @endif
                    href="{{ $link }}"> {{ $name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
@endforeach