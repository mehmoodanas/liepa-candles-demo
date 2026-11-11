<#
.SYNOPSIS
  Sets up English + Latvian with Polylang: languages, Latvian product copies, Latvian home page.
  Needs the Polylang plugin installed and active:  scripts\wp.ps1 plugin install polylang --activate
  Safe to re-run.
#>
$ErrorActionPreference = 'Stop'

$wp = Join-Path $PSScriptRoot 'wp.ps1'
foreach ($name in 'setup-languages', 'setup-translations') {
    "== $name"
    & $wp eval-file (Join-Path $PSScriptRoot "php\$name.php")
}

"== rewrite rules"
& $wp rewrite flush   # makes the /lv/ product and page URLs resolve
