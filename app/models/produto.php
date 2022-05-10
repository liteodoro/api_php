<?php
class Produto
{
    public $id;
    public $nome;
    public $descricao;
    public $quant;
    public $data_alteracao;
    public $valor;
    public $largura;
    public $altura;
    public $comprimento;
    public $peso;
    public $fotos;
    public $ativo;


    public function popo($dados)
    {
        $this->id = $dados->id;
        $this->nome = $dados->nome;
        $this->descricao = $dados->descricao;
        $this->quant = $dados->quant;
        //$this->data_alteracao = $dados->data_alteracao;
        $this->valor = $dados->valor;
        $this->largura = $dados->largura;
        $this->altura = $dados->altura;
        $this->comprimento = $dados->comprimento;
        $this->peso = $dados->peso;
        $this->fotos = $dados->fotos;
        $this->ativo = $dados->ativo;
    }
}