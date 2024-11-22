ALTER PROCEDURE SpSaidaEstoque
    @TipoSaidaId SMALLINT,
    @Nfe INT,
    @DataNfe DATETIME,
    @SaidaObser NVARCHAR(255),
    @UsuarioId SMALLINT,
    @ClienteId SMALLINT,
    @EstoqueId SMALLINT,
    @ProdutoId SMALLINT,
    @Quantidade DECIMAL(10, 2)
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION;
			
		DECLARE @SaidaId SMALLINT;
		DECLARE @EstoqueAtualSaida DECIMAL(10,2);
		SELECT @EstoqueAtualSaida = ProdEstoqueQuantidade FROM TbProdutoEstoque WHERE ProdutoId = @ProdutoId AND EstoqueId = @EstoqueId;
					
		IF @EstoqueAtualSaida < @Quantidade
		BEGIN
			RAISERROR('Não há estoque suficiente para saída deste produto', 16, 1);
			RETURN;
		END;

        -- Inserir na tabela Saída
        INSERT INTO TbSaida (TipoSaidaId, SaidaNfe, SaidaDataNfe, SaidaData, SaidaObser)
        VALUES (@TipoSaidaId, @Nfe, @DataNfe, GETDATE(), @SaidaObser);

        SET @SaidaId = SCOPE_IDENTITY();

        -- Inserir na tabela Movimentação
        INSERT INTO TbMovimentacao (TipoMovimentacao, SaidaId, MovimentacaoData, UsuarioId, ClienteId, EstoqueId, ProdutoId, MovimentacaoQuantidade)
        VALUES ('S', @SaidaId, GETDATE(), @UsuarioId, @ClienteId, @EstoqueId, @ProdutoId, @Quantidade);

        -- Atualizar estoque
        UPDATE TbProdutoEstoque
        SET ProdEstoqueQuantidade = ProdEstoqueQuantidade - @Quantidade
        WHERE EstoqueId = @EstoqueId AND ProdutoId = @ProdutoId;

        -- Processar componentes do produto, se existirem
        IF EXISTS (SELECT 1 FROM TbComposicaoProduto WHERE ProdutoId = @ProdutoId)
        BEGIN
            DECLARE Componentes CURSOR FOR
            SELECT ProdutoComponenteId, CompProdutoQuantidade
            FROM TbComposicaoProduto
            WHERE ProdutoId = @ProdutoId;

            DECLARE @ComponenteId SMALLINT, @CompQuantidade DECIMAL(10, 2);

            OPEN Componentes;

            FETCH NEXT FROM Componentes INTO @ComponenteId, @CompQuantidade;

            WHILE @@FETCH_STATUS = 0
            BEGIN
				DECLARE @EstoqueAtualSaidaComp DECIMAL(10,2);
				SELECT @EstoqueAtualSaidaComp = ProdEstoqueQuantidade FROM TbProdutoEstoque WHERE ProdutoId = @ComponenteId AND EstoqueId = @EstoqueId;
					
				IF @EstoqueAtualSaidaComp < @CompQuantidade
				BEGIN
					RAISERROR('Não há estoque suficiente para dar saída no componente', 16, 1);
					RETURN;
				END;

                INSERT INTO TbMovimentacao (TipoMovimentacao, SaidaId, MovimentacaoData, UsuarioId, EstoqueId, ProdutoId, MovimentacaoQuantidade)
                VALUES ('S', @SaidaId, GETDATE(), @UsuarioId, @EstoqueId, @ComponenteId, @Quantidade * @CompQuantidade);

                UPDATE TbProdutoEstoque
                SET ProdEstoqueQuantidade = ProdEstoqueQuantidade - (@Quantidade * @CompQuantidade)
                WHERE EstoqueId = @EstoqueId AND ProdutoId = @ComponenteId;

                FETCH NEXT FROM Componentes INTO @ComponenteId, @CompQuantidade;
            END

            CLOSE Componentes;
            DEALLOCATE Componentes;
        END

        COMMIT;
    END TRY
    BEGIN CATCH
        ROLLBACK;
        DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT @ErrorMessage = ERROR_MESSAGE(), @ErrorSeverity = ERROR_SEVERITY(), @ErrorState = ERROR_STATE();   
        RAISERROR ('Erro na saída de estoque: %s', @ErrorSeverity, 1, @ErrorMessage);
    END CATCH
END;
