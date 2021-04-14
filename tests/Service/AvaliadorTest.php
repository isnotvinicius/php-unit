<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    public function testAvaliadorDeveEncontrarOMaiorLanceEmOrdemCrescente()
    {
        $leilao = new Leilao('Carro brabo');

        $vinicius = new Usuario('Vinicius');

        $yasmin = new Usuario('Yasmin');

        $leilao->recebeLance(new Lance($vinicius, 2000));
        $leilao->recebeLance(new Lance($yasmin, 2500));

        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        $this->assertEquals(2500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarOMaiorLanceEmOrdemDecrescente()
    {
        $leilao = new Leilao('Carro brabo');

        $vinicius = new Usuario('Vinicius');

        $yasmin = new Usuario('Yasmin');
        
        $leilao->recebeLance(new Lance($yasmin, 2500));
        $leilao->recebeLance(new Lance($vinicius, 2000));

        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        $this->assertEquals(2500, $maiorValor);
    }
}