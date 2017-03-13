
    <div>
        @if($contents)
            @foreach($contents as $content)
            <div>
                <a href="{{ $content['url'] }}">
                    {{$content['title']}}
                </a>
            </div>
            <div>
                <a href="{{ $content['url'] }}">
                    {{$content['url']}}
                </a>
            </div>
            <div>
                <a href="{{ $content['url'] }}">
                    {{$content['author']}}
                </a>
            </div>
            @endforeach
        @endif
    </div>
