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
        $Fornecedor = new Fornecedor();
        $Fornecedor->setNome($_POST['nome']);
        $Fornecedor->setNomeFantasia($_POST['nomeFantasia']);
        $Fornecedor->setCnpj($_POST['cnpj']);
        $Fornecedor->setInscricaoEstadual($_POST['inscricaoEstadual']);
        $Fornecedor->setEndereco($_POST['endereco']);
        $Fornecedor->setTipoDeServico($_POST['tipoDeServico']);
        $Fornecedor->setTelefone($_POST['telefone']);

        Sessao::gravaFormulario($_POST);

        $fornecedorDAO = new FornecedorDAO();

        if($fornecedorDAO->verificaCnpj($_POST['cnpj'])){
            Sessao::gravaMensagem("CNPJ existente");
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

    public function listar()
    {

        if (!isset($_SESSION['usuario_id'])) {
            Sessao::gravaMensagem("Você precisa estar logado para ver a lista de fornecedores.");
            $this->redirect('/login');
        }

        if ($_SESSION['usuario_nivel'] !== 'admin') {
             Sessao::gravaMensagem("Você não tem permissão para acessar esta página.");
             $this->redirect('/home'); 
        }

        $fornecedorDAO = new FornecedorDAO();
        $fornecedor = $fornecedorDAO -> listar();

        $this->setViewParam('fornecedor', $fornecedor);
        $this->render('/fornecedor/listar');
    }

    public function editar($id)
    {
        $fornecedorDAO = new FornecedorDAO();
        $fornecedor = $fornecedorDAO->buscar($id);
    
        if ($fornecedor) {
            $this->setViewParam('fornecedor', $fornecedor);
            $this->render('/fornecedor/editar');
        } else {
            Sessao::gravaMensagem("Fornecedor não encontrado.");
            $this->redirect('/fornecedor/listar');
        }
    }

    public function excluir($id)
    {
        $fornecedorDAO = new FornecedorDAO();

        if ($fornecedorDAO->excluir($id)) {
            Sessao::gravaMensagem("Fornecedor excluído com sucesso");
        } else {
            Sessao::gravaMensagem("Erro ao excluir fornecedor");
        }
        $this->redirect('/fornecedor/listar');
    }

    public function atualizar()
    {
        $fornecedor = new Fornecedor();
        $fornecedor->setId($_POST['id']);
        $fornecedor->setNome($_POST['nome']);
        $fornecedor->setNomeFantasia($_POST['nomeFantasia']);
        $fornecedor->setCnpj($_POST['cnpj']);
        $fornecedor->setInscricaoEstadual($_POST['inscricaoEstadual']);
        $fornecedor->setEndereco($_POST['endereco']);
        $fornecedor->setTipoDeServico($_POST['tipoDeServico']);
        $fornecedor->setTelefone($_POST['telefone']);
    
        $fornecedorDAO = new FornecedorDAO();
    
        if ($fornecedorDAO->atualizar($fornecedor)) {
            Sessao::gravaMensagem("Fornecedor atualizado com sucesso!");
        } else {
            Sessao::gravaMensagem("Erro ao atualizar fornecedor.");
            $this->redirect('/fornecedor/editar/' . $_POST['id']);
            return;
        }
    
        $this->redirect('/fornecedor/listar');
    }
}