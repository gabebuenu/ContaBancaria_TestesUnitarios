<?php

class ContaBancaria {
    private $cliente;
    private $historicoTransacoes;
    private $saldo;

    public function __construct($cliente, $saldo) {
        $this->cliente = $cliente;
        $this->saldo = $saldo;
        $this->historicoTransacoes = [];
    }

    public function getHistoricoTransacoes() {
        return $this->historicoTransacoes;
    }

    public function transferir($valor, ContaBancaria $contaDestino) {
        if ($this->saldo >= $valor) {
            $this->saldo -= $valor;
            $contaDestino->deposito($valor);
            $this->addTransacao('transferência enviada', $valor);
            $contaDestino->addTransacao('transferência recebida', $valor);
        } else {
            echo "Saldo insuficiente para transferência.";
        }
    }

    public function saque($valor) {
        if ($valor <= 0) {
            echo "Valor inválido para saque.";
            return;
        }

        if ($this->saldo >= $valor) {
            $this->saldo -= $valor;
            $this->addTransacao('saque', $valor);
        } else {
            echo "Saldo insuficiente.";
        }
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function calcularJuros($taxa) {
        if ($taxa <= 0) {
            echo "Taxa inválida.";
            return;
        }
        $juros = $this->saldo * ($taxa / 100);
        $this->saldo += $juros;
        $this->addTransacao('juros adicionados', $juros);
    }

    private function addTransacao($tipo, $valor) {
        $this->historicoTransacoes[] = [
            'tipo' => $tipo,
            'valor' => $valor,
            'data' => date('Y-m-d H:i:s')
        ];
    }

    public function deposito($valor) {
        if ($valor <= 0) {
            echo "Valor inválido para depósito.";
            return;
        }

        $this->saldo += $valor;
        $this->addTransacao('depósito', $valor);
    }

    public function getCliente() {
        return $this->cliente;
    }
}
