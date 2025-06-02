<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\UsuarioDAO;
use App\Models\Entidades\Usuario;

class UsuarioController extends Controller
{
    
    public function cadastro()
    {
        $this->render('/usuario/cadastro');

        Sessao::limpaFormulario();
        Sessao::limpaMensagem();
    }

    public function salvar()
    {
        $Usuario = new Usuario();
        $Usuario->setNome($_POST['nome']);
        $Usuario->setEmail($_POST['email']);
        $Usuario->setSenha(password_hash($_POST['senha'], PASSWORD_DEFAULT));
        $Usuario->setNivelAcesso('usuario');

        Sessao::gravaFormulario($_POST);

        $usuarioDAO = new UsuarioDAO();

        if($usuarioDAO->verificaEmail($_POST['email'])){
            Sessao::gravaMensagem("Email existente");
            $this->redirect('/usuario/cadastro');
        }

        if($usuarioDAO->salvar($Usuario)){
            $this->redirect('/usuario/sucesso');
        }else{
            Sessao::gravaMensagem("Erro ao gravar");
        }
    }
    
    public function sucesso()
    {
        if(Sessao::retornaValorFormulario('nome')) {
            $this->render('/usuario/sucesso');

            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
        }else{
            $this->redirect('/');
        }
    }

    public function index()
    {
        $this->redirect('/usuario/cadastro');
    }

    
    public function listar()
    {
        $usuarioDAO = new UsuarioDAO();
        $usuarios = $usuarioDAO -> listar();

        $this->setViewParam('usuarios', $usuarios);
        $this->render('/usuario/listar');
    }

    public function editar($id)
    {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->buscar($id);
    
        if ($usuario) {
            $this->setViewParam('usuario', $usuario);
            $this->render('/usuario/editar');
        } else {
            Sessao::gravaMensagem("Usuário não encontrado.");
            $this->redirect('/usuario/listar');
        }
    }

    public function excluir($id)
    {
        $usuarioDAO = new UsuarioDAO();

        if ($usuarioDAO->excluir($id)) {
            Sessao::gravaMensagem("Usuário excluído com sucesso");
        } else {
            Sessao::gravaMensagem("Erro ao excluir usuário");
        }
        $this->redirect('/usuario/listar');
    }

    public function atualizar()
    {
    $usuario = new Usuario();
    $usuario->setId($_POST['id']);
    $usuario->setNome($_POST['nome']);
    $usuario->setEmail($_POST['email']);

    $usuarioDAO = new UsuarioDAO();

    if ($usuarioDAO->atualizar($usuario)) {
        Sessao::gravaMensagem("Usuário atualizado com sucesso!");
    } else {
        Sessao::gravaMensagem("Erro ao atualizar usuário.");
        $this->redirect('/usuario/editar/' . $_POST['id']);
    }
    $this->redirect('/usuario/listar');
}
}