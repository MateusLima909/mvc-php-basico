<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\UsuarioDAO;
use App\Models\Entidades\Usuario;

class LoginController extends Controller
{
    private $usuarioDAO;

    public function __construct($app)
    {
        parent::__construct($app);

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function index()
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('/usuario/cadastro'); // REDIRECIONAMENTO TEMPORÁRIO PARA TESTE
        }
        $this->render('login/index'); 
    }

    public function autenticar()
    {
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('/usuario/cadastro'); // REDIRECIONAMENTO TEMPORÁRIO PARA TESTE
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = $_POST['email'] ?? null;
        $senha_digitada = $_POST['senha'] ?? null;

        if (empty($email) || empty($senha_digitada)) {
            Sessao::gravaMensagem("E-mail e senha são obrigatórios.");
            $this->redirect('/login');
        }

        try {
            $usuario = $this->usuarioDAO->buscarPorEmail($email);

            if ($usuario && password_verify($senha_digitada, $usuario->getSenha())) {
                $_SESSION['usuario_id']    = $usuario->getId();
                $_SESSION['usuario_nome']  = $usuario->getNome();
                $_SESSION['usuario_nivel'] = $usuario->getNivelAcesso();

                Sessao::limpaMensagem();
                Sessao::limpaFormulario();

                $this->redirect('/usuario/cadastro'); // REDIRECIONAMENTO TEMPORÁRIO PARA TESTE

            } else {
                Sessao::gravaMensagem("E-mail ou senha inválidos.");
                Sessao::gravaFormulario(['email_login' => $email]);
                $this->redirect('/login');
            }

        } catch (\Exception $e) {
            error_log("Erro na autenticação: " . $e->getMessage());
            Sessao::gravaMensagem("Ocorreu um erro no sistema. Tente novamente mais tarde.");
            $this->redirect('/login');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        
        $this->redirect('/login');
    }
}
