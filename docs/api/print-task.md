---
title: PrintTask
sort: 2
---

`Rawilk\Printing\PrintTask`

## Methods
<x-table>
    <x-slot name="thead">
        <tr>
            <th>Method</th>
            <th>Params</th>
            <th>Returns</th>
            <th>Description</th>
        </tr>
    </x-slot>

    <tr>
        <td><code>content</code></td>
        <td><code>$content</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set the content to be printed</td>
    </tr>
    <tr>
        <td><code>file</code></td>
        <td><code>$filePath</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set the path to a *PDF* file to be printed</td>
    </tr>
    <tr>
        <td><code>url</code></td>
        <td><code>$url</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set a url to be printed</td>
    </tr>
    <tr>
        <td><code>jobTitle</code></td>
        <td><code>$jobTitle</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set the title of the print task. Defaults to a randomly generated id.</td>
    </tr>
    <tr>
        <td><code>printer</code></td>
        <td><code>$printerId</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set the id of the printer to print to. This method must be called when printing.</td>
    </tr>
    <tr>
        <td><code>printSource</code></td>
        <td><code>$printSource</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set a source of the print task. Defaults to the application name.</td>
    </tr>
    <tr>
        <td><code>tags</code></td>
        <td><code>$tags</code> => <code>string|array|mixed</code></td>
        <td><code>PrintTask</code></td>
        <td>Add tags to the task if your driver supports it</td>
    </tr>
    <tr>
        <td><code>tray</code></td>
        <td><code>$tray</code> => <code>string</code></td>
        <td><code>PrintTask</code></td>
        <td>Set a tray to print to if your driver and printer support it.</td>
    </tr>
    <tr>
        <td><code>copies</code></td>
        <td><code>$copies</code> => <code>int</code></td>
        <td><code>PrintTask</code></td>
        <td>Set the amount of copies to print</td>
    </tr>
    <tr>
        <td><code>range</code></td>
        <td>
            <code>$start</code> => <code>int|string</code>
            <br><code>$end</code> => <code>null|int</code>
        </td>
        <td><code>PrintTask</code></td>
        <td>Set the page range to print. Omit <code>$end</code> to start at a page and continue to end.</td>
    </tr>
    <tr>
        <td><code>option</code></td>
        <td>
            <code>$key</code> => <code>string</code>
            <br><code>$value</code> => <code>mixed</code>
        </td>
        <td><code>PrintTask</code></td>
        <td>Set an option for the print task that your driver supports</td>
    </tr>
    <tr>
        <td><code>send</code></td>
        <td></td>
        <td><code>PrintJob</code></td>
        <td>Send the print task to your print server. If successful, it will return a <code>PrintJob</code> instance.</td>
    </tr>
</x-table>
