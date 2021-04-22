<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    private $leiloeiro;

    protected function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoEmOrdemCrescente
     */
    public function testAvaliadorDeveEncontrarOMaiorLance(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maiorValor = $this->leiloeiro->getMaiorValor();

        $this->assertEquals(2500, $maiorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveEncontrarOMenorLance(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $menorValor = $this->leiloeiro->getMenorValor();

        $this->assertEquals(1700, $menorValor);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     */
    public function testAvaliadorDeveBuscarOsTresMaioresValores(Leilao $leilao)
    {
        $this->leiloeiro->avalia($leilao);

        $maioresLances = $this->leiloeiro->getMaioresLances();

        $this->assertCount(3, $maioresLances);
        $this->assertEquals(2500, $maioresLances[0]->getValor());
        $this->assertEquals(2000, $maioresLances[1]->getValor());
        $this->assertEquals(1700, $maioresLances[2]->getValor());
    }

    public function leilaoEmOrdemCrescente(): array
    {
        $leilao = new Leilao('Carro brabo');

        $vinicius = new Usuario('Vinicius');
        $yasmin = new Usuario('Yasmin');
        $kimie = new Usuario('Kimie');

        $leilao->recebeLance(new Lance($kimie, 1700));
        $leilao->recebeLance(new Lance($vinicius, 2000));
        $leilao->recebeLance(new Lance($yasmin, 2500));

        return [
            'ordem crescente' => [$leilao]
        ];
    }

    public function leilaoEmOrdemDecrescente(): array
    {
        $leilao = new Leilao('Carro brabo');

        $vinicius = new Usuario('Vinicius');
        $yasmin = new Usuario('Yasmin');
        $kimie = new Usuario('Kimie');

        $leilao->recebeLance(new Lance($yasmin, 2500));
        $leilao->recebeLance(new Lance($vinicius, 2000));
        $leilao->recebeLance(new Lance($kimie, 1700));

        return [
            'ordem decrescente' => [$leilao]
        ];
    }
}