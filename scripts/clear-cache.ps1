<#
.SYNOPSIS
  Empties the page cache of WP Super Cache. Run it after changing theme or plugin code,
  otherwise visitors keep seeing the old cached pages.
#>
$root = Split-Path -Parent $PSScriptRoot
$cache = Join-Path $root 'sites\app\public\wp-content\cache\supercache'
if (Test-Path $cache) {
    Get-ChildItem $cache -Force | Remove-Item -Recurse -Force
    'Page cache cleared.'
} else {
    'No page cache found.'
}
