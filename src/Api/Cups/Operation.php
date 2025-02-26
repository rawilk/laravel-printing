<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

class Operation
{
    // public const RESERVED_UNUSED = 0x0000;
    // public const RESERVED_UNUSED = 0x0001;
    public const PRINT_JOB = 0x0002;

    public const PRINT_URI = 0x0003;

    public const VALIDATE_JOB = 0x0004;

    public const CREATE_JOB = 0x0005;

    public const SEND_DOCUMENT = 0x0006;

    public const SEND_URI = 0x0007;

    public const CANCEL_JOB = 0x0008;

    public const GET_JOB_ATTRIBUTES = 0x0009;

    public const GET_JOBS = 0x000A;

    public const GET_PRINTER_ATTRIBUTES = 0x000B;

    public const HOLD_JOB = 0x000C;

    public const RELEASE_JOB = 0x000D;

    public const RESTART_JOB = 0x000E;
    // public const RESERVED_UNUSED = 0x000F;

    public const PAUSE_PRINTER = 0x0010;

    public const RESUME_PRINTER = 0x0011;

    public const PURGE_JOBS = 0x0012;
    // public const RESERVED_UNUSED = 0x0013-0x3FFF;
    // public const RESERVED_UNUSED = 0x4000-0x8FFF;

    public const SET_PRINTER_ATTIRBUTES = 0x0013;

    public const SET_JOB_ATTRIBUTES = 0x0014;

    public const GET_PRINTER_SUPPORTED_VALUES = 0x0015;

    public const CREATE_PRINTER_SUBSCRIPTIONSS = 0x0016;

    public const CREATE_JOB_SUBSCRIPTIONS = 0x0017;

    public const GET_SUBSCRIPTION_ATTRIBUTESS = 0x0018;

    public const GET_SUBSCRIPTIONS = 0x0019;

    public const RENEW_SUBSCRIPTION = 0x001A;

    public const CANCEL_SUBSCRIPTION = 0x001B;

    public const GET_NOTIFICATIONS = 0x001C;

    public const SEND_NOTIFICATIONS = 0x001D;

    public const GET_RESOURCE_ATTRIBUTES = 0x001E;

    public const GET_RESOURCE_DATA = 0x001F;

    public const GET_RESOURCES = 0x0020;

    public const GET_PRINTER_SUPPORTED_FILES = 0x0021;

    public const ENABLE_PRINTER = 0x0022;

    public const DISABLE_PRINTER = 0x0023;

    public const PAUSE_PRINTER_AFTER_CURRENT_JOB = 0x0024;

    public const HOLD_NEW_JOBS = 0x0025;

    public const RELEASE_HELD_NEW_JOBS = 0x0026;

    public const DEACTIVATE_PRINTER = 0x0027;

    public const ACTIVATE_PRINTER = 0x0028;

    public const RESTART_PRINTER = 0x0029;

    public const SHUTDOWN_PRINTER = 0x002A;

    public const START_PRINTER = 0x002B;

    public const REPROCESS_JOB = 0x002C;

    public const CANCEL_CURRENT_JOB = 0x002D;

    public const SUSPEND_CURRENT_JOB = 0x002E;

    public const RESUME_JOB = 0x002F;

    public const PROMOTE_JOB = 0x0030;

    public const SCHEDULE_JOB_AFTER = 0x0031;

    // public const RESERVED_UNUSED = 0x0032;
    public const CANCEL_DOCUMENT = 0x0033;

    public const GET_DOCUMENT_ATTRIBUTES = 0x0034;

    public const GET_DOCUMENTS = 0x0035;

    public const DELETE_DOCUMENT = 0x0036;

    public const SET_DOCUMENT_ATTRIBUTES = 0x0037;

    public const CANCEL_JOBS = 0x0038;

    public const CANCEL_MY_JOBS = 0x0039;

    public const RESUBMIT_JOB = 0x003A;

    public const CLOSE_JOB = 0x003B;

    public const IDENTIFY_PRINTER = 0x003C;

    public const VALIDATE_DOCUMENT = 0x003D;

    public const ADD_DOCUMENT_IMAGES = 0x003E;

    public const ACKNOWLEDGE_DOCUMENT = 0x003F;

    // CUPS specific operations
    public const CUPS_GET_DEFAULT = 0x4001;

    public const CUPS_GET_PRINTERS = 0x4002;

    public const CUPS_ADD_MODIFY_PRINTER = 0x4003;

    public const CUPS_DELETE_PRINTER = 0x4004;

    public const CUPS_GET_CLASSES = 0x4005;

    public const CUPS_ADD_MODIFY_CLASSES = 0x4006;

    public const CUPS_DELETE_CLASS = 0x4007;

    public const CUPS_ACCEPT_JOBS = 0x4008;

    public const CUPS_REJECT_JOBS = 0x4009;

    public const CUPS_SET_DEFAULT = 0x400A;

    public const CUPS_GET_DEVICES = 0x400B;

    public const CUPS_GET_PPDS = 0x400C;

    public const CUPS_MOVE_JOB = 0x400D;

    public const CUPS_AUTHENTICATE_JOB = 0x400E;

    public const CUPS_GET_PPD = 0x400F;

    public const CUPS_GET_DOCUMENT = 0x4027;

    public const CUPS_CREATE_LOCAL_PRINTER = 0x4028;
}
