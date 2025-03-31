<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\ClienteDAO;
use App\Models\Entidades\Cliente;

class ClienteController extends Controller
{
    public function cadastro()
    {
        $this->render('/cliente/cadastro');

        Sessao::limpaFormulario();
        Sessao::limpaMensagem();
    }

    public function salvar()
    {
        $Cliente = new Cliente();
        $Cliente->setId($_POST['id']);
        $Cliente->setNome($_POST['nome']);
        $Cliente->setDtnasc($_POST['dtnasc']);
        $Cliente->setCpf($_POST['cpf']);
        $Cliente->setTelefone($_POST['telefone']);
        
        Sessao::gravaFormulario($_POST);

        $clienteDAO = new ClienteDAO();

        if($clienteDAO->verificaTelefone($_POST['telefone'])){
            Sessao::gravaMensagem("telefone existente");
            $this->redirect('/cliente/cadastro');
        }

        if($clienteDAO->salvar($Cliente)){
            $this->redirect('/cliente/sucesso');
        }else{
            Sessao::gravaMensagem("Erro ao gravar");
        }
    }
    
    public function sucesso()
    {
        if(Sessao::retornaValorFormulario('nome')) {
            $this->render('/cliente/sucesso');

            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
        }else{
            $this->redirect('/');
        }
    }

    public function index()
    {
        $this->redirect('/cliente/cadastro');
    }

}