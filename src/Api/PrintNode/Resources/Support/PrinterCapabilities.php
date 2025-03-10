<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources\Support;

use Rawilk\Printing\Api\PrintNode\PrintNodeObject;

/**
 * A `PrinterCapabilities` represents the reported capabilities of its associated
 * `Printer` in the PrintNode API.
 *
 * @see https://www.printnode.com/en/docs/api/curl#printer-capabilities
 *
 * @property-read array $bins The paper tray names the printer driver supports. May be zero length.
 * @property-read bool $collate Indicates `true` if the printer supports collation.
 * @property-read int $copies The maximum number of copies the printer supports. If the printer does not
 *      support multiple copies this value will be `1`.
 * @property-read bool $color Indicates `true` if the printer is a color printer.
 * @property-read array $dpis An array of strings, each of which describes a dpi setting supported by the Printer.
 *      May be zero length.
 * @property-read array $extent If the printer driver reports its maximum and minimum supported paper sizes, this
 *      is a two-dimensional array of integers, where [0][0] and [0][1] are respectively the minimum supported width
 *      and height and [1][0] and [1][1] are respectively the maximum supported width and height. The units are
 *      tenths of a mm. If the printer does not report this information, this is a zero-length array.
 * @property-read array $medias An array of media names the printer driver supports. May be zero-length.
 * @property-read array $nup The set of values of N for which <a href="https://en.wikipedia.org/wiki/N-up">N-up printing</a> is supported, or a
 *      zero-length array if N-up printing is not supported.
 * @property-read array $papers The paper sizes that are supported by the printer. Each key represents a paper name
 *      and the corresponding value is the dimension of the paper expressed in a two-value array. The array is
 *      expressed as `[width, height]`, with `width` and `height` expressed in tenths of a mm. In some
 *      circumstances these values are not reported by the printer driver, in which case the array
 *      is `[null, null]`.
 * @property-read null|\Rawilk\Printing\Api\PrintNode\Resources\Support\PrintRate $printrate The printer's supported print rate.
 * @property-read bool $supports_custom_paper_size Indicates `true` if the printer supports custom paper sizes.
 */
class PrinterCapabilities extends PrintNodeObject
{
    // Alias for bins
    public function trays(): array
    {
        return $this->bins;
    }

    protected function getExpectedValueResource(string $key): ?string
    {
        return match ($key) {
            'printrate' => PrintRate::class,
            default => null,
        };
    }
}
