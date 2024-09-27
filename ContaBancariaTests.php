<?php

require_once 'ContaBancaria.php';
use PHPUnit\Framework\TestCase;

class ContaCorrenteTest extends TestCase {
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

        $this->primeiraConta->depositar(-100);
        $this->assertEquals(1500, $this->primeiraConta->getSaldo());

        $this->terceiraConta->depositar(300);
        $this->assertEquals(2300, $this->terceiraConta->getSaldo());
    }

    public function testSacar() {
        $this->primeiraConta->sacar(200);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());

        $this->primeiraConta->sacar(1000);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());

        $this->primeiraConta->sacar(-50);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());

        $this->terceiraConta->sacar(100);
        $this->assertEquals(1900, $this->terceiraConta->getSaldo());

        $this->segundaConta->sacar(60);
        $this->assertEquals(440, $this->segundaConta->getSaldo());
    }

    public function testTransferencia() {
        $this->primeiraConta->transferir(200, $this->segundaConta);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());
        $this->assertEquals(700, $this->segundaConta->getSaldo());

        $this->primeiraConta->transferir(1000, $this->segundaConta);
        $this->assertEquals(800, $this->primeiraConta->getSaldo());
        $this->assertEquals(700, $this->segundaConta->getSaldo());

        $this->segundaConta->transferir(150, $this->terceiraConta);
        $this->assertEquals(550, $this->segundaConta->getSaldo());
        $this->assertEquals(2150, $this->terceiraConta->getSaldo());
    }

    public function testAplicarJuros() {
        $this->primeiraConta->aplicarJuros(10);
        $this->assertEquals(1100, $this->primeiraConta->getSaldo());

        $this->primeiraConta->aplicarJuros(-5);
        $this->assertEquals(1100, $this->primeiraConta->getSaldo());
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
