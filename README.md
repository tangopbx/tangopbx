# TangoPBX Branding Module for FreePBX
---
## What is the TangoPBX branding module?

The TangoPBX branding module is a free, open source module for FreePBX®, published by TangoPBX LLC, that sets up the TangoPBX visuals and operating environment. Once installed, your system will have the branding elements associated with TangoPBX and be configured to use the ClearlyIP module Repo for PBX module maintenance.

## Module License

The TangoPBX module is published under GNU AGPL 3.0

## Module Requirements

This module has been tested on FPBX versions 16 and 17, but will likely work on earlier versions 13+.

## How to install the TangoPBX module

There are two supported ways to install the TangoPBX branding module. The URL to download the module is:

    https://mirror.clearlyip.com/modules/3924858000001140003/tangopbx/tangopbx-17.0.1.tgz

**Install Method 1** - Use the GUI

* In the FPBX GUI, browse to Admin -> Module Admin and click the button “Upload Modules”
* In the page that follows, paste the entire URL from above into the Download Remote Module field and click the “Download” button
* The download will usually proceed quickly. When complete, follow the link to module administration and locate the TangoPBX module in the list that follows. It’s near the bottom
* Click the down-arrow to expand the TangoPBX section, click the install button, and then click the process button at the bottom of the page. Follow the remaining prompts.
* The final step is to hit the red “Apply Config” button to complete the installation.

**Install Method 2** - The command line

After logging in as root, run the following commands, substituting the URL from above:

    fwconsole ma downloadinstall <<module url>>
    fwconsole r

## How do I see the TangoPBX module features?

Just install the module, apply config and browse to the dashboard home page. You will see the TangoPBX branding immediately after install
