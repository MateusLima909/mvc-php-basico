<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\UsuarioDAO;
use App\Models\DAO\ClienteDAO;
use App\Models\Entidades\Usuario;
use App\Models\Entidades\Cliente;

class ClienteController extends Controller
{
    public function __construct($app)
    {
        parent::__construct($app);
        // Garante que a sessão está sempre iniciada quando se utiliza este controller.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Renderiza a página pública de cadastro de um novo cliente.
     */
    public function cadastro()
    {
        Sessao::limpaFormulario();
        Sessao::limpaMensagem();
        
        $this->render('cliente/cadastro');
    }

    /**
     * Processa os dados do formulário de cadastro, criando um usuário e um cliente.
     */
    public function salvar()
    {
        $dadosPost = $_POST;
        Sessao::gravaFormulario($dadosPost);

        $usuarioDAO = new UsuarioDAO();
        $clienteDAO = new ClienteDAO();

        $erros = [];

        // Validação dos Campos
        if (empty($dadosPost['nome'])) $erros[] = "O campo Nome é obrigatório.";
        if (empty($dadosPost['cpf'])) $erros[] = "O campo CPF é obrigatório.";
        if (empty($dadosPost['email'])) $erros[] = "O campo Email é obrigatório.";
        if (!filter_var($dadosPost['email'], FILTER_VALIDATE_EMAIL)) $erros[] = "O formato do e-mail é inválido.";
        if (empty($dadosPost['senha'])) $erros[] = "O campo Senha é obrigatório.";
        if (strlen($dadosPost['senha']) < 6) $erros[] = "A senha deve ter no mínimo 6 caracteres.";
        if ($dadosPost['senha'] !== $dadosPost['confirmar_senha']) $erros[] = "As senhas não coincidem.";
        
        if (empty($erros)) {
            if ($usuarioDAO->verificaEmail($dadosPost['email'])) $erros[] = "Este e-mail já está em uso.";
            if ($clienteDAO->verificaCpf($dadosPost['cpf'])) $erros[] = "Este CPF já está cadastrado.";
        }

        if (!empty($erros)) {
            Sessao::gravaMensagem(implode("<br>", $erros));
            $this->redirect('/cliente/cadastro');
            return;
        }

        try {
            $novoUsuario = new Usuario();
            $novoUsuario->setNome($dadosPost['nome']);
            $novoUsuario->setEmail($dadosPost['email']);
            $novoUsuario->setSenha(password_hash($dadosPost['senha'], PASSWORD_DEFAULT));
            $novoUsuario->setNivelAcesso('cliente');

            $idUsuarioNovo = $usuarioDAO->salvar($novoUsuario);
            
            if (!$idUsuarioNovo) {
                throw new \Exception("Falha ao criar a conta de usuário.");
            }

            $novoCliente = new Cliente();
            $novoCliente->setNome($dadosPost['nome']);
            $novoCliente->setDtnasc($dadosPost['dtnasc']);
            $novoCliente->setCpf($dadosPost['cpf']);
            $novoCliente->setTelefone($dadosPost['telefone']);
            $novoCliente->setIdUsuario($idUsuarioNovo);

            $clienteDAO->salvar($novoCliente);
            
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Cadastro realizado com sucesso! Faça seu login.");
            $this->redirect('/login');

        } catch (\Exception $e) {
            error_log("Erro no cadastro de cliente: " . $e->getMessage());
            Sessao::gravaMensagem("Erro ao realizar o cadastro. Tente novamente.");
            $this->redirect('/cliente/cadastro');
        }
    }

    /**
     * Exibe o perfil do cliente logado.
     */
    public function perfil()
    {
        // Garante que o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            Sessao::gravaMensagem("Você precisa estar logado para ver seu perfil.");
            $this->redirect('/login');
            return;
        }

        // Garante que o usuário é um cliente
        if ($_SESSION['usuario_nivel'] !== 'cliente') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        try {
            $clienteDAO = new ClienteDAO();
            // Busca os dados do cliente usando o id do usuário guardado na sessão
            $cliente = $clienteDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

            if ($cliente) {
                $this->setViewParam('cliente', $cliente);
                $this->render('cliente/perfil'); // Renderiza a nova view de perfil
            } else {
                Sessao::gravaMensagem("Não foi possível encontrar os dados do seu perfil de cliente.");
                $this->redirect('/home');
            }
        } catch (\Exception $e) {
            error_log("Erro ao buscar perfil do cliente: " . $e->getMessage());
            Sessao::gravaMensagem("Ocorreu um erro ao carregar seu perfil. Tente novamente.");
            $this->redirect('/home');
        }
    }

    /**
     * Exibe o formulário para o cliente editar o seu próprio perfil.
     */
    public function editarPerfil()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'cliente') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/login');
            return;
        }

        try {
            $clienteDAO = new ClienteDAO();
            $cliente = $clienteDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

            if ($cliente) {
                Sessao::limpaFormulario(); // Limpa dados de formulários antigos
                $this->setViewParam('cliente', $cliente);
                $this->render('cliente/editarPerfil'); // Renderiza a nova view de edição
            } else {
                Sessao::gravaMensagem("Não foi possível encontrar seu perfil para edição.");
                $this->redirect('/cliente/perfil');
            }
        } catch (\Exception $e) {
            error_log("Erro ao carregar formulário de edição de perfil: " . $e->getMessage());
            Sessao::gravaMensagem("Ocorreu um erro ao carregar a página de edição.");
            $this->redirect('/cliente/perfil');
        }
    }

    /**
     * Processa a atualização do perfil do próprio cliente.
     */
    public function atualizarPerfil()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'cliente') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/login');
            return;
        }

        $clienteDAO = new ClienteDAO();
        // Busca o cliente existente para garantir que estamos a editar o correto
        // e para manter dados que não podem ser alterados (como id_usuario e cpf)
        $clienteAtual = $clienteDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

        if (!$clienteAtual) {
            Sessao::gravaMensagem("O seu perfil não foi encontrado. A atualização falhou.");
            $this->redirect('/home');
            return;
        }

        // Validação dos dados recebidos
        if (empty($_POST['nome']) || empty($_POST['telefone'])) {
            Sessao::gravaMensagem("Nome e telefone são campos obrigatórios.");
            $this->redirect('/cliente/editarPerfil');
            return;
        }

        // Atualiza o objeto com os novos dados
        $clienteAtual->setNome($_POST['nome']);
        $clienteAtual->setDtnasc($_POST['dtnasc']);
        $clienteAtual->setTelefone($_POST['telefone']);

        try {
            if ($clienteDAO->atualizar($clienteAtual)) {
                Sessao::gravaMensagem("Perfil atualizado com sucesso!");
            } else {
                // Embora o rowCount possa ser 0 se nenhum dado mudou,
                // tratamos como um aviso para o utilizador.
                Sessao::gravaMensagem("Nenhuma alteração foi detetada ou ocorreu um erro.");
            }
        } catch (\Exception $e) {
            error_log("Erro ao atualizar perfil do cliente: " . $e->getMessage());
            Sessao::gravaMensagem("Ocorreu um erro ao salvar as alterações.");
        }

        $this->redirect('/cliente/perfil');
    }

    /**
     * Lista todos os clientes. Acesso restrito a administradores.
     */
    public function listar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado. Você não tem permissão para acessar esta página.");
            $this->redirect('/home');
            return;
        }

        $clienteDAO = new ClienteDAO();
        $clientes = $clienteDAO->listar();

        $this->setViewParam('clientes', $clientes);
        $this->render('cliente/listar');
    }

    /**
     * Renderiza o formulário para editar um cliente. Acesso restrito a administradores.
     */
    public function editar($params)
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        $id = $params[0] ?? 0;
        $clienteDAO = new ClienteDAO();
        $cliente = $clienteDAO->buscar($id);

        if (!$cliente) {
            Sessao::gravaMensagem("Cliente não encontrado.");
            $this->redirect('/cliente/listar');
            return;
        }

        Sessao::limpaFormulario();
        $this->setViewParam('cliente', $cliente);
        $this->render('/cliente/editar');
    }

    /**
     * Processa a atualização de um cliente. Acesso restrito a administradores.
     */
    public function atualizar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }
        
        $cliente = new Cliente();
        $cliente->setId($_POST['id']);
        $cliente->setNome($_POST['nome']);
        $cliente->setDtnasc($_POST['dtnasc']);
        $cliente->setCpf($_POST['cpf']);
        $cliente->setTelefone($_POST['telefone']);
        
        $clienteDAO = new ClienteDAO();

        if ($clienteDAO->atualizar($cliente)) {
            Sessao::gravaMensagem("Cliente atualizado com sucesso!");
            $this->redirect('/cliente/listar');
        } else {
            Sessao::gravaMensagem("Erro ao atualizar cliente.");
            $this->redirect('/cliente/editar/' . $_POST['id']);
        }
    }

    /**
     * Exclui um cliente. Acesso restrito a administradores.
     */
    public function excluir($params)
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }
        
        $id = $params[0] ?? 0;
        $clienteDAO = new ClienteDAO();

        try {
            if ($clienteDAO->excluir($id)) {
                Sessao::gravaMensagem("Cliente excluído com sucesso.");
            } else {
                Sessao::gravaMensagem("Erro ao excluir cliente. Verifique se ele não possui registros associados.");
            }
        } catch (\Exception $e) {
             Sessao::gravaMensagem("Erro ao excluir cliente: " . $e->getMessage());
        }
        
        $this->redirect('/cliente/listar');
    }
}
