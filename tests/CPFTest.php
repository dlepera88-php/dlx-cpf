<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CPF\Tests;

use CPF\CPF;
use CPF\Exceptions\CPFInvalidoException;
use PHPUnit\Framework\TestCase;

/**
 * Class CPFTest
 * @package CPF\Tests
 * @coversDefaultClass \CPF\CPF
 */
class CPFTest extends TestCase
{
    public function test__construct()
    {
        $txt_cpf = '0123456789101';

        $cpf = new CPF($txt_cpf);
        $this->assertInstanceOf(CPF::class, $cpf);
        $this->assertEquals($cpf->getCpfMask(), (string)$cpf);
    }

    /**
     * @covers ::getCpfCompleto
     */
    public function test_GetCpfCompleto_deve_retornar_todos_os_numeros_do_cpf_inclusive_zeros_a_esquerda()
    {
        $str_cpf = '189.136.940-77';
        $cpf_completo = preg_replace('~[^0-9]+~',  '', $str_cpf);
        $cpf = new CPF($str_cpf);

        $this->assertIsString($cpf->getCpfCompleto());
        $this->assertEquals($cpf_completo, $cpf->getCpfCompleto());
    }

    /**
     * @covers ::getCpf
     */
    public function test_GetCpf_deve_retornar_valor_limpo_de_cpf()
    {
        $str_cpf = '189.136.940-77';
        $cpf_limpo = preg_replace('~[^0-9]+~',  '', $str_cpf);
        $cpf = new CPF($str_cpf);

        $this->assertIsInt($cpf->getCpf());
        $this->assertEquals($cpf_limpo, $cpf->getCpf());
    }

    /**
     * @covers ::getCpfMask
     */
    public function test_GetCpfMask_deve_retornar_o_CPF_com_mascara()
    {
        $str_cpf = '551.920.170-68';
        $cpf = new CPF($str_cpf);

        $rgx_cpf = '~^([0-9]{3}\.){2}[0-9]{3}\-[0-9]{2}$~';
        $this->assertRegExp($rgx_cpf, $cpf->getCpfMask());
    }

    /**
     * @covers ::getCpfOculto
     */
    public function test_GetCpfOculto()
    {
        $str_cpf = '716.799.560-60';
        $cpf = new CPF($str_cpf);

        $rgx_cpf = '~^([0-9]{3})(\.\*{3}){2}\-[0-9]{2}$~';
        $this->assertRegExp($rgx_cpf, $cpf->getCpfOculto());
    }

    /**
     * @covers ::getNumerosBase
     */
    public function test_GetNumerosBase_deve_retornar_o_numero_base_do_cpf_menos_o_digito_verificador()
    {
        $str_cpf = '928.078.200-26';
        $numero_base = substr(filter_var($str_cpf, FILTER_SANITIZE_NUMBER_INT), 0, 9);

        $cpf = new CPF($str_cpf);
        $this->assertEquals($numero_base, $cpf->getNumerosBase());
    }

    /**
     * @covers ::getDigitoVerificador
     */
    public function test_GetDigitoVerificador_deve_retornar_o_digito_verificador()
    {
        $str_cpf = '928.078.200-26';
        $digito_verificador = substr($str_cpf, -2);

        $cpf = new CPF($str_cpf);
        $this->assertIsString($cpf->getDigitoVerificador());
        $this->assertEquals($digito_verificador, $cpf->getDigitoVerificador());
    }

    /**
     * @covers ::isValido
     * @throws CPFInvalidoException
     */
    public function test_IsValido_deve_retornar_true_quando_cpf_for_valido_false_quando_nao_for()
    {
        $str_cpf = '445.640.430-21';
        $cpf = new CPF($str_cpf);

        $is_valido = $cpf->isValido();

        $this->assertTrue($is_valido, "CPF {$str_cpf}, na verdade, é inválido.");
    }

    /**
     * @throws CPFInvalidoException
     * @covers ::isValido
     */
    public function test_IsValido_deve_lancar_excecao_quando_cpf_for_invalido()
    {
        $str_cpf = '445.640.430-20';
        $cpf = new CPF($str_cpf);

        $this->expectException(CPFInvalidoException::class);
        $this->expectExceptionCode(10);

        $cpf->isValido();
    }
}
