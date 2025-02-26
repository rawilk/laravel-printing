<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Illuminate\Support\Carbon;
use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class DateTime extends Type
{
    protected int $tag = TypeTag::DATETIME->value;

    /**
     * @param  Carbon  $value
     */
    public function __construct(public mixed $value) {}

    public static function fromBinary(string $binary, int &$offset): array
    {
        $attrName = self::nameFromBinary($binary, $offset);

        $valueLen = (unpack('n', $binary, $offset))[1];
        $offset += 2;

        $data = unpack('nY/cm/cd/cH/ci/cs/cfff/aUTCSym/cUTCm/cUTCs', $binary, $offset);
        $offset += $valueLen;

        $value = Carbon::createFromFormat(
            'YmdHisO',
            $data['Y']
                . str_pad((string) $data['m'], 2, '0', STR_PAD_LEFT)
                . str_pad((string) $data['d'], 2, '0', STR_PAD_LEFT)
                . str_pad((string) $data['H'], 2, '0', STR_PAD_LEFT)
                . str_pad((string) $data['i'], 2, '0', STR_PAD_LEFT)
                . str_pad((string) $data['s'], 2, '0', STR_PAD_LEFT)
                . $data['UTCSym']
                . str_pad((string) $data['UTCm'], 2, '0', STR_PAD_LEFT)
                . str_pad((string) $data['UTCs'], 2, '0', STR_PAD_LEFT)
        );

        return [$attrName, new static($value)];
    }

    public function encode(): string
    {
        preg_match('/([+-])(\d{2}):(\d{2})/', $this->value->getOffsetString(), $matches);

        return pack('n', 11) . pack('n', $this->value->format('Y'))
            . pack('c', $this->value->format('m'))
            . pack('c', $this->value->format('d'))
            . pack('c', $this->value->format('H'))
            . pack('c', $this->value->format('i'))
            . pack('c', $this->value->format('s'))
            . pack('c', 0)
            . pack('a', $matches[1])
            . pack('c', self::unpad($matches[2]))
            . pack('c', self::unpad($matches[3]));
    }

    private static function unpad(string $str)
    {
        $unpaddedStr = ltrim($str, '0');
        if ($unpaddedStr === '') {
            $unpaddedStr = '0';  // Ensure "00" becomes "0"
        }

        return $unpaddedStr;
    }
}
