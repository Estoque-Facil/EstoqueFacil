CREATE PROCEDURE SpInserirUsuario
	@UsuarioNome NVARCHAR(255),
	@UsuarioEmail NVARCHAR(255),
	@UsuarioSenhaHash NVARCHAR(255),
	@UsuarioIdAdmin SMALLINT,
	@PermissaoId SMALLINT
AS
BEGIN
	BEGIN TRY
		BEGIN TRANSACTION;
			DECLARE @UsuarioId SMALLINT;

			IF EXISTS (SELECT 1 FROM TbUsuario WHERE UsuarioEmail = @UsuarioEmail)
			BEGIN
				RAISERROR('Email já cadastrado!', 16, 1);
				RETURN;
			END

			INSERT INTO TbUsuario (UsuarioNome, UsuarioEmail, UsuarioSenhaHash, UsuarioIdAdmin) 
			VALUES (@UsuarioNome, @UsuarioEmail, @UsuarioSenhaHash, @UsuarioIdAdmin);

			SET @UsuarioId = SCOPE_IDENTITY();

			INSERT INTO TbUsuarioPermissao (UsuarioId, PermissaoId)
			VALUES (@UsuarioId, @PermissaoId);

		COMMIT;
	END TRY
	BEGIN CATCH
		ROLLBACK;
		DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT @ErrorMessage = ERROR_MESSAGE(), @ErrorSeverity = ERROR_SEVERITY(), @ErrorState = ERROR_STATE();   
        RAISERROR ('Erro na inserção do usuario: %s', @ErrorSeverity, 1, @ErrorMessage);
	END CATCH
END;