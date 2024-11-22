<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require('C:\João\Projetos\EstoqueFacil\src\back-end\conexao.php');

        // Obtendo os valores enviados pelo formulário
        $tipoCliente = $_POST['tipoCliente'] ?? null;
        $tipoPessoaCliente = $_POST['tipoPessoaCliente'] ?? null;
        $clienteRazaoSocial = $_POST['clienteRazaoSocial'] ?? null;
        $clienteNome = $_POST['clienteNome'] ?? null;
        $clienteSobrenome = $_POST['clienteSobrenome'] ?? null;
        $cpf = preg_replace('/\D/', '', $_POST['cpf']) ?? null;
        $cnpj = preg_replace('/\D/', '', $_POST['cnpj']) ?? null;
        $clienteObser = $_POST['clienteObser'] ?? null;
        $logradouro = $_POST['logradouro'] ?? null;
        $numero = $_POST['numero'] ?? null;
        $complemento = $_POST['complemento'] ?? null;
        $bairro = $_POST['bairro'] ?? null;
        $cep = preg_replace('/\D/', '', $_POST['cep']) ?? null;
        $estado = $_POST['estado'] ?? null;
        $cidade = $_POST['cidade'] ?? null;
        $email = $_POST['email'] ?? null;
        $usuarioId = $_SESSION['UsuarioId'];

        try {
            // Verificando o tipo de cadastro e chamando a procedure correta
            if ($tipoPessoaCliente === 'F') {
                // Pessoa Física
                $query = "EXEC SpCadastrarClientePessoaFisica 
                    @TipoClienteId = :tipoCliente, 
                    @ClienteEmail = :email, 
                    @ClienteObser = :observacao, 
                    @ClienteEnderecoLogra = :logradouro, 
                    @ClienteEnderecoNum = :numero, 
                    @ClienteEnderecoComp = :complemento, 
                    @ClienteEnderecoBai = :bairro, 
                    @ClienteEnderecoCep = :cep, 
                    @CidadeId = :cidade, 
                    @UsuarioId = :usuarioId, 
                    @ClienteNome = :nome, 
                    @ClienteSobrenome = :sobrenome, 
                    @ClienteCpf = :cpf";
            } elseif ($tipoPessoaCliente === 'J') {
                // Pessoa Jurídica
                $query = "EXEC SpCadastrarClientePessoaJuridica 
                    @TipoClienteId = :tipoCliente, 
                    @ClienteEmail = :email, 
                    @ClienteObser = :observacao, 
                    @ClienteEnderecoLogra = :logradouro, 
                    @ClienteEnderecoNum = :numero, 
                    @ClienteEnderecoComp = :complemento, 
                    @ClienteEnderecoBai = :bairro, 
                    @ClienteEnderecoCep = :cep, 
                    @CidadeId = :cidade, 
                    @UsuarioId = :usuarioId, 
                    @ClienteRazaoSocial = :razaoSocial, 
                    @ClienteCnpj = :cnpj";
            } else {
                throw new Exception("Tipo de cadastro inválido.");
            }

            $stmt = $conexao->prepare($query);

            // Vinculando os parâmetros
            $stmt->bindParam(':tipoCliente', $tipoCliente, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':observacao', $clienteObser, PDO::PARAM_STR);
            $stmt->bindParam(':logradouro', $logradouro, PDO::PARAM_STR);
            $stmt->bindParam(':numero', $numero, PDO::PARAM_STR);
            $stmt->bindParam(':complemento', $complemento, PDO::PARAM_STR);
            $stmt->bindParam(':bairro', $bairro, PDO::PARAM_STR);
            $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindParam(':cidade', $cidade, PDO::PARAM_INT);
            $stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
            
            // Dependendo do tipo de cliente, vincula os parâmetros adicionais
            if ($tipoPessoaCliente === 'F') {
                $stmt->bindParam(':nome', $clienteNome, PDO::PARAM_STR);
                $stmt->bindParam(':sobrenome', $clienteSobrenome, PDO::PARAM_STR);
                $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
            } elseif ($tipoPessoaCliente === 'J') {
                $stmt->bindParam(':razaoSocial', $clienteRazaoSocial, PDO::PARAM_STR);
                $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
            }

            // Executando a procedure
            if ($stmt->execute()) {
                header('Location: ../front-end/html/register-cliente.php?status=sucesso');
                exit();
            } else {
                header('Location: ../front-end/html/register-cliente.php?status=falha');
                exit();
            }
            
        } catch (Exception $e) {
            error_log("Erro ao registrar cliente: " . $e->getMessage());
            header('Location: ../front-end/html/register-cliente.php?status=falha');
            exit();
        }
    } else {
        header('Location: ../front-end/html/register-cliente.php?status=falhaForm');
        exit();
    }
?>
