ALTER VIEW VwConsultarUsuarios
AS
SELECT 
	u.UsuarioId,
	u.UsuarioNome,
	u.UsuarioEmail,
	p.PermissaoNome,
	u.UsuarioStatus,
	u.UsuarioData,
	ua.UsuarioNome AS UsuarioNomeAdmin
FROM TbUsuario u
INNER JOIN TbUsuarioPermissao up ON u.UsuarioId = up.UsuarioId
INNER JOIN TbPermissao p ON up.PermissaoId = p.PermissaoId
INNER JOIN TbUsuario ua ON u.UsuarioId = ua.UsuarioId;