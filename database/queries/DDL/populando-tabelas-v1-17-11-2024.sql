-- Populando o Banco
INSERT INTO TbEstado (EstadoUf, EstadoNome)
VALUES
('SP', 'S�o Paulo'),
('RJ', 'Rio de Janeiro'),
('MG', 'Minas Gerais'),
('MS', 'Mato Grosso do Sul'),
('ES', 'Esp�rito Santo'),
('PR', 'Paran�'),
('SC', 'Santa Catarina'),
('RS', 'Rio Grande do Sul');

-- Inserindo Estados restantes
INSERT INTO TbEstado (EstadoUf, EstadoNome)
VALUES
('AC', 'Acre'),
('AL', 'Alagoas'),
('AM', 'Amazonas'),
('AP', 'Amap�'),
('BA', 'Bahia'),
('CE', 'Cear�'),
('DF', 'Distrito Federal'),
('GO', 'Goi�s'),
('MA', 'Maranh�o'),
('MT', 'Mato Grosso'),
('PA', 'Par�'),
('PB', 'Para�ba'),
('PE', 'Pernambuco'),
('PI', 'Piau�'),
('RN', 'Rio Grande do Norte'),
('RO', 'Rond�nia'),
('RR', 'Roraima'),
('SE', 'Sergipe'),
('TO', 'Tocantins');

INSERT INTO TbCidade (CidadeNome, EstadoUf)
VALUES
('S�o Paulo', 'SP'),
('Rio de Janeiro', 'RJ'),
('Belo Horizonte', 'MG'),
('Campo Grande', 'MS'),
('Vit�ria', 'ES'),
('Curitiba', 'PR'),
('Florian�polis', 'SC'),
('Porto Alegre', 'RS');

-- Inserindo Cidades restantes
INSERT INTO TbCidade (CidadeNome, EstadoUf)
VALUES
('Rio Branco', 'AC'),
('Macei�', 'AL'),
('Manaus', 'AM'),
('Macap�', 'AP'),
('Salvador', 'BA'),
('Fortaleza', 'CE'),
('Bras�lia', 'DF'),
('Goi�nia', 'GO'),
('S�o Lu�s', 'MA'),
('Cuiab�', 'MT'),
('Bel�m', 'PA'),
('Jo�o Pessoa', 'PB'),
('Recife', 'PE'),
('Teresina', 'PI'),
('Natal', 'RN'),
('Porto Velho', 'RO'),
('Boa Vista', 'RR'),
('Aracaju', 'SE'),
('Palmas', 'TO');

INSERT INTO TbPermissao(PermissaoNome, PermissaoDescr)
VALUES 
('Administrador','Permiss�o para : administrar e possuir controle total sobre o sistema.'),
('Estoquista','Permiss�o para : registrar entradas e sa�das de produtos, consultar estoque e relat�rios, cadastrar clientes e produtos, atualizar informa��es de clientes e produtos.'),
('Operador','Permiss�o para : consultar estoque e relat�rios.');

INSERT INTO TbTipoCliente (TipoClienteNome, TipoClienteDescr, TipoClienteStatus)
VALUES 
('Fornecedor', 'Fornecedor de produtos ou servi�os', 1),
('Cliente', 'Cliente final que compra produtos ou servi�os', 1),
('Funcion�rio', 'Funcion�rio da empresa', 1),
('Transportadora', 'Transportadora respons�vel pela entrega de produtos', 1);

INSERT INTO TbTipoEntrada (TipoEntradaNome, TipoEntradaDescr, TipoEntradaStatus)
VALUES 
('Compra', 'Entrada de produtos atrav�s de compra', 1),
('Fabrica��o', 'Entrada de produtos fabricados internamente', 1),
('Devolu��o', 'Entrada de produtos devolvidos', 1),
('Retorno', 'Entrada de produtos retornados ap�s transporte ou outra causa', 1);

INSERT INTO TbTipoSaida (TipoSaidaNome, TipoSaidaDescr, TipoSaidaStatus)
VALUES 
('Venda', 'Sa�da de produtos vendidos', 1),
('Fabrica��o', 'Sa�da de produtos fabricados internamente', 1),
('Devolu��o', 'Sa�da de produtos devolvidos', 1),
('Remessa', 'Sa�da de produtos enviados por remessa', 1);

INSERT INTO TbEstoque (EstoqueNome) VALUES ('Campo F�cil Matriz');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Cochos', 'Produtos destinados a alimenta��o do gado'),
('Bebedouros','Produtos destinados a hidrata��o do gado'),
('Dep�sitos','Produtos destinados ao armazenamento de sal para gado');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Instala��o','Produtos que s�o utilizados na instala��o'),
('Pl�sticos','Mat�ria prima respons�vel pela fabrica��o dos produtos');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Gases','Gases que s�o utilizados para fabrica��o');

INSERT INTO TbCategoria (CategoriaNome, CategoriaDescr)
VALUES 
('Acess�rios','Acess�rios para os produtos finais');

INSERT INTO TbTipoProduto (TipoProdutoNome, TipoProdutoDescr)
VALUES 
('Produto', 'Produtos final para venda'),
('Mat�ria Prima','Mat�ria prima usada para fabrica��o de outros produtos'),
('Componente','Produto vendido junto ao produto final');

INSERT INTO TbProduto (ProdutoNome, ProdutoDescr, ProdutoPreco, CategoriaId, UsuarioId, TipoProdutoId) VALUES 
('Cocho Bovino Banda Forte Premium 300L', 'Cocho com 300L de capacidade para alimenta��o do gado, atende at� 50 cabe�as', 780.00, 1, 1, 1),
('Bebedouro Pilheta 1200L', 'Bebedouro com 1200L de capacidade para abastecimento de �gua, vai junto um kit de instala��o da b�ia', 1650.00, 2, 1,1);

INSERT INTO TbProduto (ProdutoNome, ProdutoDescr, ProdutoPreco, CategoriaId, UsuarioId, TipoProdutoId) VALUES 
('Polietileno Micronizado Preto', 'Mat�ria prima respons�vel pela fabrica��o dos produtos para gado', 11.00, 5, 1, 2),
('Kit Bebedouro Pilheta 1200L', 'Kit de instala��o da Pilheta 1200L', 50.00, 4, 1, 3);

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
(1, 'J', 'fullplasticos@example.com', 'Fornecedor de Mat�ria Prima', 'Avenida A', 123, 'Sala 1', 'Comercial', '12345678', 1, 1), 
(2, 'F', 'juliano@example.com', 'Amigo do junior', 'Rua B', 456, 'Apto 10', 'Centro', '87654321', 2, 1);

INSERT INTO TbClientePessoaFisica (ClienteId, ClienteNome, ClienteSobrenome, ClienteCpf) VALUES 
(2, 'Juliano', 'Silva', '12345678901');

INSERT INTO TbClientePessoaJuridica (ClienteId, ClienteRazaoSocial, ClienteCnpj) VALUES 
(1, 'Full Pl�sticos LTDA', '98765432100019');

SELECT * FROM TbMovimentacao
SELECT * FROM TbEntrada
SELECT * FROM TbSaida
SELECT * FROM TbTipoSaida

EXEC SpMovimentacaoEstoque
	@TipoMovimentacaoES = 1,
    @TipoMovimentacao = 'S', -- 'E' para Entrada, 'S' para Sa�da, 'F' para Fabrica��o
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