<#
.SYNOPSIS
  Imports assets/images/products/<sku>.webp into the media library and sets each one
  as the featured image of the product with that SKU. Products that already have an image are skipped.
#>
$ErrorActionPreference = 'Stop'

$wp = Join-Path $PSScriptRoot 'wp.ps1'
$root = Split-Path -Parent $PSScriptRoot
$products = Get-Content (Join-Path $PSScriptRoot 'seed\products.json') -Raw -Encoding UTF8 | ConvertFrom-Json

foreach ($p in $products) {
    $id = & $wp post list --post_type=product --meta_key=_sku "--meta_value=$($p.sku)" --format=ids
    if (-not $id) { "missing product $($p.sku)"; continue }

    if (& $wp post meta get $id _thumbnail_id) { "skip   $($p.sku)"; continue }

    $file = Join-Path $root "assets\images\products\$($p.sku).webp"
    & $wp media import $file "--post_id=$id" --featured_image "--title=$($p.name)" "--alt=$($p.name)" --porcelain | Out-Null
    "image  $($p.sku) -> product $id"
}
