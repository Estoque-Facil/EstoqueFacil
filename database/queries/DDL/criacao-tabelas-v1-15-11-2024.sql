CREATE TABLE TbEstado (
    EstadoUf CHAR(2),
    EstadoNome NVARCHAR(255) NOT NULL,

    CONSTRAINT PkEstadoUf PRIMARY KEY (EstadoUf),
    CONSTRAINT UqEstadoNome UNIQUE (EstadoNome)
);

CREATE TABLE TbCidade (
    CidadeId SMALLINT IDENTITY(1,1),
    CidadeNome NVARCHAR(255) NOT NULL,
    EstadoUf CHAR(2) NOT NULL,

    CONSTRAINT PkCidadeId PRIMARY KEY (CidadeId),
    CONSTRAINT FkEstadoUfTbCidade FOREIGN KEY (EstadoUf) REFERENCES TbEstado (EstadoUf)
);

CREATE TABLE TbUsuario (
	UsuarioId SMALLINT IDENTITY(1,1),
	UsuarioNome NVARCHAR(255) NOT NULL,
	UsuarioEmail NVARCHAR(255) NOT NULL,
	UsuarioSenhaHash NVARCHAR(255) NOT NULL,
	UsuarioStatus BIT NOT NULL DEFAULT 1,
	UsuarioData DATETIME NOT NULL DEFAULT GETDATE(),
	UsuarioIdAdmin SMALLINT NOT NULL,

	CONSTRAINT PkUsuarioId PRIMARY KEY (UsuarioId),
	CONSTRAINT UqUsuarioEmail UNIQUE (UsuarioEmail),
	CONSTRAINT FkUsuarioIdAdmin FOREIGN KEY (UsuarioIdAdmin) REFERENCES TbUsuario (UsuarioId)
);

CREATE TABLE TbPermissao (
	PermissaoId SMALLINT IDENTITY(1,1),
	PermissaoNome NVARCHAR(255) NOT NULL,
	PermissaoDescr NVARCHAR(255) NOT NULL,

	CONSTRAINT PkPermissaoId PRIMARY KEY (PermissaoId),
	CONSTRAINT UqPermissaoNome UNIQUE (PermissaoNome)
);

CREATE TABLE TbUsuarioPermissao (
	UsuarioId SMALLINT NOT NULL,
	PermissaoId SMALLINT NOT NULL,

	CONSTRAINT PkUsuarioPermissaoId PRIMARY KEY (UsuarioId, PermissaoId),
	CONSTRAINT FkUsuarioIdTbUsuarioPermissao FOREIGN KEY (Usuarioid) REFERENCES TbUsuario (UsuarioId),
	CONSTRAINT FkPermissaoIdTbUsuarioPermissao FOREIGN KEY (PermissaoId) REFERENCES TbPermissao (PermissaoId)

);

CREATE TABLE TbCategoria (
	CategoriaId SMALLINT IDENTITY(1,1),
	CategoriaNome NVARCHAR(255) NOT NULL,
	CategoriaDescr NVARCHAR(255) NOT NULL,
	CategoriaStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkCategoriaId PRIMARY KEY (CategoriaId),
	CONSTRAINT UqCategoriaNome UNIQUE (CategoriaNome)
);

CREATE TABLE TbTipoProduto (
	TipoProdutoId SMALLINT IDENTITY(1,1),
	TipoProdutoNome NVARCHAR(255) NOT NULL,
	TipoProdutoDescr NVARCHAR(255) NOT NULL,
	TipoProdutoStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkTipoProdutoId PRIMARY KEY (TipoProdutoId),
	CONSTRAINT UqTipoProdutoNome UNIQUE (TipoProdutoNome)
);

CREATE TABLE TbProduto (
	ProdutoId SMALLINT IDENTITY(1,1),
	ProdutoNome NVARCHAR(255) NOT NULL,
	ProdutoDescr NVARCHAR(255) NOT NULL,
	ProdutoPreco DECIMAL(10,2) NOT NULL,
	CategoriaId SMALLINT NOT NULL,
	ProdutoStatus BIT NOT NULL DEFAULT 1,
	ProdutoData DATETIME NOT NULL DEFAULT GETDATE(),
	UsuarioId SMALLINT NOT NULL,
	TipoProdutoId SMALLINT NOT NULL,

	CONSTRAINT PkProdutoId PRIMARY KEY (ProdutoId),
	CONSTRAINT FkCategoriaIdTbProduto FOREIGN KEY (CategoriaId) REFERENCES TbCategoria (CategoriaId),
	CONSTRAINT FkUsuarioIdTbProduto FOREIGN KEY (UsuarioId) REFERENCES TbUsuario (UsuarioId),
	CONSTRAINT FkTipoProdutoIdTbProduto FOREIGN KEY (TipoProdutoId) REFERENCES TbTipoProduto (TipoProdutoId)
);

CREATE TABLE TbComposicaoProduto (
	ProdutoId SMALLINT NOT NULL,
	ProdutoComponenteId SMALLINT NOT NULL,
	CompProdutoQuantidade DECIMAL(10,2) NOT NULL,
	CompProdutoStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkComposicaoProdutoId PRIMARY KEY (ProdutoId, ProdutoComponenteId),
	CONSTRAINT FkProdutoIdTbComposicaoProduto FOREIGN KEY (ProdutoId) REFERENCES TbProduto (ProdutoId),
	CONSTRAINT FkProdutoComponenteIdTbComposicaoProduto FOREIGN KEY (ProdutoComponenteId) REFERENCES TbProduto (ProdutoId)
);

CREATE TABLE TbFabricacaoProduto (
	ProdutoId SMALLINT NOT NULL,
	ProdutoMateriaPrimaId SMALLINT NOT NULL,
	FabriProdutoQuantidade DECIMAL(10,2) NOT NULL,
	FabriProdutoStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkFabricacaoProdutoId PRIMARY KEY (ProdutoId, ProdutoMateriaPrimaId),
	CONSTRAINT FkProdutoIdTbFabricacaoProduto FOREIGN KEY (ProdutoId) REFERENCES TbProduto (ProdutoId),
	CONSTRAINT FkProdutoMateriaPrimaIdTbFabricacaoProduto FOREIGN KEY (ProdutoMateriaPrimaId) REFERENCES TbProduto (ProdutoId)
);

CREATE TABLE TbEstoque (
	EstoqueId SMALLINT IDENTITY(1,1),
	EstoqueNome NVARCHAR(255) NOT NULL,
	EstoqueStatus BIT NOT NULL DEFAULT 1,
	EstoqueData DATETIME NOT NULL DEFAULT GETDATE(),

	CONSTRAINT PkEstoqueId PRIMARY KEY (EstoqueId),
	CONSTRAINT UqEstoqueNome UNIQUE (EstoqueNome)
);

CREATE TABLE TbProdutoEstoque (
	EstoqueId SMALLINT NOT NULL,
	ProdutoId SMALLINT NOT NULL,
	ProdEstoqueQuantidade DECIMAL(10,2) NOT NULL

	CONSTRAINT PkProdutoEstoqueId PRIMARY KEY (EstoqueId, ProdutoId)
);

CREATE TABLE TbTipoCliente (
	TipoClienteId SMALLINT IDENTITY(1,1),
	TipoClienteNome NVARCHAR(255) NOT NULL,
	TipoClienteDescr NVARCHAR(255) NOT NULL,
	TipoClienteStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkTipoClienteId PRIMARY KEY (TipoClienteId),
	CONSTRAINT UqTipoClienteNome UNIQUE (TipoClienteNome)
);

CREATE TABLE TbCliente (
	ClienteId SMALLINT IDENTITY(1,1),
	TipoClienteId SMALLINT NOT NULL,
	TipoPessoaCliente CHAR(1) NOT NULL,
	ClienteEmail NVARCHAR(255) NOT NULL,
	ClienteObser NVARCHAR(255),
	ClienteEnderecoLogra NVARCHAR(255) NOT NULL,
    ClienteEnderecoNum SMALLINT NOT NULL,
    ClienteEnderecoComp NVARCHAR(255) NOT NULL,
    ClienteEnderecoBai NVARCHAR(255) NOT NULL,
    ClienteEnderecoCep CHAR(8) NOT NULL,
	CidadeId SMALLINT NOT NULL,
	ClienteStatus BIT NOT NULL DEFAULT 1,
	UsuarioId SMALLINT NOT NULL,
	ClienteData DATETIME NOT NULL DEFAULT GETDATE(),

	CONSTRAINT PkClienteId PRIMARY KEY (ClienteId),
	CONSTRAINT CkTipoPessoaCliente CHECK (TipoPessoaCliente IN('F', 'J')),
	CONSTRAINT FkTipoClienteIdTbCliente FOREIGN KEY (TipoClienteId) REFERENCES TbTipoCliente (TipoClienteId),
	CONSTRAINT FkCidadeIdTbCidade FOREIGN KEY (CidadeId) REFERENCES TbCidade (CidadeId),
	CONSTRAINT FkUsuarioIdTbUsuario FOREIGN KEY (UsuarioId) REFERENCES TbUsuario (UsuarioId)
);

CREATE TABLE TbClientePessoaFisica (
	ClientePfId SMALLINT IDENTITY(1,1),
	ClienteId SMALLINT NOT NULL,
	ClienteNome NVARCHAR(255) NOT NULL,
	ClienteSobrenome NVARCHAR(255) NOT NULL,
	ClienteCpf CHAR(11) NOT NULL,

	CONSTRAINT PkClientePfId PRIMARY KEY (ClientePfId),
	CONSTRAINT FkClienteIdTbClientePessoaFisica FOREIGN KEY (ClienteId) REFERENCES TbCliente (ClienteId)
);

CREATE TABLE TbClientePessoaJuridica (
	ClientePjId SMALLINT IDENTITY(1,1),
	ClienteId SMALLINT NOT NULL,
	ClienteRazaoSocial NVARCHAR(255) NOT NULL,
	ClienteCnpj CHAR(14) NOT NULL,

	CONSTRAINT PkClientePjId PRIMARY KEY (ClientePjId),
	CONSTRAINT FkClienteIdTbClientePessoaJuridica FOREIGN KEY (ClienteId) REFERENCES TbCliente (ClienteId)
);

CREATE TABLE TbTipoEntrada (
	TipoEntradaId SMALLINT IDENTITY(1,1),
	TipoEntradaNome NVARCHAR(255) NOT NULL,
	TipoEntradaDescr NVARCHAR(255) NOT NULL,
	TipoEntradaStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkTipoEntradaid PRIMARY KEY (TipoEntradaId),
	CONSTRAINT UqTipoEntradaNome UNIQUE(TipoEntradaNome)
);

CREATE TABLE TbTipoSaida (
	TipoSaidaId SMALLINT IDENTITY(1,1),
	TipoSaidaNome NVARCHAR(255) NOT NULL,
	TipoSaidaDescr NVARCHAR(255) NOT NULL,
	TipoSaidaStatus BIT NOT NULL DEFAULT 1,

	CONSTRAINT PkTipoSaidaid PRIMARY KEY (TipoSaidaId),
	CONSTRAINT UqTipoSaidaNome UNIQUE(TipoSaidaNome)
);

CREATE TABLE TbEntrada (
	EntradaId SMALLINT IDENTITY(1,1),
	TipoEntradaId SMALLINT NOT NULL,
	EntradaNfe INT,
	EntradaDataNfe DATETIME,
	EntradaData DATETIME NOT NULL DEFAULT GETDATE(),
	EntradaTurno NVARCHAR(255),
	EntradaObser NVARCHAR(255),

	CONSTRAINT PkEntradaId PRIMARY KEY (EntradaId),
	CONSTRAINT FkTipoEntradaIdTbEntrada FOREIGN KEY (TipoEntradaId) REFERENCES TbTipoEntrada (TipoEntradaId)
);

CREATE TABLE TbSaida (
	SaidaId SMALLINT IDENTITY(1,1),
	TipoSaidaId SMALLINT NOT NULL,
	SaidaNfe INT,
	SaidaDataNfe DATETIME,
	SaidaData DATETIME NOT NULL DEFAULT GETDATE(),
	SaidaObser NVARCHAR(255),

	CONSTRAINT PkSaidaId PRIMARY KEY (SaidaId),
	CONSTRAINT FkTipoSaidaIdTbSaida FOREIGN KEY (TipoSaidaId) REFERENCES TbTipoSaida (TipoSaidaId)
);

CREATE TABLE TbMovimentacao (
	MovimentacaoId SMALLINT IDENTITY(1,1),
	TipoMovimentacao CHAR(1) NOT NULL,
	EntradaId SMALLINT NULL,
	SaidaId SMALLINT NULL,
	MovimentacaoData DATETIME NOT NULL DEFAULT GETDATE(),
	MovimentacaoStatus BIT NOT NULL DEFAULT 1,
	UsuarioId SMALLINT NOT NULL,
	ClienteId SMALLINT,
	EstoqueId SMALLINT NOT NULL,
	ProdutoId SMALLINT NOT NULL,
	MovimentacaoQuantidade DECIMAL(10,2) NOT NULL,

	CONSTRAINT PkMovimentacaoId PRIMARY KEY (MovimentacaoId),
	CONSTRAINT CkTipoMovimentacao CHECK (TipoMovimentacao IN ('E', 'S', 'F')),
	CONSTRAINT FkEntradaId FOREIGN KEY (EntradaId) REFERENCES TbEntrada (EntradaId),
	CONSTRAINT FkSaidaId FOREIGN KEY (SaidaId) REFERENCES TbSaida (SaidaId),
	CONSTRAINT FkUsuarioIdTbMovimentacao FOREIGN KEY (UsuarioId) REFERENCES TbUsuario (UsuarioId),
	CONSTRAINT FkClienteIdTbMovimentacao FOREIGN KEY (ClienteId) REFERENCES TbCliente (ClienteId),
	CONSTRAINT FkEstoqueIdTbMovimentacao FOREIGN KEY (EstoqueId) REFERENCES TbEstoque (EstoqueId),
	CONSTRAINT FkProdutoIdTbMovimentacao FOREIGN KEY (ProdutoId) REFERENCES TbProduto (ProdutoId)
);

-- Removendo as constraints de FOREIGN KEY da tabela TbMovimentacao
ALTER TABLE TbMovimentacao DROP CONSTRAINT FkTipoMovimentacaoEntradaId;
ALTER TABLE TbMovimentacao DROP CONSTRAINT FkTipoMovimentacaoSaidaId;
ALTER TABLE TbMovimentacao DROP CONSTRAINT FkUsuarioIdTbMovimentacao;
ALTER TABLE TbMovimentacao DROP CONSTRAINT FkClienteIdTbMovimentacao;
ALTER TABLE TbMovimentacao DROP CONSTRAINT FkEstoqueIdTbMovimentacao;
ALTER TABLE TbMovimentacao DROP CONSTRAINT FkProdutoIdTbMovimentacao;

-- Removendo a tabela TbMovimentacao
DROP TABLE TbMovimentacao;
