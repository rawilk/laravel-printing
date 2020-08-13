---
title: PrintJob
sort: 3
---

`Rawilk\Printing\Contracts\PrintJob`

## Methods

<x-table>
    <x-slot name="thead">
        <tr>
            <th>Method</th>
            <th>Return Type</th>
            <th>Description</th>
        </tr>
    </x-slot>

    <tr>
        <td><code>date()</code></td>
        <td><code>DateTime|mixed</code></td>
        <td>The date the job was created</td>
    </tr>
    <tr>
        <td><code>id()</code></td>
        <td><code>int|string</code></td>
        <td>The id of the job</td>
    </tr>
    <tr>
        <td><code>name()</code></td>
        <td><code>string|null</code></td>
        <td>The name of the job</td>
    </tr>
    <tr>
        <td><code>printerId()</code></td>
        <td><code>int|string|mixed</code></td>
        <td>Returns the id of the printer the job was sent to, if available</td>
    </tr>
    <tr>
        <td><code>printerName()</code></td>
        <td><code>string|null</code></td>
        <td>Returns the name of the printer the job was sent to, if available</td>
    </tr>
    <tr>
        <td><code>state()</code></td>
        <td><code>string|null</code></td>
        <td>Returns the status of the job</td>
    </tr>
</x-table>
