CREATE VIEW VwConsultarClientesBasico
AS
SELECT 
    c.ClienteId,
    tc.TipoClienteNome,
	c.TipoPessoaCliente,
    pj.ClienteRazaoSocial,
    pf.ClienteNome,
    pf.ClienteSobrenome,
    pf.ClienteCpf,
    pj.ClienteCnpj,
    ci.CidadeNome,
	e.EstadoUf,
	c.ClienteStatus,
    c.ClienteData
FROM TbCliente c
INNER JOIN TbTipoCliente tc ON c.TipoClienteId = tc.TipoClienteId
LEFT JOIN TbClientePessoaFisica pf ON c.ClienteId = pf.ClienteId
LEFT JOIN TbClientePessoaJuridica pj ON c.ClienteId = pj.ClienteId
INNER JOIN TbCidade ci ON c.CidadeId = ci.CidadeId
INNER JOIN TbEstado e ON ci.EstadoUf = e.EstadoUf
