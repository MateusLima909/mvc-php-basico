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
        $Cliente->setNome($_POST['nome']);
        $Cliente->setDtnasc($_POST['dtnasc']);
        $Cliente->setCpf($_POST['cpf']);
        $Cliente->setTelefone($_POST['telefone']);
        
        Sessao::gravaFormulario($_POST);

        $clienteDAO = new ClienteDAO();

        if($clienteDAO->verificaCpf($_POST['cpf'])){
            Sessao::gravaMensagem("CPF existente");
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

    public function listar()
    {
        $clienteDAO = new ClienteDAO();
        $cliente = $clienteDAO -> listar();

        $this->setViewParam('cliente', $cliente);
        $this->render('/cliente/listar');
    }

    public function editar($id)
    {
        $clienteDAO = new ClienteDAO();
        $cliente = $clienteDAO->buscar($id);
    
        if ($cliente) {
            $this->setViewParam('cliente', $cliente);
            $this->render('/cliente/editar');
        } else {
            Sessao::gravaMensagem("Cliente não encontrado.");
            $this->redirect('/cliente/listar');
        }
    }

    public function excluir($id)
    {
        $clienteDAO = new ClienteDAO();

        if ($clienteDAO->excluir($id)) {
            Sessao::gravaMensagem("Usuário excluído com sucesso");
        } else {
            Sessao::gravaMensagem("Erro ao excluir usuário");
        }
        $this->redirect('/cliente/listar');
    }

    public function atualizar()
    {
    $cliente = new Cliente();
    $cliente->setId($_POST['id']);
    $cliente->setNome($_POST['nome']);
    $cliente->setDtnasc($_POST['dtnasc']);
    $cliente->setCpf($_POST['cpf']);
    $cliente->setTelefone($_POST['telefone']);

    $clienteDAO = new ClienteDAO();

    if ($clienteDAO->atualizar($cliente)) {
        Sessao::gravaMensagem("Cliente atualizado com sucesso!");
    } else {
        Sessao::gravaMensagem("Erro ao atualizar cliente.");
        $this->redirect('/cliente/editar/' . $_POST['id']);
    }
    $this->redirect('/cliente/listar');
}

}
