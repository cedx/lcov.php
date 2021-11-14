#!/usr/bin/env pwsh
Set-StrictMode -Version Latest
Set-Location (Split-Path $PSScriptRoot)

$phpdoc = $IsWindows ? 'php "C:/Program Files/PHP/share/phpDocumentor.phar"' : "phpdoc";
Invoke-Expression "$phpdoc --config=etc/phpdoc.xml"

if (-not (Test-Path docs/images)) { New-Item docs/images -ItemType Directory | Out-Null }
Copy-Item www/favicon.ico docs/images
