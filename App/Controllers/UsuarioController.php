<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\UsuarioDAO;
use App\Models\Entidades\Usuario;

class UsuarioController extends Controller
{
    public function __construct($app)
    {
        parent::__construct($app);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        $this->redirect('/usuario/listarGeral');
    }

    public function listarGeral()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado. Esta área é restrita a administradores.");
            $this->redirect('/home');
            return;
        }

        $usuarioDAO = new UsuarioDAO();
        $usuarios = $usuarioDAO->listarUsuariosDetalhados();

        $this->setViewParam('usuariosDetalhados', $usuarios);
        $this->render('usuario/listarGeral');
    }

    public function editar($params)
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        $id = $params[0] ?? 0;
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscar($id);

        if (!$usuario) {
            Sessao::gravaMensagem("Utilizador não encontrado.");
            $this->redirect('/usuario/listarGeral');
            return;
        }

        $this->setViewParam('usuario', $usuario);
        $this->render('/usuario/editar');
    }

    public function atualizar()
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscar($_POST['id']);

        if (!$usuario) {
            Sessao::gravaMensagem("Utilizador não encontrado para atualização.");
            $this->redirect('/usuario/listarGeral');
            return;
        }

        $usuario->setNome($_POST['nome']);
        $usuario->setEmail($_POST['email']);
        $usuario->setNivelAcesso($_POST['nivel_acesso']);

        if (!empty($_POST['senha'])) {
            $usuario->setSenha(password_hash($_POST['senha'], PASSWORD_DEFAULT));
        }

        try {
            if ($usuarioDAO->atualizar($usuario)) {
                Sessao::gravaMensagem("Utilizador atualizado com sucesso!");
            } else {
                Sessao::gravaMensagem("Nenhuma alteração foi efetuada.");
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Erro ao atualizar utilizador: " . $e->getMessage());
        }
        
        $this->redirect('/usuario/listarGeral');
    }

    public function excluir($params)
    {
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_nivel'] !== 'admin') {
            Sessao::gravaMensagem("Acesso negado.");
            $this->redirect('/home');
            return;
        }

        $id = $params[0] ?? 0;

        if ($id == $_SESSION['usuario_id']) {
            Sessao::gravaMensagem("Operação inválida. Não é possível excluir a sua própria conta de administrador.");
            $this->redirect('/usuario/listarGeral');
            return;
        }
        
        $usuarioDAO = new UsuarioDAO();

        try {
            if ($usuarioDAO->excluir($id)) {
                Sessao::gravaMensagem("Utilizador excluído com sucesso.");
            } else {
                Sessao::gravaMensagem("Erro ao excluir utilizador.");
            }
        } catch (\Exception $e) {
            Sessao::gravaMensagem("Erro ao excluir utilizador: " . $e->getMessage());
        }
        
        $this->redirect('/usuario/listarGeral');
    }
}
