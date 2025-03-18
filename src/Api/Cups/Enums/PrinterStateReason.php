<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

/**
 * Note: The values for this enum were generated with AI, so they may
 * be inaccurate or incomplete. Please submit a PR if any values
 * are incorrect or missing.
 */
enum PrinterStateReason: string
{
    // region General Printer Conditions
    /** No issues; the printer is in a normal state. */
    case None = 'none';

    /** Ink or toner is running low. */
    case MarkerSupplyLow = 'marker-supply-low';

    /** Ink or toner is completely empty. */
    case MarkerSupplyEmpty = 'marker-supply-empty';

    /** Paper is running low. */
    case MediaLow = 'media-low';

    /** No paper is available in the tray. */
    case MediaEmpty = 'media-empty';
    // endregion

    // region Connectivity & Offline Issues
    /** The printer is turned off or not responding. */
    case Offline = 'offline';

    /** The printer is turned off manually. */
    case Shutdown = 'shutdown';

    /** The printer has lost power. */
    case PowerOff = 'power-off';

    /** The printer is trying to establish a connection. */
    case ConnectingToDevice = 'connecting-to-device';

    /** The connection to the printer timed out. */
    case TimedOut = 'timed-out';
    // endregion

    // region User & Administrative Actions
    /** The printer is paused by an administrator. */
    case Paused = 'paused';

    /** The printer is transitioning to a paused state. */
    case MovingToPaused = 'moving-to-paused';

    /** The printer has pending jobs but hasn't started processing yet. */
    case CupsPendingJob = 'cups-pending-job';

    /** The printer is currently processing a job. */
    case CupsProcessingJob = 'cups-processing-job';

    /** The printer was stopped manually by an administrator. */
    case CupsStopped = 'cups-stopped';
    // endregion

    // region Physical & Hardware Issues
    /** A physical door on the printer (e.g., toner or paper tray cover) is open. */
    case DoorOpen = 'door-open';

    /** A safety interlock is open, preventing operation. */
    case InterlockOpen = 'interlock-open';

    /** The printer requires paper to be loaded. */
    case MediaNeeded = 'media-needed';

    /** The toner is completely out. */
    case TonerEmpty = 'toner-empty';

    /** The waste toner container is full and needs to be emptied. */
    case WasteTonerReceptacleFull = 'waste-toner-receptacle-full';

    /** The developer unit (used in some laser printers) is empty. */
    case DeveloperEmpty = 'developer-empty';

    /** The printer's fuser is overheating. */
    case FuserOverTemp = 'fuser-over-temp';

    /** The printer's fuser is too cold. */
    case FuserUnderTemp = 'fuser-under-temp';

    /** A paper jam has occurred. */
    case MediaJam = 'media-jam';
    // endregion

    // region Job & Printing Errors
    /** The last print job finished successfully. */
    case JobCompletedSuccessfully = 'job-completed-successfully';

    /** The last print job finished, but some errors occurred. */
    case JobCompletedWithErrors = 'job-completed-with-errors';

    /** A user manually canceled the print job. */
    case JobCanceledByUser = 'job-canceled-by-user';

    /** An administrator or operator canceled the job. */
    case JobCanceledByOperator = 'job-canceled-by-operator';

    /** The printer itself canceled the job. */
    case JobCanceledAtDevice = 'job-canceled-at-device';

    /** A recoverable error occurred (e.g., low ink, minor issues). */
    case PrinterRecoverableFailure = 'printer-recoverable-failure';

    /** The printer stopped, but might resume later. */
    case PrinterStoppedPartly = 'printer-stopped-partly';
    // endregion

    // region Environmental & Service Issues
    /** The printer is requesting maintenance or service. */
    case ServiceRequested = 'service-requested';

    /** The printer is in the process of warming up. */
    case MovingToWarmup = 'moving-to-warmup';

    /** The printer is shutting down. */
    case Stopping = 'stopping';

    /** The printer is only partially stopped and may resume. */
    case StoppedPartly = 'stopped-partly';
    // endregion

    public function isOffline(): bool
    {
        return in_array($this, [
            self::Offline,
            self::Shutdown,
            self::PowerOff,
            self::Paused,
            self::CupsStopped,
            self::MovingToPaused,
            self::TimedOut,
        ], true);
    }
}
