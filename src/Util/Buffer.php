<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use Countable;
use SplFixedArray;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Exceptions\InputValidationException;

/**
 * A convenient wrapper class around an array of bytes (int's).
 */
class Buffer implements Countable
{
    public const TYPE_STRING = 'string';
    public const TYPE_BYTE = 'byte';
    public const TYPE_SHORT = 'short';
    public const TYPE_INT = 'int';
    public const TYPE_LONG = 'long';
    public const TYPE_FLOAT = 'float';

    public const FORMAT_CHAR_SIGNED = 'c';
    public const FORMAT_CHAR_UNSIGNED = 'C';
    public const FORMAT_SHORT_16_SIGNED = 's';
    public const FORMAT_SHORT_16_UNSIGNED = 'v';
    public const FORMAT_LONG_32_SIGNED = 'l';
    public const FORMAT_LONG_32_UNSIGNED = 'V';
    public const FORMAT_LONG_LONG_64_SIGNED = 'q';
    public const FORMAT_LONG_LONG_64_UNSIGNED = 'P';
    public const FORMAT_FLOAT = 'e';

    /**
     * @var array<int>
     */
    protected array $data;

    /**
     * @var bool is this a signed or unsigned value?
     */
    protected ?bool $signed = null;

    /**
     * @var ?string $datatype
     */
    protected ?string $datatype = null;

    /**
     * @param mixed $value
     * @param ?string $datatype
     * @param ?bool $signed
     */
    public function __construct(mixed $value = null, ?string $datatype = null, ?bool $signed = null)
    {
        $this->datatype = $datatype;
        $this->signed = $signed;

        $isString = is_string($value);
        $isNumeric = is_numeric($value);

        if ($isString || $isNumeric) {
            $this->datatype = $datatype;
            $this->signed = $signed;

            // unpack returns an array indexed at 1.
            $this->data = $isString
                ? array_values(unpack("C*", $value) ?: [])
                : array_values(unpack("C*", pack($this->computedFormat(), $value)) ?: []);
        } elseif (is_array($value)) {
            $this->data = $value;
        } elseif ($value instanceof PublicKey) {
            $this->data = $value->toBytes();
        } elseif ($value instanceof Buffer) {
            $this->data = $value->toArray();
            $this->datatype = $value->datatype;
            $this->signed = $value->signed;
        } elseif (null == $value) {
            $this->data = [];
        } elseif (method_exists($value, 'toArray')) {
            // @phpstan-ignore-next-line
            $this->data = $value->toArray();
        } else {
            throw new InputValidationException('Unsupported $value for Buffer: ' . get_class($value));
        }
    }

    /**
     * @param mixed $value
     * @param ?string $format
     * @param ?bool $signed
     * @return Buffer
     */
    public static function from(mixed $value = null, ?string $format = null, ?bool $signed = null): Buffer
    {
        return new Buffer($value, $format, $signed);
    }

    /**
     * For convenience.
     *
     * @param string $value
     * @return Buffer
     */
    public static function fromBase58(string $value): Buffer
    {
        $value = PublicKey::base58()->decode($value);

        return new Buffer($value);
    }

    /**
     * @return array<int>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param int $len
     * @param int $val
     * @return $this
     */
    public function pad(int $len, int $val = 0): Buffer
    {
        $this->data = array_pad($this->data, $len, $val);

        return $this;
    }

    /**
     * @param mixed $source
     * @return Buffer
     */
    public function push(mixed $source): Buffer
    {
        $sourceAsBuffer = Buffer::from($source);

        array_push($this->data, ...$sourceAsBuffer->toArray());

        return $this;
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @param ?string $format
     * @param ?bool $signed
     * @return Buffer
     */
    public function slice(int $offset, ?int $length = null, ?string $format = null, ?bool $signed = null): Buffer
    {
        return static::from(array_slice($this->data, $offset, $length), $format, $signed);
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @return Buffer
     */
    public function splice(int $offset, ?int $length = null): Buffer
    {
        return static::from(array_splice($this->data, $offset, $length));
    }

    /**
     * @return int|null
     */
    public function shift(): ?int
    {
        return array_shift($this->data);
    }

    /**
     * @param int $size
     * @return Buffer
     */
    public function fixed(int $size): Buffer
    {
        $fixedSizeData = SplFixedArray::fromArray($this->data);
        $fixedSizeData->setSize($size);
        // @phpstan-ignore-next-line
        $this->data = $fixedSizeData->toArray();

        return $this;
    }

    /**
     * Return binary representation of $value.
     *
     * @return array<int>
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Return binary string representation of $value.
     * @return string
     */
    public function toString(): string
    {
        return pack('C*', ...$this->toArray());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Return string representation of $value.
     *
     * @return string
     */
    public function toBase58String(): string
    {
        return PublicKey::base58()->encode($this->toString());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return sizeof($this->toArray());
    }

    /**
     * Convert the binary array to its corresponding value derived from $datatype, $signed, and sizeof($data).
     *
     * Note: it is expected that the ->fixed($length) method has already been called.
     *
     * @param int|null $length
     * @return mixed
     */
    public function value(?int $length = null): mixed
    {
        if ($length) {
            $this->fixed($length);
        }

        if (self::TYPE_STRING === $this->datatype) {
            return ord(pack("C*", ...$this->toArray()));
        } else {
            return (unpack($this->computedFormat(), pack("C*", ...$this->toArray())) ?: [])[1] ?: null;
        }
    }

    /**
     * @return string
     * @throws InputValidationException
     */
    protected function computedFormat(): string
    {
        if (! $this->datatype) {
            throw new InputValidationException(
                'Trying to calculate format of unspecified buffer. Please specify a datatype.'
            );
        }

        switch ($this->datatype) {
            case self::TYPE_STRING:
                return self::FORMAT_CHAR_UNSIGNED;
            case self::TYPE_BYTE:
                return $this->signed ? self::FORMAT_CHAR_SIGNED : self::FORMAT_CHAR_UNSIGNED;
            case self::TYPE_SHORT:
                return $this->signed ? self::FORMAT_SHORT_16_SIGNED : self::FORMAT_SHORT_16_UNSIGNED;
            case self::TYPE_INT:
                return $this->signed ? self::FORMAT_LONG_32_SIGNED : self::FORMAT_LONG_32_UNSIGNED;
            case self::TYPE_LONG:
                return $this->signed ? self::FORMAT_LONG_LONG_64_SIGNED : self::FORMAT_LONG_LONG_64_UNSIGNED;
            case self::TYPE_FLOAT:
                return self::FORMAT_FLOAT;
            default:
                throw new InputValidationException("Unsupported datatype.");
        }
    }
}
