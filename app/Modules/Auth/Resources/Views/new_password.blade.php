<h1 class="page-title">{{ trans('auth::password_reset') }}</h1>
<meta name="referrer" content="no-referrer">
{!! Form::errors($errors) !!}
<p>{{ trans('auth::reset_password_help') }}</p>
{!! Form::open(['url' => 'auth/restore/new/'.rawurlencode($email).'/'.rawurlencode($code)]) !!}
    {!! Form::smartPassword() !!}
    {!! Form::smartPassword('password_confirmation', trans('auth::reset_confirmation')) !!}
    {!! Form::actions(['submit' => trans('auth::password_reset')], false) !!}
{!! Form::close() !!}
