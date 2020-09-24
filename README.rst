=========================================
TYPO3 Extension to integrate SkillDisplay
=========================================

This TYPO3 extension integrates SkillDisplay into TYPO3 installations.

Right now it provides the following features:

* Content element to render one or more skills.

* Content element to render one skill set.

* DataProcessor to fetch skills as entities via API.

* DataProcessor to fetch skill sets as entities via API.

* ViewHelper to generate verification button.

* ViewHelper to generate verification URL.

Installation
============

The extension can be installed by downloading recent version from GitHub and adding
it inside `typo3conf/` folder.

Add static TypoScript once installed and activated via Extension Manager.
The TypoScript contains the rendering definition for provided content elements.

Next Step: Copy Templates or adjust template paths via TypoScript.
In order to allow TYPO3 to find the Fluid templates for content elements,
either add the path `EXT:skilldisplay/Resources/Private/Templates/ContentElements/` in TypoScript, e.g.::

   lib.contentElement {
       templateRootPaths {
           50 = EXT:skilldisplay/Resources/Private/Templates/ContentElements/
       }
   }

Ensure the path has a lower number then your own paths and doesn't overwrite any existing number.

Or copy the files from `/Resources/Private/Templates/ContentElements/` to your existing template folder.

Configuration
=============

Site configuration is extended, where options like API Key can be provided.

Also TypoScript and TSconfig is added to provide wizard entries for new content elements, as well as rendering.
Both can be adjusted via custom TypoScript and TSconfig.

Usage
=====

Add content elements to the pages and insert the ID of skills as comma separated
list, or the ID of a single skill set.
