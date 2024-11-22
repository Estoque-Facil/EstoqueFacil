ALTER VIEW VwConsultarComponentesProduto
AS
SELECT
	p.ProdutoId,
	cp.ProdutoComponenteId,
	pc.ProdutoNome,
	cp.CompProdutoQuantidade,
	cp.CompProdutoStatus
FROM TbProduto p
INNER JOIN TbComposicaoProduto cp ON p.ProdutoId = cp.ProdutoId
INNER JOIN TbProduto pc ON cp.ProdutoComponenteId = pc.ProdutoId