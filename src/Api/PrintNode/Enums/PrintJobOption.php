<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Enums;

use Rawilk\Printing\Exceptions\InvalidOption;

/**
 * @see https://www.printnode.com/en/docs/api/curl#printjob-options
 */
enum PrintJobOption: string
{
    /**
     * The name of one of the paper trays or output bins reported
     * by the printer capability property `bins`.
     *
     * Type: String
     */
    case Bin = 'bin';

    /**
     * Enables print copy collation when printing multiple copies. If this option is not
     * specified the printer default is used.
     *
     * Type: Boolean
     */
    case Collate = 'collate';

    /**
     * Set this to `false` for grayscale printing. This option only takes effect on Windows, with the
     * default printing backend of the PrintNode Client set to `Engine6` (you can change the default
     * printing backend using the drop-down list in the "Printers" tab of the PrintNode Client's
     * GUI). If this option is not specified the printer default is used.
     *
     * Type: Boolean
     */
    case Color = 'color';

    /**
     * The number of copies to be printed. Defaults to `1`. Maximum value as reported by the printer
     * capabilities property `copies`.
     *
     * Type: Integer
     */
    case Copies = 'copies';

    /**
     * The dpi setting to use for the print job. Allowed values are those reported by the printer capability
     * property `dpis`.
     *
     * Type: String
     */
    case Dpi = 'dpi';

    /**
     * One of `long-edge` or `short-edge` for two-sided printing along the long-edge
     * (portrait) or the short edge (landscape) respectively, or `one-sided` for single-side
     * printing. If this option is not specified the printer default is used.
     *
     * Type: String
     */
    case Duplex = 'duplex';

    /**
     * Set this to `true` to automatically fit the document to the page. In Windows, this
     * option is only supported when using the `Engine6` printing backend.
     *
     * Type: Boolean
     */
    case FitToPage = 'fit_to_page';

    /**
     * The name of the medium to use for the print job. This must be one of the values reported
     * by the printer capability property `medias`. Some printers on macOS/OS X ignore this setting
     * unless the `bin` (paper tray) option is also set.
     *
     * Type: String
     */
    case Media = 'media';

    /**
     * macOS/OS X only. Allows support for printing multiple pages per physical sheet of paper.
     * Defaults to `1`. This must be one of the values reported by the printer capability
     * property `nup`.
     *
     * Type: Integer
     */
    case Nup = 'nup';

    /**
     * A set of pages to print from a PDF. The format is the same as the one commonly
     * used in print dialogs in applications. A few examples:
     *
     * -> `1,3` prints pages 1 and 3
     * -> `-5` prints pages 1 through 5 inclusive
     * -> `-` prints all pages
     * -> `1,3-` prints all pages except page 2
     *
     * Type: String
     */
    case Pages = 'pages';

    /**
     * The name of the paper size to use. This must be one of the keys in the object
     * returned by the printer capability property `papers`.
     *
     * Type: String
     */
    case Paper = 'paper';

    /**
     * One of `0`, `90`, `180` or `270`. This sets the rotation angle of each page in the print.
     * `0` for portrait, `90` for landscape, `180` for inverted portrait and `270` for inverted
     * landscape. This setting is absolute and not relative. For example, if your PDF document
     * is in landscape format, setting this option to `90` will leave it unchanged.
     *
     * Type: Integer
     */
    case Rotate = 'rotate';

    public function validate(mixed $value): void
    {
        $verifyString = function () use ($value): void {
            throw_unless(
                is_string($value),
                InvalidOption::class,
                'The "' . $this->value . '" option must be a string',
            );
        };

        $verifyInteger = function () use ($value): void {
            throw_unless(
                is_int($value),
                InvalidOption::class,
                'The "' . $this->value . '" option must be an integer',
            );
        };

        switch ($this) {
            case self::Bin:
            case self::Dpi:
            case self::Media:
            case self::Pages:
            case self::Paper:
                $verifyString();

                break;

            case self::Collate:
            case self::Color:
            case self::FitToPage:
                throw_unless(
                    is_bool($value),
                    InvalidOption::class,
                    'The "' . $this->value . '" option must be a boolean value'
                );

                break;

            case self::Copies:
                $verifyInteger();

                throw_if(
                    $value < 1,
                    InvalidOption::class,
                    'The "' . $this->value . '" option must be at least 1',
                );

                break;

            case self::Duplex:
                $verifyString();

                $supportedValues = ['long-edge', 'short-edge', 'one-sided'];

                throw_unless(
                    in_array($value, $supportedValues, true),
                    InvalidOption::class,
                    'The "' . $this->value . '" option value provided ("' . $value . '") is not supported. Must be one of: ' .
                    implode(', ', array_map(
                        fn ($v) => '"' . $v . '"',
                        $supportedValues,
                    )),
                );

                break;

            case self::Nup:
                $verifyInteger();

                break;

            case self::Rotate:
                $verifyInteger();

                $supportedValues = [0, 90, 180, 270];

                throw_unless(
                    in_array($value, $supportedValues, true),
                    InvalidOption::class,
                    'The provided value for the "' . $this->value . '" option (' . $value . ') is not valid. Must be one of: ' .
                    implode(', ', $supportedValues),
                );

                break;
        }
    }
}
