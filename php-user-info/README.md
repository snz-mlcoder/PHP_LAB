# PHP User Info

A simple PHP project that detects user information such as IP address,
device type, operating system, and browser.

## Features
- IP address detection
- Browser detection
- OS detection
- Device type detection
- Location detection (Country/Region/City + Latitude/Longitude) via IP geolocation API


## Requirements
- PHP 7+
- Apache (XAMPP, WAMP, MAMP)
- Internet access (required for location lookup)


## Setup
1. Place the folder inside `htdocs`
2. Start Apache
3. Visit:
   http://localhost/php-user-info

## Notes
- Localhost will show limited IP/location data (often `127.0.0.1` or `::1`)
- IP-based location is approximate (not GPS)
- Upload to real hosting for more accurate results
