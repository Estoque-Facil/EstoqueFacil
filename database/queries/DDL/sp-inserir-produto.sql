ALTER PROCEDURE SpInserirProduto
    @ProdutoNome NVARCHAR(255),
    @ProdutoDescr NVARCHAR(255),
    @ProdutoPreco DECIMAL(10, 2),
    @CategoriaId SMALLINT,
    @UsuarioId SMALLINT,
    @TipoProdutoId SMALLINT,
    @ComponenteId SMALLINT,
    @ComponenteQtd DECIMAL(10,2),
	@MateriaPrimaId SMALLINT,
    @MateriaPrimaQtd DECIMAL(10,2)
AS
BEGIN
    BEGIN TRANSACTION;

    BEGIN TRY
        INSERT INTO TbProduto (ProdutoNome, ProdutoDescr, ProdutoPreco, CategoriaId, UsuarioId, TipoProdutoId)
        VALUES (@ProdutoNome, @ProdutoDescr, @ProdutoPreco, @CategoriaId, @UsuarioId, @TipoProdutoId);

        DECLARE @ProdutoId INT = SCOPE_IDENTITY();

        -- Insert into ProdutoEstoque
        INSERT INTO TbProdutoEstoque (EstoqueId, ProdutoId, ProdEstoqueQuantidade)
        VALUES (1, @ProdutoId, 0);

        -- Insert into ComposicaoProduto
        IF @ComponenteId IS NOT NULL AND @ComponenteQtd IS NOT NULL
            INSERT INTO TbComposicaoProduto (ProdutoId, ProdutoComponenteId, CompProdutoQuantidade)
            VALUES (@ProdutoId, @ComponenteId, @ComponenteQtd);

        -- Insert into FabricacaoProduto
        IF @MateriaPrimaId IS NOT NULL AND @MateriaPrimaQtd IS NOT NULL
            INSERT INTO TbFabricacaoProduto (ProdutoId, ProdutoMateriaPrimaId, FabriProdutoQuantidade)
            VALUES (@ProdutoId, @MateriaPrimaId, @MateriaPrimaQtd);

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH;
END;