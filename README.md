# Testes unitários com PHP Unit

- Este é um simples projeto para aprender os conceitos de TDD e a utilização do PHP Unit.

## O que é e para que serve o TDD?

- TDD, ou Desenvolvimento Orientado por Testes, é como o próprio nome já diz, desenvolver um software baseado em testes escritos do nosso código de produção. Basicamente o TDD se baseia em pequenos ciclos de repetições , onde para cada funcionalidade do sistema um teste é criado antes, é claro que inicialmente o teste falhará pois a funcionalidade ainda não existe, mas depois do teste implementamos a funcionalidade para que o teste passe. 

- Mas quais as vantagens de se usar o TDD? Uma grande vantagem desse tipo de desenvolvimento é a segurança, tanto no refactoring como na correção de bugs, já que podemos ver o que estamos ou não afetando. Outra vantagem é que consequentemente o nosso código estará menos acoplado, levando em conta que temos que separá-lo em pequenos "pedaços" para que sejam testáveis, entre outras muitas vantagens. Existem diversos artigos pela internet que explicam de maneira mais detalhada do TDD e sua importância, vale a pena buscá-los.

## Escrevendo um teste simples

- Caso navegue pelas pastas do projeto, verá que ele se trata de um leilão, onde temos um Usuário com nome, um Leilão com a descrição e seus lances, e o Lance com o usuário que deu o lance e seu valor. Há também uma service que pega o array de Leilão e retorna para nós o maior valor.

- Agora, na raiz do nosso projeto iremos criar o arquivo ```teste-avaliador.php``` e escrever um pequeno código para testarmos se de fato estamos recebendo o valor do maior lance efetuado.

```
<?php

use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

require 'vendor/autoload.php';

$leilao = new Leilao('Ferrari');

$vinicius = new Usuario('Vinicius');

$yasmin = new Usuario('Yasmin');

$leilao->recebeLance(new Lance($vinicius, 2000));
$leilao->recebeLance(new Lance($yasmin, 2500));

$leiloeiro = new Avaliador();

$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

echo $maiorValor;
```

- Se executarmos este arquivo teremos como resultado de fato o maior lance dado, mas vamos pensar um pouco. Caso este código seja pego por outro desenvolvedor como ele terá certeza que este é de fato o maior lance? Outro problema é que o teste não informa se o código está correto ou não, ele apenas retornou o valor e nós mesmos precisamos verificar se o teste é correto ou falho. Como podemos resolver isso?

## Testes automatizados

- Um teste automatizado é extremamente necessário pois um teste feito manualmente está suscetível a erros, diferente de um automatizado. Testes automatizados são separados em alguns cenários, sendo eles:

   1º - Criar o cenário: Aqui é onde "organizamos a casa" para que possamos executar nosso código, no nosso caso instânciamos as classes necessárias e fazemos os lances do leião;

   2º - Execução do código: Aqui executamos o código em si, no nosso caso avaliamos o leilão e retornamos o maior lance dado;

   3º - Verificação: Por último verificamos se o resultado do segundo cenário é o esperado ou não.

- Este padrão é conhecido como ```Arrange-Act-Assert``` ou ```Given-When-Then```.

- Poderiamos adicionar um if para termos mais automatizado esse nosso teste, algo como:

```
$valorEsperado = 2500;

if($valorEsperado == $maiorValor){
    echo "TESTE OK";
}else{
    echo "TESTE FALHOU";
}
```

- Note que nosso teste criado ainda não está automatizado pois ele não informa se o teste foi bem sucedido ou não, para isso podemos adicionar um if verificando se o valor esperado é igual a 2500, mas isso ainda é muito pouco e dá para ser melhorado. Como podemos melhorar e automatizar o nosso teste?

## PHPUnit

- O PHPUnit é um framework de testes orientado a códigos do PHP, é a ferramenta de testes mais famosa do PHP mas existem outras como o PEST, por exemplo.

- Há duas formas de instalar o PHPUnit no seu projeto, a primeira delas é com um executável PHP e a segunda, a maneira que iremos utilizar, é pelo composer. Basta rodar o seguinte comando na pasta do seu projeto e o PHPUnit será instalado:

```
composer require --dev phpunit/phpunit ^9
```

- Agora que instalamos o PHPUnit, como iremos usá-lo para escrever um teste e utilizar todas as facilidades que ele nos traz?

## Escrevendo testes com o PHPUnit

- A primeira coisa a se saber do PHPUnit é que ele não irá executar arquivos como o que criamos anteriormente, o PHPUnit trabalha com classes de teste, ou seja, para que os testes sejam executados eles precisam estar dentro de uma classe.

- O próximo passo é criarmos uma pasta para armazenarmos as nossas classes de teste. Neste exemplo elas ficarão dentro da pasta ```tests``` na raiz do nosso projeto.

- Dentro desta pasta iremos criar uma classe chamada ```AvaliadorTest.php```. Note que iniciamos com o nome da classe que será testada e adicionamos o sufixo ```test```, isso é uma convenção, ou seja, não é uma regra a ser seguida mas é assim que normalmente são nomeadas as classes de teste do PHPUnit.

- A classe de teste também precisa herdar a classe ```TestCase``` do PHPUnit para que possamos utilizar todas as features que a ferramenta nos oferece. Sua classe deve ficar assim:

```
<?php

namespace Alura\Leilao\Tests\Service;

use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{

}
```

- Como estamos trabalhando com classes, para executarmos nossos testes precisamos criar métodos dentro da nossa classe de teste. Na classe que acabamos de criar, implemente um método qualquer e cole o código contido no arquivo ```teste-avaliador.php```.

```
public function testUm()
{
   $leilao = new Leilao('Carro brabo');

   $vinicius = new Usuario('Vinicius');

   $yasmin = new Usuario('Yasmin');

   $leilao->recebeLance(new Lance($vinicius, 2000));
   $leilao->recebeLance(new Lance($yasmin, 2500));

   $leiloeiro = new Avaliador();

   $leiloeiro->avalia($leilao);

   $maiorValor = $leiloeiro->getMaiorValor();

   $valorEsperado = 2500;
}
```

- Note que removemos o if criado anteriormente, mas por quê? Essa verificação de teste sucedido ou falho é o PHPUnit que faz para nós. Como herdamos a classe TestCase nós temos acesso a alguns métodos, um deles é o ```$this->assertEquals();``` que verifica se dois valores são iguais. Então no lugar do nosso if nós teremos:

```
$this->assertEquals(2500, $maiorValor);
```

- Agora se você ir no seu terminal e executar o phpunit passando a pasta que contém os nossos testes, eles serão executados e o terminal irá mostrar se os testes passaram ou não e mais algumas informações. Execute no terminal:

```
vendor\bin\phpunit tests
```

- Com este comando todos os testes dentro da pasta informada serão executados e o terminal irá exibir quantos testes foram executados e com quantas verificações.

```
OK (1 test, 1 assertion)
```

- Caso o teste falhe o terminal irá exibir qual método contém a falha e qual foi o erro obtido, por isso é importante prestar atenção em dois detalhes:

   1º - Para o PHPUnit entender que o método é um teste o nome do método precisa ter o prefixo ```test```;
   
   2º - Depois do prefixo é importante deixar bem descrito o que este teste faz, não tenha medo de dar nomes grandes, quanto mais descritivo melhor.


- No nosso caso haviamos nomeado nosso método como ```testUm()``` e isso não é nada descritivo, um nome melhor seria ```testAvaliadorDeveEncontrarOMaiorLanceEmOrdemCrescente()```.

- Mas e se nós criarmos um outro método e trocarmos a ordem dos lances fazendo com que o maior lance venha antes do primeiro, nosso teste irá passar? A resposta é não. Para isso precisaremos alterar a classe ```Avaliador.php```. No método ```avalia()``` iremos adicionar um foreach para cada lance e verificar se o lance é maior que o atributo ```$maiorValor``` inicializado com 0. Sua classe deve ficar assim:

```
class Avaliador
{
    private $maiorValor = 0;

    public function avalia(Leilao $leilao): void
    {
        foreach($leilao->getLances() as $lance){
            if($lance->getValor() > $this->maiorValor){
                $this->maiorValor = $lance->getValor();
            }
        }
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }
}
```

- Agora se executarmos os testes com os dois métodos criados ambos passarão, já que agora o maior valor sempre será de fato o maior lance.
