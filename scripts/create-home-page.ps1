<#
.SYNOPSIS
  Publishes src/content/home.html as the "Home" page and makes it the front page.
  Re-run it after editing home.html to update the page.
#>
$ErrorActionPreference = 'Stop'

$wp = Join-Path $PSScriptRoot 'wp.ps1'
$content = Join-Path (Split-Path -Parent $PSScriptRoot) 'src\content\home.html'

$id = & $wp post list --post_type=page --name=home --format=ids
if ($id) {
    & $wp post update $id $content | Out-Null
    "updated page $id"
} else {
    $id = & $wp post create $content --post_type=page --post_status=publish --post_title=Home --post_name=home --porcelain
    "created page $id"
}

& $wp option update show_on_front page | Out-Null
& $wp option update page_on_front $id | Out-Null
"front page set to $id"
