-- Populando o Banco
INSERT INTO TbEstado (EstadoUf, EstadoNome)
VALUES
('SP', 'São Paulo'),
('RJ', 'Rio de Janeiro'),
('MG', 'Minas Gerais'),
('MS', 'Mato Grosso do Sul'),
('ES', 'Espírito Santo'),
('PR', 'Paraná'),
('SC', 'Santa Catarina'),
('RS', 'Rio Grande do Sul');

-- Inserindo Estados restantes
INSERT INTO TbEstado (EstadoUf, EstadoNome)
VALUES
('AC', 'Acre'),
('AL', 'Alagoas'),
('AM', 'Amazonas'),
('AP', 'Amapá'),
('BA', 'Bahia'),
('CE', 'Ceará'),
('DF', 'Distrito Federal'),
('GO', 'Goiás'),
('MA', 'Maranhão'),
('MT', 'Mato Grosso'),
('PA', 'Pará'),
('PB', 'Paraíba'),
('PE', 'Pernambuco'),
('PI', 'Piauí'),
('RN', 'Rio Grande do Norte'),
('RO', 'Rondônia'),
('RR', 'Roraima'),
('SE', 'Sergipe'),
('TO', 'Tocantins');

INSERT INTO TbCidade (CidadeNome, EstadoUf)
VALUES
('São Paulo', 'SP'),
('Rio de Janeiro', 'RJ'),
('Belo Horizonte', 'MG'),
('Campo Grande', 'MS'),
('Vitória', 'ES'),
('Curitiba', 'PR'),
('Florianópolis', 'SC'),
('Porto Alegre', 'RS');

-- Inserindo Cidades restantes
INSERT INTO TbCidade (CidadeNome, EstadoUf)
VALUES
('Rio Branco', 'AC'),
('Maceió', 'AL'),
('Manaus', 'AM'),
('Macapá', 'AP'),
('Salvador', 'BA'),
('Fortaleza', 'CE'),
('Brasília', 'DF'),
('Goiânia', 'GO'),
('São Luís', 'MA'),
('Cuiabá', 'MT'),
('Belém', 'PA'),
('João Pessoa', 'PB'),
('Recife', 'PE'),
('Teresina', 'PI'),
('Natal', 'RN'),
('Porto Velho', 'RO'),
('Boa Vista', 'RR'),
('Aracaju', 'SE'),
('Palmas', 'TO');

INSERT INTO TbPermissao(PermissaoNome, PermissaoDescr)
VALUES 
('Administrador','Permissão para : administrar e possuir controle total sobre o sistema.'),
('Estoquista','Permissão para : registrar entradas e saídas de produtos, consultar estoque e relatórios, cadastrar clientes e produtos, atualizar informações de clientes e produtos.'),
('Operador','Permissão para : consultar estoque e relatórios.');

INSERT INTO TbTipoCliente (TipoClienteNome, TipoClienteDescr, TipoClienteStatus)
VALUES 
('Fornecedor', 'Fornecedor de produtos ou serviços', 1),
('Cliente', 'Cliente final que compra produtos ou serviços', 1),
('Funcionário', 'Funcionário da empresa', 1),
('Transportadora', 'Transportadora responsável pela entrega de produtos', 1);

INSERT INTO TbTipoEntrada (TipoEntradaNome, TipoEntradaDescr, TipoEntradaStatus)
VALUES 
('Compra', 'Entrada de produtos através de compra', 1),
('Fabricação', 'Entrada de produtos fabricados internamente', 1),
('Devolução', 'Entrada de produtos devolvidos', 1),
('Retorno', 'Entrada de produtos retornados após transporte ou outra causa', 1);

INSERT INTO TbTipoSaida (TipoSaidaNome, TipoSaidaDescr, TipoSaidaStatus)
VALUES 
('Venda', 'Saída de produtos vendidos', 1),
('Fabricação', 'Saída de produtos fabricados internamente', 1),
('Devolução', 'Saída de produtos devolvidos', 1),
('Remessa', 'Saída de produtos enviados por remessa', 1);

INSERT INTO TbEstoque (EstoqueNome) VALUES ('Campo Fácil Matriz');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Cochos', 'Produtos destinados a alimentação do gado'),
('Bebedouros','Produtos destinados a hidratação do gado'),
('Depósitos','Produtos destinados ao armazenamento de sal para gado');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Instalação','Produtos que são utilizados na instalação'),
('Plásticos','Matéria prima responsável pela fabricação dos produtos');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Gases','Gases que são utilizados para fabricação');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Acessórios','Acessórios para os produtos finais');

INSERT INTO TbTipoProduto (TipoProdutoNome, TipoProdutoDescr)
VALUES 
('Produto', 'Produtos final para venda'),
('Matéria Prima','Matéria prima usada para fabricação de outros produtos'),
('Componente','Produto vendido junto ao produto final');

INSERT INTO TbProduto (ProdutoNome, ProdutoDescr, ProdutoPreco, CategoriaId, UsuarioId, TipoProdutoId) VALUES 
('Cocho Bovino Banda Forte Premium 300L', 'Cocho com 300L de capacidade para alimentação do gado, atende até 50 cabeças', 780.00, 1, 1, 1),
('Bebedouro Pilheta 1200L', 'Bebedouro com 1200L de capacidade para abastecimento de água, vai junto um kit de instalação da bóia', 1650.00, 2, 1,1);

INSERT INTO TbProduto (ProdutoNome, ProdutoDescr, ProdutoPreco, CategoriaId, UsuarioId, TipoProdutoId) VALUES 
('Polietileno Micronizado Preto', 'Matéria prima responsável pela fabricação dos produtos para gado', 11.00, 5, 1, 2),
('Kit Bebedouro Pilheta 1200L', 'Kit de instalação da Pilheta 1200L', 50.00, 4, 1, 3);

INSERT INTO TbFabricacaoProduto (ProdutoId, ProdutoMateriaPrimaId, FabriProdutoQuantidade)
VALUES
(1, 3, 58),
(2, 3, 43);

INSERT INTO TbComposicaoProduto (ProdutoId, ProdutoComponenteId, CompProdutoQuantidade)
VALUES
(2, 4, 1);

INSERT INTO TbProdutoEstoque (ProdutoId, EstoqueId, ProdEstoqueQuantidade)
VALUES 
(1, 1, 10),
(2, 1, 5);

INSERT INTO TbProdutoEstoque (ProdutoId, EstoqueId, ProdEstoqueQuantidade)
VALUES 
(3, 1, 4000),
(4, 1, 50);

INSERT INTO TbCliente (TipoClienteId, TipoPessoaCliente, ClienteEmail, ClienteObser, ClienteEnderecoLogra, ClienteEnderecoNum, ClienteEnderecoComp, ClienteEnderecoBai, ClienteEnderecoCep, CidadeId, UsuarioId) VALUES 
(1, 'J', 'fullplasticos@example.com', 'Fornecedor de Matéria Prima', 'Avenida A', 123, 'Sala 1', 'Comercial', '12345678', 1, 1), 
(2, 'F', 'juliano@example.com', 'Amigo do junior', 'Rua B', 456, 'Apto 10', 'Centro', '87654321', 2, 1);

INSERT INTO TbClientePessoaFisica (ClienteId, ClienteNome, ClienteSobrenome, ClienteCpf) VALUES 
(2, 'Juliano', 'Silva', '12345678901');

INSERT INTO TbClientePessoaJuridica (ClienteId, ClienteRazaoSocial, ClienteCnpj) VALUES 
(1, 'Full Plásticos LTDA', '98765432100019');

SELECT * FROM TbMovimentacao
SELECT * FROM TbEntrada
SELECT * FROM TbSaida
SELECT * FROM TbTipoSaida

EXEC SpMovimentacaoEstoque
	@TipoMovimentacaoES = 1,
    @TipoMovimentacao = 'S', -- 'E' para Entrada, 'S' para Saída, 'F' para Fabricação
	@Nfe = 503,
	@DataNfe = '2024-11-18 18:43:27.380',
	@EntradaTurno = NULL,
	@MovimentacaoObser = 'JULIANO COMPROU NO BOLETO',
    @UsuarioId = 1,
    @ClienteId = 2,
    @EstoqueId = 1,
    @ProdutoId = 2,
    @Quantidade = 1

SELECT * FROM TbMovimentacao