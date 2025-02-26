<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

enum Operation: int
{
    case PrintJob = 0x0002;
    case PrintUri = 0x0003;
    case ValidateJob = 0x0004;
    case CreateJob = 0x0005;
    case SendDocument = 0x0006;
    case SendUri = 0x0007;
    case CancelJob = 0x0008;
    case GetJobAttributes = 0x0009;
    case GetJobs = 0x000A;
    case GetPrinterAttributes = 0x000B;
    case HoldJob = 0x000C;
    case ReleaseJob = 0x000D;
    case RestartJob = 0x000E;
    case PausePrinter = 0x0010;
    case ResumePrinter = 0x0011;
    case PurgeJobs = 0x0012;
    case SetPrinterAttributes = 0x0013;
    case SetJobAttributes = 0x0014;
    case GetPrinterSupportedValues = 0x0015;
    case CreatePrinterSubscriptions = 0x0016;
    case CreateJobSubscriptions = 0x0017;
    case GetSubscriptionAttributes = 0x0018;
    case GetSubscriptions = 0x0019;
    case RenewSubscription = 0x001A;
    case CancelSubscription = 0x001B;
    case GetNotifications = 0x001C;
    case SendNotifications = 0x001D;
    case GetResourceAttributes = 0x001E;
    case GetResourceData = 0x001F;
    case GetResources = 0x0020;
    case GetPrinterSupportedFiles = 0x0021;
    case EnablePrinter = 0x0022;
    case DisablePrinter = 0x0023;
    case PausePrinterAfterCurrentJob = 0x0024;
    case HoldNewJobs = 0x0025;
    case ReleaseHeldNewJobs = 0x0026;
    case DeactivatePrinter = 0x0027;
    case ActivatePrinter = 0x0028;
    case RestartPrinter = 0x0029;
    case ShutdownPrinter = 0x002A;
    case StartPrinter = 0x002B;
    case ReprocessJob = 0x002C;
    case CancelCurrentJob = 0x002D;
    case SuspendCurrentJob = 0x002E;
    case ResumeJob = 0x002F;
    case PromoteJob = 0x0030;
    case ScheduleJobAfter = 0x0031;
    case CancelDocument = 0x0033;
    case GetDocumentAttributes = 0x0034;
    case GetDocuments = 0x0035;
    case DeleteDocument = 0x0036;
    case SetDocumentAttributes = 0x0037;
    case CancelJobs = 0x0038;
    case CancelMyJobs = 0x0039;
    case ResubmitJob = 0x003A;
    case CloseJob = 0x003B;
    case IdentifyPrinter = 0x003C;
    case ValidateDocument = 0x003D;
    case AddDocumentImages = 0x003E;
    case AcknowledgeDocument = 0x003F;

    // CUPS specific operations
    case CupsGetDefault = 0x4001;
    case CupsGetPrinters = 0x4002;
    case CupsAddModifyPrinter = 0x4003;
    case CupsDeletePrinter = 0x4004;
    case CupsGetClasses = 0x4005;
    case CupsAddModifyClasses = 0x4006;
    case CupsDeleteClass = 0x4007;
    case CupsAcceptJobs = 0x4008;
    case CupsRejectJobs = 0x4009;
    case CupsSetDefault = 0x400A;
    case CupsGetDevices = 0x400B;
    case CupsGetPpds = 0x400C;
    case CupsMoveJob = 0x400D;
    case CupsAuthenticateJob = 0x400E;
    case CupsGetPpd = 0x400F;
    case CupsGetDocument = 0x4027;
    case CupsCreateLocalPrinter = 0x4028;
}
