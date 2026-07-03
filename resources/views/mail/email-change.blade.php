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
    Este e-mail foi informado para acessar a {{ config('mail.from.name') }}, por favor confirme.
    <br /><br />
    <a href="{{$link}}" target="_blank" class="button">Confirmar</a>
    <br /><br />
    Ou, cole este link no seu navegador: {{$link}}
    <br /><br />
    Atenciosamente,<br /><br />
    {{ config('mail.from.name') }}
  </body>
</html>
