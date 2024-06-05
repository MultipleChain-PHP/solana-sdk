<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Borsh;

use MultipleChain\SolanaSDK\Util\Buffer;
use MultipleChain\SolanaSDK\Exceptions\TodoException;

class Borsh
{
    /**
     * @param array<mixed> $schema
     * @param object $object
     * @return array<mixed>
     */
    public static function serialize(
        array $schema,
        object $object
    ): array {
        $writer = new BinaryWriter();
        static::serializeObject($schema, $object, $writer);
        return $writer->toArray();
    }

    /**
     * @param array<mixed> $schema
     * @param object $object
     * @param BinaryWriter $writer
     * @return void
     */
    protected static function serializeObject(
        array $schema,
        object $object,
        BinaryWriter $writer
    ): void {
        $objectSchema = $schema[get_class($object)] ?? null;
        if (! $objectSchema) {
            $class = get_class($object);
            throw new BorshException("Class {$class} is missing in schema");
        }

        if ('struct' === $objectSchema['kind']) {
            foreach ($objectSchema['fields'] as list($fieldName, $fieldType)) {
                static::serializeField($schema, $fieldName, $object->{$fieldName}, $fieldType, $writer);
            }
        } elseif ('enum' === $objectSchema['kind']) {
            throw new TodoException("TODO: Enums don't exist in PHP yet???");
        } else {
            $kind = $objectSchema['kind'];
            $class = get_class($object);
            throw new BorshException("Unexpected schema kind: {$kind} for {$class}");
        }
    }

    /**
     * @param array<mixed> $schema
     * @param string|null $fieldName
     * @param mixed $value
     * @param mixed $fieldType
     * @param BinaryWriter $writer
     * @return void
     */
    protected static function serializeField(
        array $schema,
        ?string $fieldName,
        mixed $value,
        mixed $fieldType,
        BinaryWriter $writer
    ): void {
        if (is_string($fieldType)) {
            $writer->{'write' . ucfirst($fieldType)}($value);
        } elseif (is_array($fieldType) && isset($fieldType[0])) { // sequential array
            if (is_int($fieldType[0])) {
                if (sizeof($value) !== $fieldType[0]) {
                    $sizeOf = sizeof($value);
                    throw new BorshException(
                        "Expecting byte array of length {$fieldType[0]}, but got ${$sizeOf} bytes"
                    );
                }
                $writer->writeFixedArray($value);
            } elseif (2 === sizeof($fieldType) && is_int($fieldType[1])) {
                if (sizeof($value) !== $fieldType[1]) {
                    $sizeOf = sizeof($value);
                    throw new BorshException(
                        "Expecting byte array of length {$fieldType[1]}, but got ${$sizeOf} bytes"
                    );
                }

                for ($i = 0; $i < $fieldType[1]; $i++) {
                    static::serializeField($schema, null, $value[$i], $fieldType[0], $writer);
                }
            } else {
                $writer->writeArray(
                    $value,
                    fn ($item) => static::serializeField($schema, $fieldName, $item, $fieldType[0], $writer)
                );
            }
        } elseif (isset($fieldType['kind'])) { // associative array
            switch ($fieldType['kind']) {
                case 'option':
                    if ($value) {
                        $writer->writeU8(1);
                        static::serializeField($schema, $fieldName, $value, $fieldType['type'], $writer);
                    } else {
                        $writer->writeU8(0);
                    }
                    break;
                default:
                    throw new BorshException("FieldType {$fieldType['kind']} unrecognized");
            }
        } else {
            static::serializeObject($schema, $value, $writer);
        }
    }

    /**
     * @param array<mixed> $schema
     * @param string $class
     * @param array<mixed> $buffer
     * @return mixed
     */
    public static function deserialize(
        array $schema,
        string $class,
        array $buffer
    ): mixed {
        $reader = new BinaryReader(Buffer::from($buffer));
        return static::deserializeObject($schema, $class, $reader);
    }

    /**
     * @param array<mixed> $schema
     * @param string $class
     * @param BinaryReader $reader
     * @return mixed
     */
    protected static function deserializeObject(
        array $schema,
        string $class,
        BinaryReader $reader
    ): mixed {
        $objectSchema = $schema[$class] ?? null;
        if (!$objectSchema) {
            throw new BorshException("Class {$class} is missing in schema");
        }

        if ('struct' === $objectSchema['kind']) {
            if (!method_exists($class, 'borshConstructor')) {
                throw new BorshException(
                    "Class {$class} does not implement borshConstructor. Please use the BorshDeserialize trait."
                );
            }

            $result = $class::borshConstructor();
            foreach ($objectSchema['fields'] as list($fieldName, $fieldType)) {
                $result->{$fieldName} = static::deserializeField($schema, $fieldName, $fieldType, $reader);
            }
            return $result;
        }

        if ('enum' === $objectSchema['kind']) {
            // TODO: Enums already exist in PHP, but we need to figure out how to implement them
            throw new TodoException("TODO: Enums don't exist in PHP yet???");
        }

        $kind = $objectSchema['kind'];
        throw new BorshException("Unexpected schema kind: {$kind} for {$class}");
    }

    /**
     * @param array<mixed> $schema
     * @param string|null $fieldName
     * @param mixed $fieldType
     * @param BinaryReader $reader
     * @return mixed
     */
    protected static function deserializeField(
        array $schema,
        ?string $fieldName,
        mixed $fieldType,
        BinaryReader $reader
    ): mixed {
        if (is_string($fieldType) && !class_exists($fieldType)) {
            return $reader->{'read' . ucfirst($fieldType)}();
        }

        if (is_array($fieldType) && isset($fieldType[0])) { // sequential array
            if (is_int($fieldType[0])) {
                return $reader->readFixedArray($fieldType[0]);
            } elseif (2 === sizeof($fieldType) && is_int($fieldType[1])) {
                $array = [];
                for ($i = 0; $i < $fieldType[1]; $i++) {
                    array_push($array, static::deserializeField($schema, null, $fieldType[0], $reader));
                }
                return $array;
            } else {
                return $reader->readArray(
                    fn () => static::deserializeField($schema, $fieldName, $fieldType[0], $reader)
                );
            }
        }

        if (isset($fieldType['kind']) && 'option' === $fieldType['kind']) { // associative array
            $option = $reader->readU8();
            if ($option) {
                return static::deserializeField($schema, $fieldName, $fieldType['type'], $reader);
            }

            return null;
        }

        // @phpstan-ignore-next-line
        return static::deserializeObject($schema, $fieldType, $reader);
    }
}
