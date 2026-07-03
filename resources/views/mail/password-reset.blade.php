<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <title>{{$subject}}</title>
  </head>
  <body>
    Olá,
    <br /><br />
    Houve uma solicitação de recuperação de senha para sua conta da {{ config('mail.from.name') }}.
    <br /><br />
    Se você fez esta solicitação, clique no link abaixo para configurar uma nova senha.
    Caso contrário, desconsidere esta mensagem.
    <br /><br />
    <a href="{{$link}}" target="_blank">{{$link}}</a>
    <br /><br />
    Atenciosamente,<br /><br />
    {{ config('mail.from.name') }}
  </body>
</html>
