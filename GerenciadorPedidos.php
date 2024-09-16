<?php

 class GerenciadorPedidos {
    private $cliente;
    private $itens = [];
    private $desconto = 0;

    public function __construct($cliente) {
        $this->cliente = $cliente;
    }

    public function adicionarItem($produto, $quantidade, $precoUnitario) {
        $this->itens[] = [
            'produto' => $produto,
            'quantidade' => $quantidade,
            'precoUnitario' => $precoUnitario
        ];
    }

    public function aplicarDesconto($codigo)
    {
        if ($codigo === 'DESC10') {
            $this->desconto = 0.10;
        } elseif ($codigo === 'DESC20') {
            $this->desconto = 0.20;
        } else {
            throw new InvalidArgumentException('Código de desconto inválido');
        }
    }

    public function calcularTotal()
    {
        $total = 0;
        foreach ($this->itens as $item) {
            $total += $item['quantidade'] * $item['precoUnitario'];
        }
        if (isset($this->desconto)) {
            $total -= $total * $this->desconto;
        }
        return $total;
    }


    public function validarPedido() {
        if (empty($this->itens)) {
            throw new RuntimeException("O pedido não contém itens.");
        }
        if ($this->calcularTotal() <= 0) {
            throw new RuntimeException("O total do pedido é inválido.");
        }
    }

    public function confirmarPedido() {
        $this->validarPedido();
        return true;
    }

    public function listarItens() {
        return $this->itens;
    }
}
