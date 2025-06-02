<?php

namespace App\Lib;

class Sessao
{
    /**
     * Garante que a sessão PHP seja iniciada.
     * Este método pode ser chamado no construtor de controllers base ou no início da aplicação.
     */
    public static function iniciar()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function gravaMensagem($mensagem)
    {
        self::iniciar(); // Garante que a sessão está ativa
        $_SESSION['mensagem'] = $mensagem;
    }

    public static function limpaMensagem()
    {
        self::iniciar();
        unset($_SESSION['mensagem']);
    }

    public static function retornaMensagem()
    {
        self::iniciar();
        return (isset($_SESSION['mensagem'])) ? $_SESSION['mensagem'] : "";
    }

    /**
     * Verifica se existe alguma mensagem gravada na sessão.
     * @return bool True se existir uma mensagem, false caso contrário.
     */
    public static function existeMensagem()
    {
        self::iniciar();
        return isset($_SESSION['mensagem']);
    }

    public static function gravaFormulario($form)
    {
        self::iniciar();
        $_SESSION['form'] = $form;
    }

    public static function limpaFormulario()
    {
        self::iniciar();
        unset($_SESSION['form']);
    }

    public static function retornaValorFormulario($key)
    {
        self::iniciar();
        return (isset($_SESSION['form'][$key])) ? $_SESSION['form'][$key] : "";
    }

    /**
     * Verifica se existe algum formulário gravado na sessão.
     * @return bool True se existir um formulário, false caso contrário.
     */
    public static function existeFormulario()
    {
        self::iniciar();
        return isset($_SESSION['form']);
    }

    // Você também pode querer adicionar métodos para o usuário logado aqui,
    // para encapsular o acesso a $_SESSION['usuario_id'], etc.
    // Exemplo:
    /*
    public static function gravaUsuarioLogado($id, $nome, $nivel)
    {
        self::iniciar();
        $_SESSION['usuario_id']    = $id;
        $_SESSION['usuario_nome']  = $nome;
        $_SESSION['usuario_nivel'] = $nivel;
    }

    public static function limpaUsuarioLogado()
    {
        self::iniciar();
        unset($_SESSION['usuario_id']);
        unset($_SESSION['usuario_nome']);
        unset($_SESSION['usuario_nivel']);
        // session_destroy(); // Cuidado: isso destrói TUDO na sessão, incluindo mensagens.
                           // Use session_unset() se quiser limpar todas as variáveis da sessão atual.
    }

    public static function getUsuarioId()
    {
        self::iniciar();
        return $_SESSION['usuario_id'] ?? null;
    }

    public static function getUsuarioNome()
    {
        self::iniciar();
        return $_SESSION['usuario_nome'] ?? null;
    }

    public static function getUsuarioNivel()
    {
        self::iniciar();
        return $_SESSION['usuario_nivel'] ?? null;
    }

    public static function estaLogado()
    {
        self::iniciar();
        return isset($_SESSION['usuario_id']);
    }
    */
}
