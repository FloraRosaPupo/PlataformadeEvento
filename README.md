# Plataforma de Eventos

Aplicação acadêmica em PHP e MySQL para usuários, eventos, categorias e inscrições.

## Configuração local
Requer PHP 8.2+ com extensão mysqli e MySQL. Importe `Data/Sql.sql` em um banco de desenvolvimento (o script cria o banco `labprog`). Defina `DB_HOST`, `DB_USER`, `DB_PASSWORD` e `DB_NAME` no ambiente do terminal. `.env.example` apenas documenta as variáveis; PHP não carrega esse arquivo automaticamente.

```sh
php -S 127.0.0.1:8000
```
Abra `http://127.0.0.1:8000/home.php`. O servidor embutido é destinado ao desenvolvimento local.

## Organização
- `Classes/`: entidades e acesso aos dados.
- `Services/`: operações solicitadas pelas páginas.
- `pages/`: interface.
- `Data/`: conexão e esquema SQL.

## Verificação
A CI executa `php -l` em todos os arquivos PHP. Isso verifica sintaxe; os fluxos de autenticação, autorização e banco ainda exigem testes integrados. Esta aplicação acadêmica não foi auditada para publicação em produção.

## Licença
Não há licença de distribuição definida neste repositório.
