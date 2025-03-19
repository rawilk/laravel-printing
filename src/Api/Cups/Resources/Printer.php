<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Resources;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\Cups\CupsObject;
use Rawilk\Printing\Api\Cups\Enums\PrinterState;
use Rawilk\Printing\Api\Cups\Enums\PrinterStateReason;

/**
 * A `Printer` represents a physical printer installed on a CUPS server.
 *
 * @property-read string $uri The printer's uri. Alias to `$printerUriSupported`
 * @property-read string $printerUriSupported The printer's uri.
 * @property-read int $printerState An integer representation of the printer's status.
 * @property-read string $printerName The name of the printer.
 * @property-read array $mediaSourceSupported The media (trays (I think)) the printer supports.
 * @property-read null|string $printerInfo A description of the printer.
 * @property-read array $printerStateReasons A more detailed list of the printer's status.
 */
class Printer extends CupsObject
{
    /**
     * @return array<string, \Rawilk\Printing\Api\Cups\Type|array>
     */
    public function capabilities(): array
    {
        return array_filter(
            $this->_values,
            fn (string $key): bool => ! in_array($key, [
                'printer-uri-supported',
                'uri',
                'printer-state',
                'printer-name',
                'printer-info',
            ], true),
            ARRAY_FILTER_USE_KEY,
        );
    }

    public function state(): ?PrinterState
    {
        return PrinterState::tryFrom($this->printerState);
    }

    /**
     * @return Collection<int, \Rawilk\Printing\Api\Cups\Enums\PrinterStateReason>
     */
    public function stateReasons(): Collection
    {
        return collect($this->printerStateReasons)
            ->map(fn (string $reason) => PrinterStateReason::tryFrom($reason))
            ->filter();
    }

    public function isOnline(): bool
    {
        // First check if any of the reported state reasons are "offline".
        $offline = $this->stateReasons()->first(
            fn (PrinterStateReason $reason): bool => $reason->isOffline()
        );

        if ($offline) {
            return false;
        }

        return $this->state()?->isOnline() ?? false;
    }

    public function trays(): array
    {
        return $this->mediaSourceSupported ?? [];
    }

    protected function mutateAttributes(array $values): array
    {
        $values['printer-uri-supported'] = $this->attributeValue($values, 'printer-uri-supported');
        $values['printer-state'] = $this->attributeValue($values, 'printer-state', PrinterState::Stopped->value);
        $values['printer-name'] = $this->attributeValue($values, 'printer-name');
        $values['media-source-supported'] = $this->attributeValue($values, 'media-source-supported', []);
        $values['printer-info'] = $this->attributeValue($values, 'printer-info');
        $values['printer-state-reasons'] = data_get($values, 'printer-state-reasons', []);

        return $values;
    }
}
