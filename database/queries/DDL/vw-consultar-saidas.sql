USE [DbEstoqueFacil]
GO

/****** Object:  View [dbo].[VwConsultarSaidas]    Script Date: 21/11/2024 01:33:28 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW VwConsultarSaidas
AS
SELECT 
    m.MovimentacaoId,
	m.MovimentacaoData,
	ts.TipoSaidaNome,
	pf.ClienteNome,
	pf.ClienteCpf,
	pj.ClienteRazaoSocial,
	pj.ClienteCnpj,
    s.SaidaNfe,
    s.SaidaDataNfe,
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
INNER JOIN TbSaida s ON m.SaidaId = s.SaidaId
INNER JOIN TbTipoSaida ts ON s.TipoSaidaId = ts.TipoSaidaId;
GO


