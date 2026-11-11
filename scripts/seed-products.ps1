<#
.SYNOPSIS
  Creates the demo candle products from scripts/seed/products.json.
  Safe to re-run: products whose SKU already exists are skipped.
#>
$ErrorActionPreference = 'Stop'

$wp = Join-Path $PSScriptRoot 'wp.ps1'
$products = Get-Content (Join-Path $PSScriptRoot 'seed\products.json') -Raw -Encoding UTF8 | ConvertFrom-Json

function Get-CategoryId([string] $name) {
    $terms = & $wp term list product_cat '--fields=term_id,name' --format=json | ConvertFrom-Json
    ($terms | Where-Object { $_.name -eq $name } | Select-Object -First 1).term_id
}

foreach ($category in ($products.category | Select-Object -Unique)) {
    if (-not (Get-CategoryId $category)) { & $wp term create product_cat $category | Out-Null }
}

foreach ($p in $products) {
    $exists = & $wp post list --post_type=product --meta_key=_sku "--meta_value=$($p.sku)" --format=ids
    if ($exists) { "skip   $($p.sku)"; continue }

    $short = "Hand-poured soy candle, about 40 hours burn time. LV: $($p.name_lv)."
    $long = "Fictional demo product for the Liepa Candles portfolio store. $($p.name_lv)."

    $id = & $wp wc product create --user=1 --porcelain "--name=$($p.name)" --type=simple `
        "--regular_price=$($p.price)" "--sku=$($p.sku)" --manage_stock=true "--stock_quantity=$($p.stock)" `
        "--short_description=$short" "--description=$long" --status=publish

    # Pass the category NAME: WP-CLI would treat a numeric ID as a new term called "16".
    & $wp post term set $id product_cat $p.category | Out-Null
    "create $($p.sku) -> product $id"
}
