## Teste-Back-End

### Rotas:
/login -> Realizar login 

/registrar -> Cadastrar usuário

/investimento -> Realizar um investimento e simular um investimento

/resgatar -> Resgatar investimento

Utilizei o Bundle "knplabs/knp-paginator-bundle" para criar a paginação dos investimentos na rota de resgate

**Atenção:** É necessário executar a fixtures AppFixtures.php para inserir o dado fictício de produtos e testar o sistema
Comando para inserir os dados de exemplo da fixtures no banco: **symfony console doctrine:fixtures:load**
