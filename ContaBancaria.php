<?php

class ContaBancaria {
    private $cliente;
    private $saldo;
    private $historicoTransacoes = [];

    public function __construct($cliente, $saldoInicial) {
        $this->cliente = $cliente;
        $this->saldo = $saldoInicial;
    }

    public function depositar($valor) {
        if ($valor <= 0) {
            throw new Exception("Valor inválido para depósito.");
        }
        $this->saldo += $valor;
        $this->addTransacao('Depósito', $valor);
    }

    public function sacar($valor) {
        if ($valor <= 0) {
            throw new Exception("Valor inválido para saque.");
        }
        if ($valor > $this->saldo) {
            throw new Exception("Saldo insuficiente.");
        }
        $this->saldo -= $valor;
        $this->addTransacao('Saque', $valor);
    }

    public function transferir($valor, ContaBancaria $contaDestino) {
        if ($valor > $this->saldo) {
            throw new Exception("Saldo insuficiente para transferência.");
        }
        $this->sacar($valor);
        $contaDestino->depositar($valor);
        $this->addTransacao('Transferência Enviada', $valor);
        $contaDestino->addTransacao('Transferência Recebida', $valor);
    }

    public function calcularJuros($taxa) {
        if ($taxa <= 0) {
            throw new Exception("Taxa inválida.");
        }
        $juros = ($this->saldo * $taxa) / 100;
        $this->saldo += $juros;
        $this->addTransacao('Juros', $juros);
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getHistoricoTransacoes() {
        return $this->historicoTransacoes;
    }

    private function addTransacao($tipo, $valor) {
        $this->historicoTransacoes[] = ['tipo' => $tipo, 'valor' => $valor, 'data' => date('Y-m-d H:i:s')];
    }
}
