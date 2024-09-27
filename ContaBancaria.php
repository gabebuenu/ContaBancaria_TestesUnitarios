<?php

class ContaBancaria {
    private $nomeCliente;
    private $transacoes;
    private $saldo;

    public function __construct($nomeCliente, $saldoInicial) {
        $this->nomeCliente = $nomeCliente;
        $this->saldo = $saldoInicial;
        $this->transacoes = [];
    }

    public function getTransacoes() {
        return $this->transacoes;
    }

    public function transferir($valor, ContaBancaria $contaDestino) {
        if ($this->saldo >= $valor) {
            $this->saldo -= $valor;
            $contaDestino->depositar($valor);
            $this->registrarTransacao('Transferência enviada', $valor);
            $contaDestino->registrarTransacao('Transferência recebida', $valor);
        } else {
            echo "Saldo insuficiente para transferência.";
        }
    }

    public function sacar($valor) {
        if ($valor <= 0) {
            echo "Valor inválido para saque.";
            return;
        }

        if ($this->saldo >= $valor) {
            $this->saldo -= $valor;
            $this->registrarTransacao('Saque', $valor);
        } else {
            echo "Saldo insuficiente.";
        }
    }

    public function getSaldo() {
        return $this->saldo;
    }

    public function aplicarJuros($taxaPercentual) {
        if ($taxaPercentual <= 0) {
            echo "Taxa inválida.";
            return;
        }
        $juros = $this->saldo * ($taxaPercentual / 100);
        $this->saldo += $juros;
        $this->registrarTransacao('Juros adicionados', $juros);
    }

    private function registrarTransacao($descricao, $valor) {
        $this->transacoes[] = [
            'descricao' => $descricao,
            'valor' => $valor,
            'data' => date('Y-m-d H:i:s')
        ];
    }

    public function depositar($valor) {
        if ($valor <= 0) {
            echo "Valor inválido para depósito.";
            return;
        }

        $this->saldo += $valor;
        $this->registrarTransacao('Depósito', $valor);
    }

    public function getNomeCliente() {
        return $this->nomeCliente;
    }
}
