<#
.SYNOPSIS
  Runs WP-CLI against the local Liepa site (the one managed by LocalWP on drive E).

.EXAMPLE
  .\scripts\wp.ps1 plugin list
  The site must be running in Local first.
#>
$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$data = Join-Path $root 'tools\LocalWP-data'

$php = Get-ChildItem (Join-Path $data 'lightning-services') -Recurse -Filter php.exe |
    Where-Object { $_.FullName -match 'win64' } | Select-Object -First 1 -ExpandProperty FullName
$ini = Get-ChildItem (Join-Path $data 'run') -Recurse -Filter php.ini |
    Select-Object -First 1 -ExpandProperty FullName
$phar = Join-Path $root 'tools\LocalWP\resources\extraResources\bin\wp-cli\wp-cli.phar'
$site = Join-Path $root 'sites\app\public'

# Local gives each site its own MySQL port; read it from the running mysqld process.
$port = Get-NetTCPConnection -State Listen |
    Where-Object { (Get-Process -Id $_.OwningProcess -ErrorAction SilentlyContinue).Name -eq 'mysqld' } |
    Select-Object -First 1 -ExpandProperty LocalPort
if (-not $port) { throw 'MySQL is not running. Start the site in Local first.' }

# stderr is merged into the output as plain text so WP-CLI error messages are shown in full.
$ErrorActionPreference = 'Continue'
& $php -c $ini -d display_errors=0 -d memory_limit=512M -d "mysqli.default_port=$port" $phar "--path=$site" @args 2>&1 |
    ForEach-Object { $_.ToString() } |
    Where-Object { $_ -notmatch 'imagick' }
