<?php


require_once 'ContaBancaria.php';
use PHPUnit\Framework\TestCase;

class ContaBancariaTest extends TestCase {
    private $conta1;
    private $conta2;
    private $conta3;

    protected function setUp(): void {
        $this->conta1 = new ContaBancaria('Cliente 1', 1000);
        $this->conta2 = new ContaBancaria('Cliente 2', 500);
        $this->conta3 = new ContaBancaria('Cliente 3', 2000);
    }

    public function testDeposito() {
        $this->conta1->deposito(500);
        $this->assertEquals(1500, $this->conta1->getSaldo());

        $this->conta1->deposito(-100);
        $this->assertEquals(1500, $this->conta1->getSaldo());

        $this->conta3->deposito(300);
        $this->assertEquals(2300, $this->conta3->getSaldo());
    }

    public function testSaque() {
        $this->conta1->saque(200);
        $this->assertEquals(800, $this->conta1->getSaldo());

        $this->conta1->saque(1000);
        $this->assertEquals(800, $this->conta1->getSaldo());

        $this->conta1->saque(-50);
        $this->assertEquals(800, $this->conta1->getSaldo());

        $this->conta3->saque(100);
        $this->assertEquals(1900, $this->conta3->getSaldo());

        $this->conta2->saque(60);
        $this->assertEquals(440, $this->conta2->getSaldo());
    }

    public function testTransferir() {
        $this->conta1->transferir(200, $this->conta2);
        $this->assertEquals(800, $this->conta1->getSaldo());
        $this->assertEquals(700, $this->conta2->getSaldo());

        $this->conta1->transferir(1000, $this->conta2);
        $this->assertEquals(800, $this->conta1->getSaldo());
        $this->assertEquals(700, $this->conta2->getSaldo());

        $this->conta2->transferir(150, $this->conta3);
        $this->assertEquals(550, $this->conta2->getSaldo());
        $this->assertEquals(2150, $this->conta3->getSaldo());
    }

    public function testCalcularJuros() {
        $this->conta1->calcularJuros(10);
        $this->assertEquals(1100, $this->conta1->getSaldo());

        $this->conta1->calcularJuros(-5);
        $this->assertEquals(1100, $this->conta1->getSaldo());
    }

    public function testGetSaldo() {
        $this->assertEquals(1000, $this->conta1->getSaldo());
        $this->conta1->deposito(500);
        $this->assertEquals(1500, $this->conta1->getSaldo());
    }

    public function testGetCliente() {
        $this->assertEquals('Cliente 1', $this->conta1->getCliente());
    }

    public function testGetHistoricoTransacoes() {
        $this->conta1->deposito(200);
        $this->conta1->saque(100);
        $historico = $this->conta1->getHistoricoTransacoes();

        $this->assertCount(2, $historico);
        $this->assertEquals('depósito', $historico[0]['tipo']);
        $this->assertEquals(200, $historico[0]['valor']);
        $this->assertEquals('saque', $historico[1]['tipo']);
        $this->assertEquals(100, $historico[1]['valor']);
    }
}
