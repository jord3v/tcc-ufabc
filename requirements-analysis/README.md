# Análise de Requisitos para Sistema de Elaboração de Relatórios Circunstanciados

## 1. Introdução
Este documento apresenta a análise de requisitos para o desenvolvimento de um sistema que auxilia na elaboração de relatórios circunstanciados. O sistema deve gerar relatórios no formato Word, utilizando dados armazenados em um banco de dados interno.

## 2. Requisitos Funcionais

### 2.1 Gerenciamento de Usuários:

#### Cadastro de Usuários:
- Permitir o cadastro de novos usuários com as seguintes informações:
  - Nome completo
  - Endereço de e-mail
  - Senha
  - Foto de perfil (opcional)
  - ID da conta Google (opcional)
  - Outros detalhes (data de criação, data de atualização)

#### Autenticação de Usuários:
- Permitir o acesso de usuários cadastrados no sistema utilizando seu e-mail e senha.

#### Gerenciamento de Perfis:
- Permitir a visualização e edição das informações do perfil do usuário.
- Implementar controle de acesso baseado em perfis de usuário, definindo diferentes níveis de permissão para acesso às funcionalidades do sistema.

### 2.2 Gerenciamento de Empresas:

#### Cadastro de Empresas:
- Permitir o cadastro de novas empresas com as seguintes informações:
  - Nome da empresa
  - Razão social
  - CNPJ
  - ID do usuário que cadastrou a empresa
  - Outros detalhes (data de criação, data de atualização)

#### Consulta de Empresas:
- Permitir a consulta de empresas cadastradas no sistema por nome, razão social ou CNPJ.

#### Edição de Empresas:
- Permitir a edição das informações das empresas cadastradas.

### 2.3 Gerenciamento de Notas de Empenho:

#### Cadastro de Notas de Empenho:
- Permitir o cadastro de novas notas de empenho com as seguintes informações:
  - Ano
  - Número do processo
  - Modalidade
  - Processo da modalidade
  - Serviço prestado
  - Valor total da nota de empenho
  - Valor mensal
  - Descrição detalhada do serviço prestado
  - Situação da nota de empenho (pendente, em andamento, concluída)
  - Data de início e fim da execução do serviço

#### Consulta de Notas de Empenho:
- Permitir a consulta de notas de empenho cadastradas no sistema por ano, número do processo, modalidade, serviço prestado, situação ou data de início/fim da execução.

#### Edição de Notas de Empenho:
- Permitir a edição das informações das notas de empenho cadastradas.

### 2.4 Gerenciamento de Localizações:

#### Cadastro de Localizações:
- Permitir o cadastro de novas unidades operacionais do sistema com o nome da unidade.

#### Consulta de Localizações:
- Permitir a consulta das unidades operacionais cadastradas no sistema por nome.

### 2.5 Gerenciamento de Relatórios:

#### Criação de Relatórios:
- Permitir a criação de relatórios circunstanciados utilizando dados das classes "Empresas", "Localizações", "Notas de Empenho" e "Usuários".
- O relatório deve ser gerado no formato Word.
- O relatório deve conter os seguintes campos:
  - Gestor do relatório
  - Departamento do gestor
  - Data de geração do relatório
  - Dados das Empresas
    - Nome da empresa
    - Razão social
    - CNPJ
  - Dados das Localizações
    - Nome da unidade operacional
  - Dados das Notas de Empenho
    - Ano
    - Número do processo
    - Modalidade
    - Processo da modalidade
    - Serviço prestado
    - Valor total da nota de empenho
    - Valor mensal
    - Descrição detalhada do serviço prestado
    - Situação da nota de empenho
    - Data de início e fim da execução do serviço

#### Filtros de Relatórios:
- Permitir a filtragem dos dados utilizados na geração do relatório por diversos critérios, como:
  - Período de tempo (data de início e data final)
  - Empresas
  - Unidades operacionais
  - Modalidades de nota de empenho
  - Situação da nota de empenho

#### Visualização de Relatórios:
- Permitir a visualização dos relatórios gerados no formato Word.
- **Download do relatório para o computador do usuário**

### 2.6 Gerenciamento de Pagamentos (Opcional)

#### Cadastro de Pagamentos:
- Permitir o cadastro de pagamentos relacionados aos relatórios (opcional).
- As informações de pagamento devem incluir:
  - Identificador único (UUID)
  - Número da fatura
  - Referência do pagamento
  - Preço do serviço prestado
  - Data de pagamento
  - Data de elaboração do pagamento

#### Associação de Pagamentos a Relatórios:
- Permitir a vinculação de pagamentos a relatórios específicos (opcional).

## 3. Requisitos Não-Funcionais

### Segurança:
- O sistema deve implementar medidas de segurança para proteger os dados dos usuários e empresas. Isso inclui autenticação segura, criptografia de dados e controle de acesso baseado em permissões.

### Performance:
- O sistema deve responder a solicitações dos usuários de forma ágil e eficiente.

### Usabilidade:
- A interface do sistema deve ser intuitiva e fácil de usar para usuários com diferentes níveis de conhecimento técnico.

### Manutenção:
- O sistema deve ser fácil de manter e atualizar.

## 4. Considerações Futuras

- **Integração com outros sistemas:** Avaliar a possibilidade de integração com outros sistemas utilizados pela organização, como sistemas de contabilidade ou ERP.
- **Relatórios customizáveis:** Analisar a possibilidade de permitir a customização dos relatórios, possibilitando a inclusão ou exclusão de campos específicos.
- **Painel de visualização:** Considerar a implementação de um painel de visualização para exibir dados resumidos e gráficos sobre as notas de empenho e relatórios gerados.

Este documento apresenta uma análise inicial dos requisitos para o sistema de elaboração de relatórios circunstanciados. É importante envolver todos os stakeholders (partes interessadas) do projeto na definição dos requisitos para garantir que o sistema atenda às necessidades reais da organização.
