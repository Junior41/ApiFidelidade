# Resumo

Api desenvolvida em php com o framework laravel que simula um sistema de recompensas onde a cada 5 reais gastos o cliente ganha 1 ponto, esses pontos podem ser trocados por recompensas. Sempre que o cliente ganha um ponto ou faz uma troca de pontos por recompensas, ele recebe um email. A arquitetura foi pensada de modo a garantir eficiência e escalabilidade. Para isso, foi utilizado laravel jobs e técnicas de código limpo.

A api permite:
 - Cadastro de um cliente;
 - Busca de um cliente específico;
 - Busca de todos os clientes;
 - Busca das recompensas resgatadas pelo cliente;
 - Cadastro de transações e pontuação automática do cliente;
 - Troca de pontos por produtos.


# Instalção e inicialização (linux)

Execute os comandos dentro da pasta backend, após criar seu .env:
-    composer install
-    php artisan key:generate
-   ./vendor/bin/sail up -d
-    ./vendor/bin/sail artisan migrate
-    ./vendor/bin/sail db:seed
-    ./vendor/bin/sail artisan queue:work

# Endpoints

- ## clients (/api/v1/client)
  - ### /api/v1/clients - POST<br/>
    Cadastra um cliente.<br/>
    Parâmetros:
    - name (String, obrigatório);
    - email (string, obrigatório).
  - ### /api/v1/clients - GET<br/>
    Lista todos os clientes.
  - ### /api/v1/clients/{id} - GET<br/>
    Lista um cliente em espécifico pelo id.
  - ### /api/v1/clients/{id}/rewards - GET<br/>
    Lista todas as recompensas resgatadas por um determinado cliente.

- ## transactions (/api/v1/transaction)
  - ### /api/v1/transactions - POST<br/>
    Registra uma transação. A pontuação do cliente é feita com base no saldo do mesmo, a cada 5 reais gastos 1 ponto é adicionado. Quando o cliente solicita o resgate
    o saldo é descontado com base no preço em pontos do produto.<br/>
    Parâmetros:
    - value (Double, obrigatório, maior que 0);
    - clienteId (int, obrigatório).
   
- ## exchange (/api/v1/exchange)
  - ### /api/v1/exchange - POST<br/>
    Registar a troca de pontos do cliente por um prêmio, caso o cliente tenha pontos suficientes.<br/>
    Parâmetros:
    - clientId (int, obrigatório);
    - rewardId (int, obrigatório).

