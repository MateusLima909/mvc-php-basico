<?php

namespace App\Controllers;

use App\Lib\Sessao;
use App\Models\DAO\FornecedorDAO;
use App\Models\Entidades\Fornecedor;

class FornecedorController extends Controller
{
    public function cadastro()
    {
        $this->render('/fornecedor/cadastro');

        Sessao::limpaFormulario();
        Sessao::limpaMensagem();
    }

    public function salvar()
    {
        $Fornecedor = new Cliente();
        $Fornecedor->setId($_POST['id']);
        $Fornecedor->setNome($_POST['nome']);
        $Fornecedor->setNomeFantasia($_POST['nomeFantasia']);
        $Fornecedor->setCnpj($_POST['cnpj']);
        $Fornecedor->setInscricaoEstadual($_POST['inscricaoEstadual']);
        $Fornecedor->setEndereco($_POST['endereco']);
        $Fornecedor->setTipoDeServico($_POST['tipoDeServico']);
        $Fornecedor->setTelefone($_POST['telefone']);

        Sessao::gravaFormulario($_POST);

        $fornecedorDAO = new FornecedorDAO();

        if($fornecedorDAO->verificaTelefone($_POST['telefone'])){
            Sessao::gravaMensagem("Telefone existente");
            $this->redirect('/fornecedor/cadastro');
        }

        if($fornecedorDAO->salvar($Fornecedor)){
            $this->redirect('/fornecedor/sucesso');
        }else{
            Sessao::gravaMensagem("Erro ao gravar");
        }
    }
    
    public function sucesso()
    {
        if(Sessao::retornaValorFormulario('nome')) {
            $this->render('/fornecedor/sucesso');

            Sessao::limpaFormulario();
            Sessao::limpaMensagem();
        }else{
            $this->redirect('/');
        }
    }

    public function index()
    {
        $this->redirect('/fornecedor/cadastro');
    }

}