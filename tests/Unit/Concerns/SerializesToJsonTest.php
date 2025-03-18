<?php

declare(strict_types=1);

use Rawilk\Printing\Concerns\SerializesToJson;

test('it serializes to json correctly', function () {
    $object = new class
    {
        use SerializesToJson;

        public function toArray(): array
        {
            return ['key' => 'value', 'another_key' => 123];
        }
    };

    $expectedJson = <<<'JSON'
    {
        "key": "value",
        "another_key": 123
    }
    JSON;

    expect($object->toJson())->toBe($expectedJson);
});

test('it implements jsonSerializable', function () {
    $object = new class implements JsonSerializable
    {
        use SerializesToJson;

        public function toArray(): array
        {
            return ['key' => 'value'];
        }
    };

    $expectedJson = '{"key":"value"}';

    expect(json_encode($object))->toBe($expectedJson);
});

test('it converts to string properly', function () {
    $object = new class
    {
        use SerializesToJson;

        public function toArray(): array
        {
            return ['key' => 'value'];
        }
    };

    $expectedString = get_class($object) . ' JSON: ' . <<<'JSON'
    {
        "key": "value"
    }
    JSON;

    expect((string) $object)->toBe($expectedString);
});
