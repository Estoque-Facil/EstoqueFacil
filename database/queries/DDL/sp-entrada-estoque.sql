ALTER PROCEDURE SpEntradaEstoque
    @TipoEntradaId SMALLINT,
    @Nfe INT,
    @DataNfe DATETIME,
    @EntradaObser NVARCHAR(255),
    @UsuarioId SMALLINT,
	@ClienteId SMALLINT,
    @EstoqueId SMALLINT,
    @ProdutoId SMALLINT,
    @Quantidade DECIMAL(10, 2)
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION;

        DECLARE @EntradaId SMALLINT;

        -- Inserir na tabela Entrada
        INSERT INTO TbEntrada (TipoEntradaId, EntradaNfe, EntradaDataNfe, EntradaData, EntradaObser)
        VALUES (@TipoEntradaId, @Nfe, @DataNfe, GETDATE(), @EntradaObser);

        SET @EntradaId = SCOPE_IDENTITY();

        -- Inserir na tabela Movimentação
        INSERT INTO TbMovimentacao (TipoMovimentacao, EntradaId, MovimentacaoData, UsuarioId, ClienteId, EstoqueId, ProdutoId, MovimentacaoQuantidade)
        VALUES ('E', @EntradaId, GETDATE(), @UsuarioId, @ClienteId, @EstoqueId, @ProdutoId, @Quantidade);

        -- Atualizar estoque
        UPDATE TbProdutoEstoque
        SET ProdEstoqueQuantidade = ProdEstoqueQuantidade + @Quantidade
        WHERE EstoqueId = @EstoqueId AND ProdutoId = @ProdutoId;

        COMMIT;
    END TRY
    BEGIN CATCH
        ROLLBACK;
        DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT @ErrorMessage = ERROR_MESSAGE(), @ErrorSeverity = ERROR_SEVERITY(), @ErrorState = ERROR_STATE();   
        RAISERROR ('Erro na entrada de estoque: %s', @ErrorSeverity, 1, @ErrorMessage);
    END CATCH
END;
