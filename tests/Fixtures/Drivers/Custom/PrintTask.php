<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Fixtures\Drivers\Custom;

use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\PrintTask as BasePrintTask;
use Rawilk\Printing\Tests\Fixtures\Drivers\Custom\Entity\PrintJob as CustomDriverPrintJob;

final class PrintTask extends BasePrintTask
{
    public function range($start, $end = null): self
    {
        return $this;
    }

    public function send(): PrintJob
    {
        return new CustomDriverPrintJob($this->getPrinter());
    }

    private function getPrinter(): Printer
    {
        return Printing::printer($this->printerId);
    }
}
