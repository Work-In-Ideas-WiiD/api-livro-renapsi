@extends('mail.layout.base')

@section('content')
    <tr>
        <td class="email-body" width="100%" cellpadding="0" cellspacing="0">
        <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <!-- Body content -->
            <tr>
            <td class="content-cell">
                <div class="f-fallback">
                <h1>Bem-vindo de volta <a class=" {{ config('url.link.app_link') }}" href="mailto:{{$email}}" target="_blank">{{$email}}</a>!</h1>
                <p>Para redefinir a sua senha é super simples.</p>
                <p>Copie o <b> código de verificação </b> abaixo, acesse o link <b> <a class=" {{ config('url.link.app_link') }}" href="{{ $url }}" target="_blank"> redefinir senha </a> </b> e crie uma nova senha para o seu usuário.</p>

                <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                    <td class="attributes_content">
                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="attributes_item">
                            <span class="f-fallback">
                                <strong>Código de Verificação:</strong> {{$token}}
                            </span>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                <!-- Action -->
                <table class="body-action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                    <td align="center">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" role="presentation">
                        <tr>
                            <td align="center">
                            <a style="color: #FFF !important" href="{{ $url }}" class="f-fallback {{config('url.link.app_botao')}}" target="_blank">Redefinir Senha</a>
                            </td>
                        </tr>
                        </table>
                    </td>
                    </tr>
                </table>
                </div>
            </td>
            </tr>
        </table>
        </td>
    </tr>

@endsection
