<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

$leilao = new Leilao('Carro brabo');

$vinicius = new Usuario('Vinicius');

$yasmin = new Usuario('Yasmin');

$leilao->recebeLance(new Lance($vinicius, 2000));
$leilao->recebeLance(new Lance($yasmin, 2500));

$leiloeiro = new Avaliador();

$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

echo $maiorValor;