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

namespace CPF\Tests\Validators;

use CPF\CPF;
use CPF\Exceptions\CPFInvalidoException;
use CPF\Validators\ValidarDigitoVerificador;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidarDigitoVerificadorTest
 * @package CPF\Tests\Validators
 * @coversDefaultClass \CPF\Validators\ValidarDigitoVerificador
 */
class ValidarDigitoVerificadorTest extends TestCase
{

    /**
     * @throws CPFInvalidoException
     */
    public function test_Validar_deve_lancar_excecao_quando_digito_verificador_for_incorreto()
    {
        $cpf = new CPF('011.001.001-22');

        $this->expectException(CPFInvalidoException::class);
        $this->expectExceptionCode(CPFInvalidoException::DV_INCORRETO);

        (new ValidarDigitoVerificador())->validar($cpf);
    }

    /**
     * @throws CPFInvalidoException
     */
    public function test_Validar_deve_retornar_true_quando_digito_verificador_valido()
    {
        $cpf = new CPF('570.607.270-19');

        $is_valido = (new ValidarDigitoVerificador())->validar($cpf);
        $this->assertTrue($is_valido);
    }
}
