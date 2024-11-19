CREATE PROCEDURE SpMovimentacaoEstoque
	@TipoMovimentacaoES SMALLINT,
    @TipoMovimentacao CHAR(1), -- 'E' para Entrada, 'S' para Saída, 'F' para Fabricação
	@Nfe INT,
	@DataNfe DATETIME,
	@EntradaTurno NVARCHAR(255) = NULL,
	@MovimentacaoObser NVARCHAR(255),
    @UsuarioId SMALLINT,
    @ClienteId SMALLINT = NULL,
    @EstoqueId SMALLINT,
    @ProdutoId SMALLINT,
    @Quantidade DECIMAL(10, 2)
AS
BEGIN
    BEGIN TRY
        BEGIN TRANSACTION;

        DECLARE @SaidaId SMALLINT, @EntradaId SMALLINT;

        -- Validação adicional para Fabricação
        IF @TipoMovimentacao = 'F'
        BEGIN
			INSERT INTO TbEntrada (TipoEntradaId, EntradaTurno, EntradaData, EntradaObser)
			VALUES (@TipoMovimentacaoES, @EntradaTurno, GETDATE(), @MovimentacaoObser);

			SET @EntradaId = SCOPE_IDENTITY();

			INSERT INTO TbMovimentacao (TipoMovimentacao, EntradaId, MovimentacaoData, UsuarioId, EstoqueId, ProdutoId, MovimentacaoQuantidade)
			VALUES ('F', @EntradaId, GETDATE(), @UsuarioId, @EstoqueId, @ProdutoId, @Quantidade);

			UPDATE TbProdutoEstoque
			SET ProdEstoqueQuantidade = ProdEstoqueQuantidade + @Quantidade
			WHERE EstoqueId = @EstoqueId AND ProdutoId = @ProdutoId;

			-- Processar fabricação
            IF EXISTS (SELECT 1 FROM TbFabricacaoProduto WHERE ProdutoId = @ProdutoId)
            BEGIN
				IF NOT EXISTS (SELECT 1 FROM TbTipoSaida WHERE TipoSaidaId = @TipoMovimentacaoES)
				BEGIN
					RAISERROR('O TipoSaidaId informado não é válido.', 16, 1);
					RETURN;
				END;

				-- Criando uma saída para a matéria prima
                INSERT INTO TbSaida (TipoSaidaId, SaidaData, SaidaObser)
                VALUES (@TipoMovimentacaoES, GETDATE(), NULL);

                SET @SaidaId = SCOPE_IDENTITY();

                DECLARE Fabricacao CURSOR FOR
                SELECT ProdutoMateriaPrimaId, FabriProdutoQuantidade
                FROM TbFabricacaoProduto
                WHERE ProdutoId = @ProdutoId;

                DECLARE @MateriaPrimaId SMALLINT, @MateriaPrimaQuantidade DECIMAL(10, 2);

                OPEN Fabricacao;

                FETCH NEXT FROM Fabricacao INTO @MateriaPrimaId, @MateriaPrimaQuantidade;

                WHILE @@FETCH_STATUS = 0
                BEGIN
					DECLARE @EstoqueAtualMateriaPrima DECIMAL(10,2);
					SELECT @EstoqueAtualMateriaPrima = ProdEstoqueQuantidade FROM TbProdutoEstoque WHERE ProdutoId = @MateriaPrimaId AND EstoqueId = @EstoqueId;
					
					IF @EstoqueAtualMateriaPrima < @MateriaPrimaQuantidade
					BEGIN
						RAISERROR('Não há estoque suficiente para fabricar este produto', 16, 1);
						RETURN;
					END;

                    INSERT INTO TbMovimentacao (TipoMovimentacao, SaidaId, MovimentacaoData, UsuarioId, EstoqueId, ProdutoId, MovimentacaoQuantidade)
                    VALUES ('F', @SaidaId, GETDATE(), @UsuarioId, @EstoqueId, @MateriaPrimaId, @Quantidade * @MateriaPrimaQuantidade);

                    UPDATE TbProdutoEstoque
                    SET ProdEstoqueQuantidade = ProdEstoqueQuantidade - (@Quantidade * @MateriaPrimaQuantidade)
                    WHERE EstoqueId = @EstoqueId AND ProdutoId = @MateriaPrimaId;

                    FETCH NEXT FROM Fabricacao INTO @MateriaPrimaId, @MateriaPrimaQuantidade;
                END

                CLOSE Fabricacao;
                DEALLOCATE Fabricacao;
            END
        END

        -- Processamento para Saída
        IF @TipoMovimentacao = 'S'
        BEGIN
			DECLARE @EstoqueAtualSaida DECIMAL(10,2);
			SELECT @EstoqueAtualSaida = ProdEstoqueQuantidade FROM TbProdutoEstoque WHERE ProdutoId = @ProdutoId AND EstoqueId = @EstoqueId;
					
			IF @EstoqueAtualSaida < @Quantidade
			BEGIN
				RAISERROR('Não há estoque suficiente para saída deste produto', 16, 1);
				RETURN;
			END;

            -- Inserir na tabela Saída
            INSERT INTO TbSaida (TipoSaidaId, SaidaNfe, SaidaDataNfe, SaidaData, SaidaObser)
            VALUES (@TipoMovimentacaoES, @Nfe, @DataNfe, GETDATE(), @MovimentacaoObser);

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
        END

        -- Processamento para Entrada
        IF @TipoMovimentacao = 'E'
        BEGIN
            INSERT INTO TbEntrada (TipoEntradaId, EntradaNfe, EntradaDataNfe, EntradaData, EntradaObser)
            VALUES (@TipoMovimentacaoES, @Nfe, @DataNfe, GETDATE(), @MovimentacaoObser);

            SET @EntradaId = SCOPE_IDENTITY();

            INSERT INTO TbMovimentacao (TipoMovimentacao, EntradaId, MovimentacaoData, UsuarioId, EstoqueId, ProdutoId, MovimentacaoQuantidade)
            VALUES ('E', @EntradaId, GETDATE(), @UsuarioId, @EstoqueId, @ProdutoId, @Quantidade);

            UPDATE TbProdutoEstoque
            SET ProdEstoqueQuantidade = ProdEstoqueQuantidade + @Quantidade
            WHERE EstoqueId = @EstoqueId AND ProdutoId = @ProdutoId;
        END

        COMMIT;
    END TRY
    BEGIN CATCH
        ROLLBACK;
        DECLARE @ErrorMessage NVARCHAR(4000), @ErrorSeverity INT, @ErrorState INT;
        SELECT @ErrorMessage = ERROR_MESSAGE(), @ErrorSeverity = ERROR_SEVERITY(), @ErrorState = ERROR_STATE();   
        RAISERROR ('Erro na movimentação de estoque: %s', @ErrorSeverity, 1, @ErrorMessage);
    END CATCH
END;