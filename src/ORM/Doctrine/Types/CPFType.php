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

namespace CPF\ORM\Doctrine\Types;


use CPF\CPF;
use CPF\Exceptions\CPFInvalidoException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Class CPFType
 * @package CPF\ORM\Doctrine\Types
 * @covers CPFTypeTest
 */
class CPFType extends Type
{
    const CPF =  'cpf';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'cpf';
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return self::CPF;
    }

    /**
     * Converte o valor do banco de dados para a classe CPF
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return CPF
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new CPF($value);
    }

    /**
     * Converte o valor da classe CPF para ser salva no banco de dados
     * @param CPF $value
     * @param AbstractPlatform $platform
     * @return mixed
     * @throws CPFInvalidoException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $value->isValido();
        return $value->getCpfMask();
    }
}