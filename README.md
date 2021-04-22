# Testes unitários com PHP Unit

- Este é um projeto simples para explicar o conceito do desenvolvimento baseado em testes, nele iremos abordar o que são e para quê servem os testes, como automatizar um teste e como utilizar o PHPUnit.

## Parte 1: O que é e para que serve o TDD?

- TDD, ou Desenvolvimento Orientado por Testes, é como o próprio nome já diz, desenvolver um software baseado em testes escritos do nosso código de produção. Basicamente o TDD se baseia em pequenos ciclos de repetições , onde para cada funcionalidade do sistema um teste é criado antes, é claro que inicialmente o teste falhará pois a funcionalidade ainda não existe, mas depois do teste implementamos a funcionalidade para que o teste passe. 

- Mas quais as vantagens de se usar o TDD? Uma grande vantagem desse tipo de desenvolvimento é a segurança, tanto no refactoring como na correção de bugs, já que podemos ver o que estamos ou não afetando. Outra vantagem é que consequentemente o nosso código estará menos acoplado, levando em conta que temos que separá-lo em pequenos "pedaços" para que sejam testáveis, entre outras muitas vantagens. Existem diversos artigos pela internet que explicam de maneira mais detalhada do TDD e sua importância, vale a pena buscá-los.

### <b>Escrevendo um teste simples</b>

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

### <b>Testes automatizados</b>

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

## Parte 2: Instalação e utilização do PHPUnit

- O PHPUnit é um framework de testes orientado a códigos do PHP, é a ferramenta de testes mais famosa do PHP mas existem outras como o PEST, por exemplo.

- Há duas formas de instalar o PHPUnit no seu projeto, a primeira delas é com um executável PHP e a segunda, a maneira que iremos utilizar, é pelo composer. Basta rodar o seguinte comando na pasta do seu projeto e o PHPUnit será instalado:

```
composer require --dev phpunit/phpunit ^9
```

- Agora que instalamos o PHPUnit, como iremos usá-lo para escrever um teste e utilizar todas as facilidades que ele nos traz?

### <b>Escrevendo testes com o PHPUnit</b>

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

- Note que removemos o if criado anteriormente, mas por quê? Essa verificação de teste sucedido ou falho é o PHPUnit que faz para nós. Como herdamos a classe TestCase nós temos acesso a alguns métodos, um deles é o ```$this->assertEquals();``` que verifica se dois valores são iguais (Para verificar todos os métodos do PHPUnit basta acessar a [documentação](https://phpunit.readthedocs.io/en/9.5/assertions.html)). Então no lugar do nosso if nós teremos:

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

- Mas e se nós criarmos um outro método e trocarmos a ordem dos lances fazendo com que o maior lance seja o primeiro, nosso teste irá passar? A resposta é não. Para isso precisaremos alterar a classe ```Avaliador.php```. No método ```avalia()``` iremos adicionar um foreach para cada lance e verificar se o lance é maior que o atributo ```$maiorValor``` inicializado com 0. Sua classe deve ficar assim:

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

### <b>Escrevendo mais testes</b>

- Se nós adicionarmos mais uma funcionalidade ao nosso código, como poderíamos escrever um teste para essa funcionalidade?

- O primeiro passo é adicionar mais uma funcionalidade. Na nossa classe ```Avaliador.php``` nós iremos adicionar um else if para verificar os menores lances do leilão e podermos recuperá-los depois com um método get (não esqueça de implementá-lo). Sua função ```avalia()``` deverá ficar assim:

```
public function avalia(Leilao $leilao): void
{
    foreach($leilao->getLances() as $lance){
        if($lance->getValor() > $this->maiorValor){
            $this->maiorValor = $lance->getValor();
        } else if($lance->getValor() < $this->menorValor){
            $this->menorValor = $lance->getValor();
        }
    }
}
```

- Agora para o nosso teste é bem simples, a lógica é a mesma do teste que pega o maior valor em ordem decrescente, mudando apenas o valor passado para o método de verificação que deverá ficar ```$this->assertEquals(2000, $menorValor)```.

- Se você inicializar a váriavel ```$menorValor``` com INF, copiar o código que encontra um maior lance em ordem crescente alterando para que seja o menor valor irá notar que o teste falha, mas por quê? O erro está na lógica utilizada para verificar o valor dos lances, com o else que implementamos ele nunca irá verificar os dois casos mas sim apenas o primeiro. Para resolver isso basta retirar o else e manter dois if's dentro dó método.

- Claro que este não é o código mais limpo do mundo, este é apenas um exemplo para que você possa notar a importância dos testes automatizados e como eles nos ajudam a verificar os bugs que nosso código possuí, sem eles talvez demorariamos muito mais tempo para encontrar um erro e refatorar nosso código, mas como o teste nos mostra o que falhou e onde falhou podemos corrigir rapidamente as falhas.


### Testando listas

- Vamos ter como exemplo um teste que busca os 3 maiores lances do nosso leilão independente da ordem. Primeiro precisamos implementar a nossa lógica, para isso iremos adicionar o atributo ```private $maioresLances;``` na nossa classe ```Avaliador.php``` e dentro da função ```avalia()``` adicione a seguinte lógica:

```
$lances = $leilao->getLances();

usort($lances, function(Lance $lance1, Lance $lance2) {
    return $lance2->getValor() - $lance1->getValor();
});

$this->maioresLances = array_slice($lances, 0, 3);
```

- Com isso iremos garantir que sempre serão retornados os maiores valores do leilão. Não esqueça de implementar a função get do atributo ```$maioresLances```.

- Agora precisamos realizar o teste disso. Primeiramente iremos criar o método do teste e em seguida implementar o leilão com quantos lances você preferir. Depois de criar o leilão, os usuários e realizar os lances, nós iremos pegar os maiores lances e testar a nossa lista:

```
$maioresLances = $leiloeiro->getMaioresLances();

$this->assertCount(3, $maioresLances);
$this->assertEquals(2000, $maioresLances[0]->getValor());
$this->assertEquals(1700, $maioresLances[1]->getValor());
$this->assertEquals(1500, $maioresLances[2]->getValor());
```

- Observe que utilizamos o método ```assertCount()```. Este método recebe como parâmetro um valor inteiro X + um array e verifica se o número de indíces do array passado bate com o número inteiro passado. Para verificar todos os métodos de teste acesse a [documentação](https://phpunit.readthedocs.io/en/9.5/assertions.html).

## Parte 3: Classes de equivalência

- Agora que aprendemos como podemos criar testes automatizados com o PHPUnit, surge uma dúvida muito importante: Como saber quando nossa aplicação é confiável e quando temos testes o suficiente? Se analisarmos bem nosso código, baseado nos padrões passados anteriormente e olharmos o que vem antes do ```assert/then```, notaremos que temos algumas diferenças na montagem do cenário de testes mas que não há diferença nos dados que passamos, ou seja, tanto faz se passarmos 20.000 ou 2.000, o resultado será o mesmo. Mas se fossemos criar um teste para cada valor diferente teriamos testes infinitos, e é aí que entram as classes de equivalência.

### <b>Entendendo o que são classes de equivalência e limites de fronteira</b>

- Classes de equivalência são similaridades entre os cenários de testes. É muito importante encontrar as classes de equivalência pois com elas poderemos criar apenas um teste para cada cenário, não iremos repetir testes à toa. Um exemplo simples para entendermos melhor seria um cenário onde iriamos testar idades, vamos dividi-lo em três partes:

   1º - Temos o dado de entrada, que é justamente a idade;

   2º - Temos os valores permitidos, vamos tomar como exemplo uma idade entre 18 e 120;

   3º - Temos as classes de equivalência. Um teste para idades entre 18 e 120 deve funcionar da mesma forma, não precisamos criar um teste para cada idade de 18 a 120. Podemos ter um teste para idades inferiores a 18 e um teste para idades superiores a 120. São 3 testes para cada classe de equivalência e que funciona com qualquer valor dentro do limite estabelecido na regra de negócio, sem precisarmos criar um teste para cada valor.

- Dentro das classes de equivalência podemos ter também uma abordagem diferente chama de ```Limite de fronteira```, que faz com que a gente teste o primeiro e último valor da classe de equivalência e/ou os valores <b>válidos</b> na borda da classe. Se pegarmos as três regras exemplificadas anteriormente e nos atentarmos aos limites de fronteira teremos os seguintes casos:

   1º - De 18 à 120 iremos testar se a idade é igual à 18 e 120 que são o primeiro e último valor da classe e também iremos testar se a idade é igual a 19 ou 119 que são os valores mais próximos do limite da classe;

   2º - Nas idades menores que 18 iremos testar se a idade é igual à 17 que é o valor mais próximo do limite da classe;

   3º - Nas idades maiores que 120 iremos testar se a idade é igual à 121  que é o valor mais próximo do limite da classe.

- Estas são formas de reduzirmos o número de testes da nossa aplicação sem perdermos a confiabilidade, garantindo que todos os casos estão sendo testados corretamente. Sempre teste apenas o necessário e evite criar o mesmo teste para um valor diferente. Caso queria estudar mais sobre classes de equivalência e limites de fronteira recomendo que leia este [artigo](http://testwarequality.blogspot.com/p/tenicas-de-teste.html). 


## Parte 4: Organizando nossos testes

- Como foi dito anteriormente, o nosso código não é exatamente o mais limpo do mundo. Se observarmos bem nosso código de testes veremos que todos tem uma coisa que se repete: A criação de um leilão. Então nosso primeiro passo é isolar a criação de um leilão para um único lugar e reaproveitar isso nos nossos métodos de testes.


### <b>Data Providers</b>

- Para resolvermos o problema descrito acima, poderiamos separar essas criações de leilão em uma função e toda vez que iniciarmos um teste chamarmos essas funções. Mas o PHPUnit nos oferece uma ferramenta chamada Data Provider que resolve este nosso problema.

- O Data Provider é uma maneira de entregarmos dados para os nossos testes afim de não repetirmos código e fazermos com que nossos testes sejam executados várias vezes mas com dados diferentes.

- Então utilizando o Data Provider teríamos uma abordagem diferente para os testes. Vamos tomar como exemplo nosso primeiro teste ```testAvaliadorDeveEncontrarOMaiorLanceEmOrdemCrescente()```. Com o Data Provider nós removeriamos a parte da Ordem Crescente pois tanto faz a ordem dos lances já que o método irá receber o leilão pronto.

- Mas como utilizar o Data Provider? O primeiro passo é refatorarmos a criação do leilão para um método específico.

```
public function leilaoEmOrdemCrescente(): Leilao
{
    $leilao = new Leilao('Carro brabo');

    $vinicius = new Usuario('Vinicius');
    $yasmin = new Usuario('Yasmin');
    $kimie = new Usuario('Kimie');

    $leilao->recebeLance(new Lance($kimie, 1700));
    $leilao->recebeLance(new Lance($vinicius, 2000));
    $leilao->recebeLance(new Lance($yasmin, 2500));

    return $leilao;
}

public function leilaoEmOrdemDecrescente(): Leilao
{
    $leilao = new Leilao('Carro brabo');

    $vinicius = new Usuario('Vinicius');
    $yasmin = new Usuario('Yasmin');
    $kimie = new Usuario('Kimie');

    $leilao->recebeLance(new Lance($yasmin, 2500));
    $leilao->recebeLance(new Lance($vinicius, 2000));
    $leilao->recebeLance(new Lance($kimie, 1700));

    return $leilao;
}
```

- Agora que refatoramos, como podemos fazer com que o método de teste reconheça que ele irá receber um leilão várias vezes? Através de uma annotation ```@dataProvider```, com ela informamos que este método receberá os dados para que ele possa testar.

- Adicione a annotation no primeiro método:

```
/**
* @dataProvider entregaLeiloes()
*/
public function testAvaliadorDeveEncontrarOMaiorLance(Leilao $leilao){...}
```

- E agora vamos implementar a função ```entregaLeiloes()```:

```
public function entregaLeiloes()
{
    return [
        array($this->leilaoEmOrdemCrescente(), $this->leilaoEmOrdemDecrescente())
    ];
}
```

- Note que temos arrays dentro de um array, isso porque quando o teste receber os dados ele irá colocá-los como parâmetro um seguido do outro. Como temos 2 leilões e apenas um parâmetro, seria retornado um aviso ao executar nossos testes.

- Faça o mesmo para todos os outros métodos de teste e adicione o Data Provider. Note que, agora, a única coisa que se repete entre os métodos é a annotation de Data Provider, nosso código está muito mais limpo e mais legível também.

- Agora que utilizamos o Data Provider para melhorarmos nosso código, surge um questionamento: Ainda temos código repetido, poderiamos extraí-lo para um data provider também? Até poderiamos, mas iríamos implementar um método somente para instanciar um objeto do tipo ```Avaliador```. Existe uma maneira mais simples de se fazer isso.

### <b>Método setUp</b>

- O método setUp é um meio do PHPUnit saber que sempre antes de executar um método de teste ele deve executar antes o que está dentro do método setUp, e é lá que iremos adicionar a regra de criar um leiloeiro. 

```
private $leiloeiro;

protected function setUp(): void
{
    $this->leiloeiro = new Avaliador();
}
```

- Implementando o método setUp podemos remover dos nossos métodos de teste a linha que instânciava um avaliador e substituir ```$leiloeiro->metodo()``` por ```$this->leiloeiro->metodo()```.

- Uma importante observação é que todos os Data Provider são executados para cada teste existente e só depois, antes de executar cada teste, é executado o código dentro do método setUp.

- Para executarmos código antes ou depois de testes, o PHPUnit nos fornece as ```fixtures```, que nada mais são métodos que serão executados em momentos específicos. Todos os métodos fixtures estão listados na [documentação](https://phpunit.readthedocs.io/en/8.1/fixtures.html) do PHPUnit.

### <b>Configurações no XML</b>

- O PHPUnit utiliza um arquivo XML para suas configurações. Lá podemos deixar por padrão a flag --colors, apontar o diretório padrão para os testes e muitas outras coisas. Para utilizá-lo basta criar um arquivo chamado ```phpunit.xml``` na raiz do seu projeto e adicionar lá as configurações desejadas.

- Para adicionarmos as configurações desejadas primeiramente precisamos ter a tag ```<phpunit>``` com alguns parâmetros obrigatórios. Basta colar o código abaixo dentro do seu arquivo.

```
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
</phpunit>
```

- Podemos adicionar um parâmetro dentro da tag ```<phpunit>``` para que sempre que executarmos o phpunit ele já executar com a flag --colors, basta adicionar ```colors="true"``` dentro da tag.

- Podemos também informar as suites de testes, que é onde os testes ficam. Podemos adicionar nosso diretório já criado, mas poderíamos adicionar outros também caso tivéssemos. Para isso usamos as tags ```<testsuites>```, ```<testsuite>``` e ```<directory>```.

```
<testsuites>
    <testsuite name="nome">
        <directory>tests</directory>
    </testsuite>
</testsuites>
```

- Adicionando essas configurações, basta executar o phpunit sem passar parâmetro nenhum.

- Uma função interessante é a de arquivos de log, que serve para verificarmos os testes que foram executados e algumas outras informações. Os nomes descritivos dos testes são importantes pois eles que são usados nesse arquivo de log.

```
<logging>
    <testdoxText outputFile="arquivo-log.txt"/>
</logging>

Arquivo de Log Gerado:
 [x] Avaliador deve encontrar o maior lance with data set #0
 [x] Avaliador deve encontrar o menor lance with data set #0
 [x] Avaliador deve buscar os tres maiores valores with data set #0
```

- Se preferir dar um nome mais descritivo para os ```data set``` basta trocar o retorno dos data providers para:

```
return [
    'nome data set' => [$variavel]
];
```

- Caso queira ver as outras opções de configurações que o PHPUnit oferece basta checar a [documentação](https://phpunit.readthedocs.io/en/9.5/configuration.html).


## Parte 5: Desenvolvimento Guiado a Testes

- Vamos supor que nossa aplicação receba uma nova regra onde cada usuário só possa dar um lance, caso haja mais de um lance para o mesmo usuário o segundo lance será ignorado. Vamos começar desenvolvendo nosso teste. Crie um diretório chamado ```Model``` na pasta de tests, crie um ```LeilaoTest.php``` e não esqueça de extender a classe de testes do PHPUnit. Dentro dele iremos implementar o teste.

```
public function testLeilaoNaoDeveReceberLancesRepetidos()
{
    $leilao = new Leilao('Carro');
    $ana = new Usuario('Ana');

    $leilao->recebeLance(new Lance($ana, 1000));
    $leilao->recebeLance(new Lance($ana, 1500));

    $this->assertCount(1, $leilao->getLances());
    $this->assertEquals(1000, $leilao->getLances()[0]->getValor());
}
```

- Se você rodar os testes verá que isso vai falhar. Este teste falhar é um bom sinal, pois ainda não implementamos essa funcionalidade e se ela falhou é porque algo realmente foi testado. Vamos então implementar a nossa funcionalidade. Na classe ```Leilao.php``` adicione o seguinte metodo.

```
private function perteceAoUltimoUsuario(Lance $lance): bool
{
    $ultimoLance = $this->lances[count($this->lances) - 1];
    return $lance->getUsuario() == $ultimoLance;
}
```

- E dentro do método ```recebeLance()``` iremos adicionar a seguinte verificação.

```
if(!empty($this->lances) && $this->perteceAoUltimoUsuario($lance)){
    return;
}
```

- É assim que o TDD funciona, primeiro criamos um teste e garantimos que ele esteja funcionando, no caso falhando, pois nossa funcionalidade ainda não foi implementada. Depois desenvolvemos a funcionalidade e garantimos que o teste agora passe. Depois de desenvolver a funcionalidade e garantir o funcionamento do teste nós refatoramos o código para deixá-lo o mais limpo possível, ainda garantindo que o teste funciona. Caso queira entender melhor como funciona o TDD você pode acessar este [artigo](https://tdd.caelum.com.br) da Caelum.