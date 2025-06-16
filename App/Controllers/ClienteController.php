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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function cadastro()
    {
        $this->render('cliente/cadastro');
    }

    public function salvar()
    {
        $dadosPost = $_POST;
        Sessao::gravaFormulario($dadosPost);

        $usuarioDAO = new UsuarioDAO();
        $clienteDAO = new ClienteDAO();

        $erros = [];

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
                throw new \Exception("Falha ao criar a conta de utilizador.");
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

    public function perfil()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'cliente') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        try {
            $clienteDAO = new ClienteDAO();
            $cliente = $clienteDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

            if ($cliente) {
                $this->setViewParam('cliente', $cliente);
                $this->render('cliente/perfil'); 
            } else {
                Sessao::gravaMensagem("Não foi possível encontrar os dados do seu perfil de cliente.");
                $this->redirect('/home');
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Ocorreu um erro ao carregar o seu perfil.");
            $this->redirect('/home');
        }
    }

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
                Sessao::limpaFormulario(); 
                $this->setViewParam('cliente', $cliente);
                $this->render('cliente/editarPerfil'); 
            } else {
                Sessao::gravaMensagem("Não foi possível encontrar o seu perfil para edição.");
                $this->redirect('/cliente/perfil');
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Ocorreu um erro ao carregar a página de edição.");
            $this->redirect('/cliente/perfil');
        }
    }

    public function atualizarPerfil()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'cliente') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/login');
            return;
        }

        $clienteDAO = new ClienteDAO();
        $clienteAtual = $clienteDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

        if (!$clienteAtual) {
            Sessao::gravaMensagem("O seu perfil não foi encontrado. A atualização falhou.");
            $this->redirect('/home');
            return;
        }

        if (empty($_POST['nome']) || empty($_POST['telefone'])) {
            Sessao::gravaMensagem("Nome e telefone são campos obrigatórios.");
            $this->redirect('/cliente/editarPerfil');
            return;
        }

        $clienteAtual->setNome($_POST['nome']);
        $clienteAtual->setDtnasc($_POST['dtnasc']);
        $clienteAtual->setTelefone($_POST['telefone']);

        try {
            if ($clienteDAO->atualizar($clienteAtual)) {
                Sessao::gravaMensagem("Perfil atualizado com sucesso!");
            } else {
                Sessao::gravaMensagem("Nenhuma alteração foi detectada.");
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Ocorreu um erro ao guardar as alterações.");
        }

        $this->redirect('/cliente/perfil');
    }

    public function listar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado. Não tem permissão para aceder a esta página.");
            $this->redirect('/home');
            return;
        }

        $clienteDAO = new ClienteDAO();
        $clientes = $clienteDAO->listar();

        $this->setViewParam('clientes', $clientes);
        $this->render('cliente/listar');
    }

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

    public function atualizar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }
        
        $clienteDAO = new ClienteDAO();
        $cliente = $clienteDAO->buscar($_POST['id']);

        if (!$cliente) {
             Sessao::gravaMensagem("Cliente não encontrado para atualização.");
            $this->redirect('/cliente/listar');
            return;
        }
        
        $cliente->setNome($_POST['nome']);
        $cliente->setDtnasc($_POST['dtnasc']);
        $cliente->setCpf($_POST['cpf']);
        $cliente->setTelefone($_POST['telefone']);
        
        try {
            if ($clienteDAO->atualizar($cliente)) {
                Sessao::gravaMensagem("Cliente atualizado com sucesso!");
            } else {
                Sessao::gravaMensagem("Nenhuma alteração foi efetuada.");
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Erro ao atualizar cliente: " . $e->getMessage());
        }
        
        $this->redirect('/cliente/listar');
    }

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
                Sessao::gravaMensagem("Erro ao excluir cliente.");
            }
        } catch (\Exception $e) {
             Sessao::gravaMensagem("Erro ao excluir cliente: " . $e->getMessage());
        }
        
        $this->redirect('/cliente/listar');
    }
}
