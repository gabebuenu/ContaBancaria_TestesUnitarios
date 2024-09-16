<?php
require_once "GerenciadorPedidos.php";
class GerenciadorPedidosTests extends \PHPUnit\Framework\TestCase {

public function testAdicionarItem() {
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->adicionarItem('Produto 1', 2, 100);
    $itens = $pedido->listarItens();

    $this->assertCount(1, $itens);
    $this->assertEquals('Produto 1', $itens[0]['produto']);
    $this->assertEquals(2, $itens[0]['quantidade']);
    $this->assertEquals(100, $itens[0]['precoUnitario']);
}

public function testAplicarDescontoValido()
{
    $pedido = new GerenciadorPedidos('Cliente X');
    $pedido->adicionarItem('Produto A', 1, 100); 
    $pedido->aplicarDesconto('DESC10'); 
    $this->assertEquals(90, $pedido->calcularTotal()); 
}


public function testAplicarDescontoInvalido() {
    $this->expectException(InvalidArgumentException::class);
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->aplicarDesconto('DESC50');
}

public function testCalcularTotalSemDesconto() {
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->adicionarItem('Produto 1', 2, 100);
    $this->assertEquals(200, $pedido->calcularTotal());
}

public function testCalcularTotalComDesconto() {
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->adicionarItem('Produto 1', 2, 100);
    $pedido->aplicarDesconto('DESC10');
    $this->assertEquals(180, $pedido->calcularTotal());
}

public function testValidarPedidoValido() {
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->adicionarItem('Produto 1', 1, 100);
    $pedido->validarPedido();
    $this->assertTrue(true);  
}

public function testValidarPedidoSemItens() {
    $this->expectException(RuntimeException::class);
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->validarPedido();
}

public function testValidarPedidoTotalNegativo() {
    $this->expectException(RuntimeException::class);
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->adicionarItem('Produto 1', 1, -100);
    $pedido->validarPedido();
}

public function testConfirmarPedidoValido() {
    $pedido = new GerenciadorPedidos('Cliente 1');
    $pedido->adicionarItem('Produto 1', 1, 100);
    $this->assertTrue($pedido->confirmarPedido());
}
}
