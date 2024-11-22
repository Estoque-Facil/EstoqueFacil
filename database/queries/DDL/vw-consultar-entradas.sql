CREATE VIEW VwConsultarEntradas
AS
SELECT 
    m.MovimentacaoId,
	m.MovimentacaoData,
	te.TipoEntradaNome,
	pf.ClienteNome,
	pf.ClienteCpf,
	pj.ClienteRazaoSocial,
	pj.ClienteCnpj,
    e.EntradaNfe,
    e.EntradaDataNfe,
    m.MovimentacaoQuantidade,
    p.ProdutoNome,
    u.UsuarioNome
FROM TbMovimentacao m
INNER JOIN TbProduto p ON m.ProdutoId = p.ProdutoId
INNER JOIN TbEstoque es ON m.EstoqueId = es.EstoqueId
INNER JOIN TbUsuario u ON m.UsuarioId = u.UsuarioId
LEFT JOIN TbCliente c ON m.ClienteId = c.ClienteId
LEFT JOIN TbClientePessoaFisica pf ON c.ClienteId = pf.ClienteId
LEFT JOIN TbClientePessoaJuridica pj ON c.ClienteId = pj.ClienteId
INNER JOIN TbEntrada e ON m.EntradaId = e.EntradaId
INNER JOIN TbTipoEntrada te ON e.TipoEntradaId = te.TipoEntradaId;