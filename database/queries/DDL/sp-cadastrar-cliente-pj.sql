CREATE PROCEDURE SpCadastrarClientePessoaJuridica
    @TipoClienteId SMALLINT,
    @ClienteEmail NVARCHAR(255),
    @ClienteObser NVARCHAR(255),
    @ClienteEnderecoLogra NVARCHAR(255),
    @ClienteEnderecoNum SMALLINT,
    @ClienteEnderecoComp NVARCHAR(255),
    @ClienteEnderecoBai NVARCHAR(255),
    @ClienteEnderecoCep CHAR(8),
    @CidadeId SMALLINT,
    @UsuarioId SMALLINT,
    @ClienteRazaoSocial NVARCHAR(255),
    @ClienteCnpj CHAR(14)
AS
BEGIN
    BEGIN TRANSACTION;

    BEGIN TRY
        -- Inserir na tabela TbCliente
        INSERT INTO TbCliente (TipoClienteId, TipoPessoaCliente, ClienteEmail, ClienteObser, ClienteEnderecoLogra, ClienteEnderecoNum, 
                               ClienteEnderecoComp, ClienteEnderecoBai, ClienteEnderecoCep, CidadeId, UsuarioId)
        VALUES (@TipoClienteId, 'J', @ClienteEmail, @ClienteObser, @ClienteEnderecoLogra, @ClienteEnderecoNum, 
                @ClienteEnderecoComp, @ClienteEnderecoBai, @ClienteEnderecoCep, @CidadeId, @UsuarioId);

        DECLARE @ClienteId SMALLINT = SCOPE_IDENTITY();

        -- Inserir na tabela TbClientePessoaJuridica
        INSERT INTO TbClientePessoaJuridica (ClienteId, ClienteRazaoSocial, ClienteCnpj)
        VALUES (@ClienteId, @ClienteRazaoSocial, @ClienteCnpj);

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO
