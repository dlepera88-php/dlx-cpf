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

namespace CPF;

use CPF\Validators\ValidarDigitoVerificador;

/**
 * Class CPF
 * @package CPF
 * @covers CPFTest
 */
class CPF
{
    /** @var int */
    private $cpf;

    /**
     * Retornar o valor limpo do CPF em formato inteiro
     * @return int
     */
    public function getCpf(): int
    {
        return $this->cpf;
    }

    /**
     * CPF constructor.
     * @param string $cpf
     */
    public function __construct(string $cpf)
    {
        $this->cpf = preg_replace('~[^0-9]~',  '', $cpf);
    }

    /**
     * Retornar todos os números desse CPF, inclusive zeros a esquerda
     * @return string
     */
    public function getCpfCompleto(): string
    {
        return str_pad($this->getCpf(), 11, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna o CPF com a máscara aplicada
     * @return string
     */
    public function getCpfMask(): string
    {
        $numeros_cpf = $this->getCpfCompleto();
        $cpf_mask = substr($numeros_cpf, 0, 3) . '.' .
            substr($numeros_cpf, 3, 3) . '.' .
            substr($numeros_cpf, 6, 3) . '-' .
            substr($numeros_cpf, 9, 2);

        return $cpf_mask;
    }

    /**
     * Retorna o CPF com os números do meio ocultos, substituídos por *
     * @return string
     */
    public function getCpfOculto(): string
    {
        $numeros_cpf = $this->getCpfCompleto();
        $cpf_oculto = substr($numeros_cpf, 0, 3) . '.' .
            str_repeat('*', 3) . '.' .
            str_repeat('*', 3) . '-' .
            substr($numeros_cpf, 9, 2);

        return $cpf_oculto;
    }

    /**
     * Retorna os números base do CPF
     * @return string
     */
    public function getNumerosBase(): string
    {
        $cpf_completo = $this->getCpfCompleto();
        return substr($cpf_completo, 0, 9);
    }

    /**
     * Retorna o dígito verificador do CPF informado
     * @return string
     */
    public function getDigitoVerificador(): string
    {
        $cpf_completo = $this->getCpfCompleto();
        return substr($cpf_completo, -2);
    }

    /**
     * Verifica se é um número de CPF válido
     * @return bool
     * @throws Exceptions\CPFInvalidoException
     */
    public function isValido(): bool
    {
        return (new ValidarDigitoVerificador())->validar($this);
    }
}