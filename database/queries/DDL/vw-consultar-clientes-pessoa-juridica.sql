CREATE VIEW VwConsultarClientesPessoaJuridica 
AS
SELECT
    c.ClienteId,
	c.TipoClienteId,
    pj.ClienteRazaoSocial,
	pj.ClienteCnpj,
    c.ClienteEmail,
    c.ClienteObser,
	c.ClienteEnderecoLogra,
    c.ClienteEnderecoNum,
    c.ClienteEnderecoComp,
    c.ClienteEnderecoBai,
    c.ClienteEnderecoCep,
	c.ClienteStatus,
    ci.CidadeNome,
    ci.EstadoUf
FROM TbCliente c
INNER JOIN TbClientePessoaJuridica pj ON c.ClienteId = pj.ClienteId
INNER JOIN TbCidade ci ON c.CidadeId = ci.CidadeId;