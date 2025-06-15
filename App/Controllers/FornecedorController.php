<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\UsuarioDAO;
use App\Models\DAO\FornecedorDAO;
use App\Models\Entidades\Usuario;
use App\Models\Entidades\Fornecedor;

class FornecedorController extends Controller
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
        Sessao::limpaFormulario();
        Sessao::limpaMensagem();
        $this->render('fornecedor/cadastro');
    }

    public function salvar()
    {
        $dadosPost = $_POST;
        Sessao::gravaFormulario($dadosPost);

        $usuarioDAO = new UsuarioDAO();
        $fornecedorDAO = new FornecedorDAO();

        $erros = [];

        if (empty($dadosPost['nome'])) $erros[] = "O campo Nome é obrigatório.";
        if (empty($dadosPost['cnpj'])) $erros[] = "O campo CNPJ é obrigatório.";
        if (empty($dadosPost['email'])) $erros[] = "O campo Email é obrigatório.";
        if (!filter_var($dadosPost['email'], FILTER_VALIDATE_EMAIL)) $erros[] = "O formato do e-mail é inválido.";
        if (empty($dadosPost['senha'])) $erros[] = "O campo Senha é obrigatório.";
        if ($dadosPost['senha'] !== $dadosPost['confirmar_senha']) $erros[] = "As senhas não coincidem.";
        
        if (empty($erros)) {
            if ($usuarioDAO->verificaEmail($dadosPost['email'])) $erros[] = "Este e-mail já está em uso.";
            if ($fornecedorDAO->verificaCnpj($dadosPost['cnpj'])) $erros[] = "Este CNPJ já está registado.";
        }

        if (!empty($erros)) {
            Sessao::gravaMensagem(implode("<br>", $erros));
            $this->redirect('/fornecedor/cadastro');
            return;
        }

        try {
            $novoUsuario = new Usuario();
            $novoUsuario->setNome($dadosPost['nome']);
            $novoUsuario->setEmail($dadosPost['email']);
            $novoUsuario->setSenha(password_hash($dadosPost['senha'], PASSWORD_DEFAULT));
            $novoUsuario->setNivelAcesso('fornecedor');

            $idUsuarioNovo = $usuarioDAO->salvar($novoUsuario);
            
            if (!$idUsuarioNovo) {
                throw new \Exception("Falha ao criar a conta de utilizador.");
            }

            $novoFornecedor = new Fornecedor();
            $novoFornecedor->setNome($dadosPost['nome']);
            $novoFornecedor->setNomeFantasia($dadosPost['nomeFantasia']);
            $novoFornecedor->setCnpj($dadosPost['cnpj']);
            $novoFornecedor->setInscricaoEstadual($dadosPost['inscricaoEstadual']);
            $novoFornecedor->setEndereco($dadosPost['endereco']);
            $novoFornecedor->setTipoDeServico($dadosPost['tipoDeServico']);
            $novoFornecedor->setTelefone($dadosPost['telefone']);
            $novoFornecedor->setIdUsuario($idUsuarioNovo);

            $fornecedorDAO->salvar($novoFornecedor);
            
            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
            Sessao::gravaMensagem("Cadastro realizado com sucesso! Faça o seu login.");
            $this->redirect('/login');

        } catch (\Exception $e) {
            error_log("Erro no cadastro de fornecedor: " . $e->getMessage());
            Sessao::gravaMensagem("Erro ao realizar o cadastro. Tente novamente.");
            $this->redirect('/fornecedor/cadastro');
        }
    }

    public function painel()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'fornecedor') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        try {
            $fornecedorDAO = new FornecedorDAO();
            $fornecedor = $fornecedorDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

            if ($fornecedor) {
                $this->setViewParam('fornecedor', $fornecedor);
                $this->render('fornecedor/painel');
            } else {
                Sessao::gravaMensagem("Não foi possível encontrar os dados do seu perfil de fornecedor.");
                $this->redirect('/home');
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Ocorreu um erro ao carregar o seu painel.");
            $this->redirect('/home');
        }
    }

    public function editarPainel()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'fornecedor') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/login');
            return;
        }

        try {
            $fornecedorDAO = new FornecedorDAO();
            $fornecedor = $fornecedorDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

            if ($fornecedor) {
                Sessao::limpaFormulario();
                $this->setViewParam('fornecedor', $fornecedor);
                $this->render('fornecedor/editarPainel');
            } else {
                Sessao::gravaMensagem("Não foi possível encontrar seu perfil para edição.");
                $this->redirect('/fornecedor/painel');
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Ocorreu um erro ao carregar a página de edição.");
            $this->redirect('/fornecedor/painel');
        }
    }

    public function atualizarPainel()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'fornecedor') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/login');
            return;
        }

        $fornecedorDAO = new FornecedorDAO();
        $fornecedorAtual = $fornecedorDAO->buscarPorIdUsuario($_SESSION['usuario_id']);

        if (!$fornecedorAtual) {
            Sessao::gravaMensagem("O seu perfil não foi encontrado. A atualização falhou.");
            $this->redirect('/home');
            return;
        }

        $fornecedorAtual->setNome($_POST['nome']);
        $fornecedorAtual->setNomeFantasia($_POST['nomeFantasia']);
        $fornecedorAtual->setInscricaoEstadual($_POST['inscricaoEstadual']);
        $fornecedorAtual->setEndereco($_POST['endereco']);
        $fornecedorAtual->setTipoDeServico($_POST['tipoDeServico']);
        $fornecedorAtual->setTelefone($_POST['telefone']);

        try {
            $fornecedorDAO->atualizar($fornecedorAtual);
            Sessao::gravaMensagem("Painel atualizado com sucesso!");
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Ocorreu um erro ao salvar as alterações.");
        }

        $this->redirect('/fornecedor/painel');
    }

    public function listar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        $fornecedorDAO = new FornecedorDAO();
        $fornecedores = $fornecedorDAO->listar();

        $this->setViewParam('fornecedores', $fornecedores);
        $this->render('fornecedor/listar');
    }

    public function editar($params)
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        $id = $params[0] ?? 0;
        $fornecedorDAO = new FornecedorDAO();
        $fornecedor = $fornecedorDAO->buscar($id);

        if (!$fornecedor) {
            Sessao::gravaMensagem("Fornecedor não encontrado.");
            $this->redirect('/fornecedor/listar');
            return;
        }

        $this->setViewParam('fornecedor', $fornecedor);
        $this->render('fornecedor/editar');
    }

    public function atualizar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }
        
        $fornecedorDAO = new FornecedorDAO();
        $fornecedor = $fornecedorDAO->buscar($_POST['id']);
        
        if(!$fornecedor){
            Sessao::gravaMensagem("Fornecedor não encontrado para atualização.");
            $this->redirect('/fornecedor/listar');
            return;
        }
        
        $fornecedor->setNome($_POST['nome']);
        $fornecedor->setNomeFantasia($_POST['nomeFantasia']);
        $fornecedor->setInscricaoEstadual($_POST['inscricaoEstadual']);
        $fornecedor->setEndereco($_POST['endereco']);
        $fornecedor->setTipoDeServico($_POST['tipoDeServico']);
        $fornecedor->setTelefone($_POST['telefone']);

        try {
            if ($fornecedorDAO->atualizar($fornecedor)) {
                Sessao::gravaMensagem("Fornecedor atualizado com sucesso!");
            } else {
                Sessao::gravaMensagem("Nenhuma alteração foi efetuada.");
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Erro ao atualizar fornecedor: " . $e->getMessage());
        }
        
        $this->redirect('/fornecedor/listar');
    }

    public function excluir($params)
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }
        
        $id = $params[0] ?? 0;
        $fornecedorDAO = new FornecedorDAO();

        try {
            if ($fornecedorDAO->excluir($id)) {
                Sessao::gravaMensagem("Fornecedor excluído com sucesso.");
            } else {
                Sessao::gravaMensagem("Erro ao excluir fornecedor.");
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Erro ao excluir fornecedor: " . $e->getMessage());
        }
        
        $this->redirect('/fornecedor/listar');
    }
}
