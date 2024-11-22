CREATE VIEW vwConsultarProdutos AS
SELECT 
    p.ProdutoId,
	tp.TipoProdutoNome,
    p.ProdutoNome,
	c.CategoriaNome,
    p.ProdutoDescr,
    p.ProdutoPreco,
    p.ProdutoStatus,
    p.ProdutoData,
    u.UsuarioNome
FROM TbProduto p
INNER JOIN TbUsuario u ON p.UsuarioId = u.UsuarioId
INNER JOIN TbTipoProduto tp ON p.TipoProdutoId = tp.TipoProdutoId
INNER JOIN TbCategoria c ON p.CategoriaId = c.CategoriaId;
