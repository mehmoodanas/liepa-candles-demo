<#
.SYNOPSIS
  Runs Lighthouse (mobile) on three store pages and saves the results.

.EXAMPLE
  .\scripts\measure-lighthouse.ps1 -Label before
  Writes docs/performance/before-*.json and prints a score table.
  Lighthouse is installed in tools/lighthouse. The site must be running in Local.
#>
param(
    [Parameter(Mandatory)] [string] $Label
)
$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$lighthouse = Join-Path $root 'tools\lighthouse\node_modules\.bin\lighthouse.cmd'
$out = Join-Path $root 'docs\performance'
New-Item -ItemType Directory -Force $out | Out-Null

$base = 'http://liepa-candles.local'
$pages = [ordered]@{
    home    = "$base/"
    shop    = "$base/shop/"
    product = "$base/product/linden-blossom-soy-candle/"
}

$env:CHROME_PATH = 'C:\Program Files\Google\Chrome\Application\chrome.exe'
$rows = foreach ($name in $pages.Keys) {
    $file = Join-Path $out "$Label-$name.json"
    & $lighthouse $pages[$name] --output=json "--output-path=$file" --quiet `
        '--chrome-flags=--headless=new --no-sandbox' --only-categories=performance,accessibility,best-practices,seo | Out-Null

    $r = Get-Content $file -Raw | ConvertFrom-Json
    [pscustomobject]@{
        Page        = $name
        Performance = [int]($r.categories.performance.score * 100)
        Access      = [int]($r.categories.accessibility.score * 100)
        BestPract   = [int]($r.categories.'best-practices'.score * 100)
        SEO         = [int]($r.categories.seo.score * 100)
        LCP_s       = [math]::Round($r.audits.'largest-contentful-paint'.numericValue / 1000, 2)
        TBT_ms      = [int]$r.audits.'total-blocking-time'.numericValue
        CLS         = [math]::Round($r.audits.'cumulative-layout-shift'.numericValue, 3)
        KB          = [int]($r.audits.'total-byte-weight'.numericValue / 1024)
    }
}
$rows | Format-Table -AutoSize
$rows | ConvertTo-Json | Set-Content (Join-Path $out "$Label-summary.json") -Encoding UTF8
