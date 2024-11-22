CREATE PROCEDURE SpCadastrarClientePessoaFisica
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
    @ClienteNome NVARCHAR(255),
    @ClienteSobrenome NVARCHAR(255),
    @ClienteCpf CHAR(11)
AS
BEGIN
    BEGIN TRANSACTION;

    BEGIN TRY
        -- Inserir na tabela TbCliente
        INSERT INTO TbCliente (TipoClienteId, TipoPessoaCliente, ClienteEmail, ClienteObser, ClienteEnderecoLogra, ClienteEnderecoNum, 
                               ClienteEnderecoComp, ClienteEnderecoBai, ClienteEnderecoCep, CidadeId, UsuarioId)
        VALUES (@TipoClienteId, 'F', @ClienteEmail, @ClienteObser, @ClienteEnderecoLogra, @ClienteEnderecoNum, 
                @ClienteEnderecoComp, @ClienteEnderecoBai, @ClienteEnderecoCep, @CidadeId, @UsuarioId);

        DECLARE @ClienteId SMALLINT = SCOPE_IDENTITY();

        -- Inserir na tabela TbClientePessoaFisica
        INSERT INTO TbClientePessoaFisica (ClienteId, ClienteNome, ClienteSobrenome, ClienteCpf)
        VALUES (@ClienteId, @ClienteNome, @ClienteSobrenome, @ClienteCpf);

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;
GO