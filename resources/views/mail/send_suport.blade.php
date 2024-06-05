@extends('mail.layout.base')

@section('content')
<tr>
    <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <!-- Body content -->
            <tr>
                <td class="content-cell">
                    <div class="f-fallback">
                        <h1>E-mail: <a class=" {{ config('url.link.app_link') }}" href="mailto:{{$email}}" target="_blank">{{$email}}</a>!</h1>
                        {!! $texto !!}
                     
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>

@endsection
