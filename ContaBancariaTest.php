<?php

require_once 'ContaBancaria.php';
use PHPUnit\Framework\TestCase;

class ContaBancariaTest extends TestCase {
    private $conta1;
    private $conta2;

    protected function setUp(): void {
        $this->conta1 = new ContaBancaria('Cliente 1', 1000);
        $this->conta2 = new ContaBancaria('Cliente 2', 500);
    }

    public function testeDepositar() {
        $this->conta1->depositar(500);
        $this->assertEquals(1500, $this->conta1->getSaldo());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Valor inválido para depósito.");
        $this->conta1->depositar(-100);

        $historico = $this->conta1->getHistoricoTransacoes();
        $this->assertCount(1, $historico);
        $this->assertEquals('Depósito', $historico[0]['tipo']);
        $this->assertEquals(500, $historico[0]['valor']);
    }

    public function testeSacar() {
        $this->conta1->sacar(200);
        $this->assertEquals(800, $this->conta1->getSaldo());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Saldo insuficiente.");
        $this->conta1->sacar(1000);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Valor inválido para saque.");
        $this->conta1->sacar(-50);

        $historico = $this->conta1->getHistoricoTransacoes();
        $this->assertCount(2, $historico); 
        $this->assertEquals('Saque', $historico[1]['tipo']);
        $this->assertEquals(200, $historico[1]['valor']);
    }

    public function testeTransferir() {
        $this->conta1->transferir(200, $this->conta2);
        $this->assertEquals(800, $this->conta1->getSaldo());
        $this->assertEquals(700, $this->conta2->getSaldo());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Saldo insuficiente para transferência.");
        $this->conta1->transferir(1000, $this->conta2);

        $historico1 = $this->conta1->getHistoricoTransacoes();
        $historico2 = $this->conta2->getHistoricoTransacoes();
        $this->assertCount(1, $historico1); 
        $this->assertCount(1, $historico2); 
    }

    public function testeCalcularJuros() {
        $this->conta1->calcularJuros(10);
        $this->assertEquals(1100, $this->conta1->getSaldo());

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Taxa inválida.");
        $this->conta1->calcularJuros(-5);

        $historico = $this->conta1->getHistoricoTransacoes();
        $this->assertCount(1, $historico);
        $this->assertEquals('Juros', $historico[0]['tipo']);
        $this->assertEquals(100, $historico[0]['valor']);
    }

    public function testeGetSaldo() {
        $this->assertEquals(1000, $this->conta1->getSaldo());
        $this->conta1->depositar(500);
        $this->assertEquals(1500, $this->conta1->getSaldo());
    }

    public function testeGetCliente() {
        $this->assertEquals('Cliente 1', $this->conta1->getCliente());
    }

    public function testeGetHistoricoTransacoes() {
        $this->conta1->depositar(200);
        $this->conta1->sacar(100);
        $transacoes = $this->conta1->getHistoricoTransacoes();

        $this->assertCount(2, $transacoes);
        $this->assertEquals('Depósito', $transacoes[0]['tipo']);
        $this->assertEquals(200, $transacoes[0]['valor']);
        $this->assertEquals('Saque', $transacoes[1]['tipo']);
        $this->assertEquals(100, $transacoes[1]['valor']);
    }
}
