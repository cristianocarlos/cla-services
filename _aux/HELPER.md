#### Gerar ide helper em arquivo separado

`composer require --dev barryvdh/laravel-ide-helper`;

gera _ide_helper_models.php
`php artisan ide-helper:models --write-mixin`

gera _ide_helper.php
`php artisan ide-helper:generate`

gera .phpstorm.meta.php
`php artisan ide-helper:meta`

#### Gerar JWT Secret
`php artisan jwt:secret`
ou
`openssl rand -hex 32`

#### Gerar App Key
`php artisan key:generate`

### Gerar dpcumentação para api
`composer require dedoc/scramble`

### Mostrar path no prompt
1. Abrir `nano ~/.zshrc`
2. Incluir linha `PROMPT='%m:%/ %# '`

### BCrypt no PostgreSQL
```sql
INSERT INTO admin.user (user_mail, user_pass) VALUES ('a@a.a', CRYPT('senha', GEN_SALT('bf')));
SELECT * FROM admin.user WHERE user_pass = CRYPT('senha', user_pass);
```
Problema: o bcrypt do PostgreSQL não é compatível com o bcrypt do laravel
Pra resolver isso é necessário mudar o prefixo da senha de `$2a$` para `$2y$`
```sql
INSERT INTO admin.user (user_mail, user_pass) VALUES ('b@b.b', REPLACE(CRYPT('senha', GEN_SALT('bf')), '$2a$', '$2y$'));
SELECT * FROM admin.user WHERE REPLACE(user_pass, '$2y$', '$2a$') = CRYPT('senha', REPLACE(user_pass, '$2y$', '$2a$'));
```
