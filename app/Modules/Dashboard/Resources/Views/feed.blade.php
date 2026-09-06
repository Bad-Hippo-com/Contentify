@foreach($feeds as $feed)
    <div class="feed-messages">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        {{ trans('app.latest_msgs') }} –
                        <a href="{{ $feed['project_url'] }}" target="_blank" rel="noopener noreferrer">{{ $feed['name'] }}</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($feed['messages'] as $message)
                    <tr>
                        <td title="{{ $message->text }}">
                            <a href="{{ $message->url }}" target="_blank" rel="noopener noreferrer">{!! HTML::fontIcon($message->icon) !!} {{ date(trans('app.date_format'), $message->timestamp) }}: {{ $message->text }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
