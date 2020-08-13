---
title: Printer
sort: 1
---

`Rawilk\Printing\Contracts\Printer`

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
        <td><code>id()</code></td>
        <td><code>int|string</code></td>
        <td>Retrieve the printer's id</td>
    </tr>
    <tr>
        <td><code>name()</code></td>
        <td><code>string|null</code></td>
        <td>Retrieve the printer's name</td>
    </tr>
    <tr>
        <td><code>description()</code></td>
        <td><code>string|null</code></td>
        <td>Retrieve the printer's description</td>
    </tr>
    <tr>
        <td><code>capabilities()</code></td>
        <td><code>array</code></td>
        <td>Retrieve the printer's capabilities</td>
    </tr>
    <tr>
        <td><code>trays()</code></td>
        <td><code>array</code></td>
        <td>Retrieve a printer's available trays</td>
    </tr>
    <tr>
        <td><code>status()</code></td>
        <td><code>string</code></td>
        <td>Get the printer's current status</td>
    </tr>
    <tr>
        <td><code>isOnline()</code></td>
        <td><code>bool</code></td>
        <td>Determines if the printer is currently online</td>
    </tr>
    <tr>
        <td><code>jobs()</code></td>
        <td><code>Illuminate\Support\Collection</code></td>
        <td>Returns the jobs for a printer. <strong>Note:</strong> this feature is not implemented at this time for PrintNode</td>
    </tr>
    <tr>
        <td><code>toArray()</code></td>
        <td><code>array</code></td>
        <td>Returns an array representation of the printer</td>
    </tr>
</x-table>
