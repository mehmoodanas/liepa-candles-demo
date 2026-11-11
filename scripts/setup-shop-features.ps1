<#
.SYNOPSIS
  Applies the shop features in scripts/php: Latvia shipping zone, WELCOME10 coupon, size variants.
  Safe to re-run.
#>
$ErrorActionPreference = 'Stop'

$wp = Join-Path $PSScriptRoot 'wp.ps1'
foreach ($name in 'setup-shipping', 'setup-coupon', 'setup-variable-product') {
    "== $name"
    & $wp eval-file (Join-Path $PSScriptRoot "php\$name.php")
}
