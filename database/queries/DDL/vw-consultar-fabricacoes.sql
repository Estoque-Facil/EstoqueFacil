CREATE VIEW	VwConsultarFabricacoes
AS
SELECT 
    m.MovimentacaoId,
	m.MovimentacaoData,
	m.TipoMovimentacao,
	te.TipoEntradaNome,
	ts.TipoSaidaNome,
	e.EntradaId,
	s.SaidaId,
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
LEFT JOIN TbEntrada e ON m.EntradaId = e.EntradaId
LEFT JOIN TbTipoEntrada te ON e.TipoEntradaId = te.TipoEntradaId
LEFT JOIN TbSaida s ON m.SaidaId = s.SaidaId
LEFT JOIN TbTipoSaida ts ON s.TipoSaidaId = ts.TipoSaidaId;