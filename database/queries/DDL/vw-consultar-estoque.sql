	CREATE VIEW VwConsultarEstoque
	AS
	SELECT
		p.ProdutoId,
		p.ProdutoNome,
		pe.ProdEstoqueQuantidade
	FROM TbProduto p
	INNER JOIN TbProdutoEstoque pe ON p.ProdutoId = pe.ProdutoId