<#
.SYNOPSIS
  Basic hardening: installs the "Two Factor" plugin and disables the theme/plugin code editor
  in the WordPress dashboard. Safe to re-run.

  After running, each admin turns on 2FA in their own profile (Users -> Profile -> Two-Factor Options).
  That step needs the person's own phone, so it is not done by this script.
#>
$ErrorActionPreference = 'Stop'

$wp = Join-Path $PSScriptRoot 'wp.ps1'

& $wp plugin install two-factor --activate
& $wp config set DISALLOW_FILE_EDIT true --raw     # no code editing from the dashboard
& $wp option update users_can_register 0           # nobody can sign up as a user by themselves

"== installed plugins"
& $wp plugin list '--fields=name,status,version' --format=table
