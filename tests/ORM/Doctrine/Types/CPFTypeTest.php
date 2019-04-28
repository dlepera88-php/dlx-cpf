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

namespace CPF\Tests\ORM\Doctrine\Types;

use CPF\CPF;
use CPF\Exceptions\CPFInvalidoException;
use CPF\ORM\Doctrine\Types\CPFType;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

/**
 * Class CPFTypeTest
 * @package CPF\Tests\ORM\Doctrine\Types
 * @coversDefaultClass \CPF\ORM\Doctrine\Types\CPFType
 */
class CPFTypeTest extends TestCase
{
    /**
     * @return CPFType
     * @throws DBALException
     */
    public function getCPFType(): CPFType
    {
        if (!Type::hasType(CPFType::CPF)) {
            Type::addType(CPFType::CPF, CPFType::class);
        }

        /** @var CPFType $cpf_type */
        $cpf_type = Type::getType(CPFType::CPF);
        return $cpf_type;
    }

    /**
     * @throws DBALException
     * @throws CPFInvalidoException
     */
    public function test_ConvertToDatabaseValue_deve_converter_classe_CPF_para_valor_do_bd()
    {
        /** @var AbstractPlatform $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $cpf = new CPF('117.089.350-37');
        $cpf_type = $this->getCPFType();

        $valor_bd = $cpf_type->convertToDatabaseValue($cpf, $platform);

        $this->assertIsString($valor_bd);
        $this->assertEquals($cpf->getCpfMask(), $valor_bd);
    }

    /**
     * @throws CPFInvalidoException
     * @throws DBALException
     */
    public function test_ConvertToDatabaseValue_deve_lancar_excecao_quando_tentar_converter_CPF_invalido()
    {
        /** @var AbstractPlatform $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $cpf = new CPF('117.089.350-17');
        $cpf_type = $this->getCPFType();

        $this->expectException(CPFInvalidoException::class);
        $this->expectExceptionCode(CPFInvalidoException::DV_INCORRETO);

        $cpf_type->convertToDatabaseValue($cpf, $platform);
    }

    /**
     * @throws DBALException
     */
    public function test_ConvertToPHPValue_deve_converter_valor_no_bd_para_classe_CPF()
    {
        /** @var AbstractPlatform $platform */
        $platform = $this->createMock(AbstractPlatform::class);

        $str_cpf = '117.089.350-37';
        $cpf_type = $this->getCPFType();

        $cpf = $cpf_type->convertToPHPValue($str_cpf, $platform);

        $this->assertInstanceOf(CPF::class, $cpf);
        $this->assertEquals($str_cpf, $cpf->getCpfMask());
    }
}
