<#
.SYNOPSIS
  Backs up the store into backups/<date-time>/: a database dump and a zip of the uploaded files.
  The code in src/ is backed up by Git, so it is not copied here.

.NOTES
  Copy the backup folder to a second place (USB drive or another disk) too: a backup that sits
  on the same disk as the site does not survive a disk failure.
  To restore: import database.sql with Local's "Database" tab (Adminer) and unzip uploads.zip into wp-content.
#>
$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$site = Join-Path $root 'sites\app\public'
$target = Join-Path $root ('backups\' + (Get-Date -Format 'yyyy-MM-dd_HH-mm'))
New-Item -ItemType Directory -Force $target | Out-Null

# Database connection details come from the site's own wp-config.php (Local's local-only account).
$config = Get-Content (Join-Path $site 'wp-config.php') -Raw
function Get-ConfigValue([string] $name) {
    [regex]::Match($config, "define\(\s*'$name'\s*,\s*'([^']*)'").Groups[1].Value
}
$port = Get-NetTCPConnection -State Listen |
    Where-Object { (Get-Process -Id $_.OwningProcess -ErrorAction SilentlyContinue).Name -eq 'mysqld' } |
    Select-Object -First 1 -ExpandProperty LocalPort
if (-not $port) { throw 'MySQL is not running. Start the site in Local first.' }

$mysqldump = Get-ChildItem (Join-Path $root 'tools\LocalWP-data\lightning-services') -Recurse -Filter mysqldump.exe |
    Where-Object { $_.FullName -match '\\mysql-\d' } |   # the site uses MySQL, not the bundled MariaDB
    Select-Object -First 1 -ExpandProperty FullName

$dump = Join-Path $target 'database.sql'
$pluginDir = Join-Path (Split-Path -Parent (Split-Path -Parent $mysqldump)) 'lib\plugin'   # needed for password login
$env:MYSQL_PWD = Get-ConfigValue 'DB_PASSWORD'                                             # keeps the password off the command line
& $mysqldump --host=127.0.0.1 "--port=$port" "--user=$(Get-ConfigValue 'DB_USER')" "--plugin-dir=$pluginDir" `
    --ssl-mode=DISABLED --get-server-public-key `
    --single-transaction --no-tablespaces "--result-file=$dump" (Get-ConfigValue 'DB_NAME')
$exitCode = $LASTEXITCODE
Remove-Item Env:\MYSQL_PWD
if ($exitCode -ne 0) { throw 'mysqldump failed.' }

Compress-Archive -Path (Join-Path $site 'wp-content\uploads') -DestinationPath (Join-Path $target 'uploads.zip')

Get-ChildItem $target | ForEach-Object { "{0}: {1} KB" -f $_.Name, [int]($_.Length / 1KB) }
"Backup saved in $target"
