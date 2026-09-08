<html lang="en">
<head>
    <title>{{ trans('auth::password_reset') }}</title>
    <meta charset="utf-8">
</head>
<body>
    <p>{{ trans('auth::pw_link', [Config::get('app.name')]) }}</p>

    <table>
        <tr>
            <td><strong>{{ trans('app.email') }}:</strong></td>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <td><strong>{{ trans('app.link') }}:</strong></td>
            <td>{!! link_to('auth/restore/new/'.rawurlencode($user->email).'/'.$code) !!}</td>
        </tr>
    </table>
    
    <p>{{ trans('auth::email_ignore') }}</p>
</body>
</html>
