<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\PrintNode\Fixtures;

use Illuminate\Support\Str;
use PrintNode\Client;
use PrintNode\Entity\Printer;

/**
 * @method self setId(string $id)
 * @method self setDescription(string $description)
 * @method self setState(string $status)
 * @method self setCapabilities(object $capabilities)
 * @method self setName(string $name)
 */
class PrintNodePrinter extends Printer
{
    public function __construct(Client $parentClient)
    {
        parent::__construct($parentClient);

        $this
            ->setState('online')
            ->setCapabilities(
                (object) [
                    'bins' => [
                        'tray 1',
                    ],
                ],
            );
    }

    protected function setAttribute(string $key, $value): self
    {
        $this->$key = $value;

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'set')) {
            return $this->setAttribute(Str::camel(Str::after($name, 'set')), ...$arguments);
        }

        return $this;
    }
}
