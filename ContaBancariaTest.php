<?php

require_once 'ContaBancaria.php';
use PHPUnit\Framework\TestCase;

class ContaBancariaTest extends TestCase {
    private $primeiraConta;
    private $segundaConta;
    private $terceiraConta;

    protected function setUp(): void {
        $this->primeiraConta = new ContaBancaria('Titular 1', 1000);
        $this->segundaConta = new ContaBancaria('Titular 2', 500);
        $this->terceiraConta = new ContaBancaria('Titular 3', 2000);
    }

    public function testDepositar() {
        $this->primeiraConta->depositar(500);
        $this->assertEquals(1500, $this->primeiraConta->getSaldo());

        // Teste de exceção para depósito de valor negativo
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Valor inválido para depósito.");
        $this->primeiraConta->depositar(-100);
    }

    public function testSacar() {
        $this->primeiraConta->sacar(200);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());

        // Teste de exceção para saque com valor maior que o saldo
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Saldo insuficiente.");
        $this->primeiraConta->sacar(1000);

        // Teste de exceção para saque de valor negativo
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Valor inválido para saque.");
        $this->primeiraConta->sacar(-50);
    }

    public function testTransferencia() {
        $this->primeiraConta->transferir(200, $this->segundaConta);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());
        $this->assertEquals(700, $this->segundaConta->getSaldo());

        // Teste de exceção para transferência com valor maior que o saldo
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Saldo insuficiente para transferência.");
        $this->primeiraConta->transferir(1000, $this->segundaConta);
    }

    public function testAplicarJuros() {
        $this->primeiraConta->aplicarJuros(10);
        $this->assertEquals(1100, $this->primeiraConta->getSaldo());

        // Teste de exceção para taxa de juros negativa
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Taxa inválida.");
        $this->primeiraConta->aplicarJuros(-5);
    }

    public function testConsultarSaldo() {
        $this->assertEquals(1000, $this->primeiraConta->getSaldo());
        $this->primeiraConta->depositar(500);
        $this->assertEquals(1500, $this->primeiraConta->getSaldo());
    }

    public function testObterNomeCliente() {
        $this->assertEquals('Titular 1', $this->primeiraConta->getNomeCliente());
    }

    public function testConsultarTransacoes() {
        $this->primeiraConta->depositar(200);
        $this->primeiraConta->sacar(100);
        $transacoes = $this->primeiraConta->getTransacoes();

        $this->assertCount(2, $transacoes);
        $this->assertEquals('Depósito', $transacoes[0]['descricao']);
        $this->assertEquals(200, $transacoes[0]['valor']);
        $this->assertEquals('Saque', $transacoes[1]['descricao']);
        $this->assertEquals(100, $transacoes[1]['valor']);
    }
}
