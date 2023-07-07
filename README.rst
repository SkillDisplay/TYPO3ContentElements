=======================================================================
Display Skills & SkillSets from SkillDisplay as TYPO3 Content Elements
========================================================================

This TYPO3 extension allows to show SkillDisplay Skills & SkillSets in TYPO3 installations.

Scenarios
* Show job requirements based on official certification and expert definitions on your website

* Visualize the curriculum of a training your agency provides

* Display the requirements for official certification (on official pages or your intranet websites)

You can list the skills as content elements one by one, or use SkillSets for grouping purposes (see scenarios above).
All public SkillSets available on the SkillDisplay platform (e.g.: Certification curriculums, etc.) are automatically available for use in this extension.
To use custom SkillSets you either need a business account on the official SkillDisplay platform, or host the Open Source SkillDisplay Core Extension on your own TYPO3 instance.

Right now this extension provides the following features:

* Content element to render one or more skills.

* Content element to render one skill set.

* DataProcessor to fetch skills as entities via API.

* DataProcessor to fetch skill sets as entities via API.

* ViewHelper to generate verification button.

* ViewHelper to generate verification URL.

* ViewHelper to group skills by domain tag

Installation
============

Install the extension via Extension Manager or composer.

Add static TypoScript once installed and activated via Extension Manager.
The TypoScript contains the rendering definition for provided content elements.

Next Step: Copy Templates or adjust template paths via TypoScript.
In order to allow TYPO3 to find the Fluid templates for content elements,
either add the path `EXT:skilldisplay_content/Resources/Private/Templates/ContentElements/` in TypoScript, e.g.::

   lib.contentElement {
       partialRootPaths {
           50 = EXT:skilldisplay_content/Resources/Private/Partials/ContentElements/
       }
       templateRootPaths {
           50 = EXT:skilldisplay_content/Resources/Private/Templates/ContentElements/
       }
   }

Ensure the path has a lower number then your own paths and doesn't overwrite any existing number.

Or copy the files from `/Resources/Private/Templates/ContentElements/` to your existing template folder.

Configuration
=============

It is possible to cache the API results for Skill Sets with this TypoScript constant::

  SkilldisplayContent.skillSet.cache = 1

You may include default CSS for the templates in your TypoScript::

  page.includeCSS.skilldisplay = EXT:skilldisplay_content/Resources/Public/Css/Styles.css


Site configuration is extended, where options like API Key can be provided.

Also TypoScript and TSconfig is added to provide wizard entries for new content elements, as well as rendering.
Both can be adjusted via custom TypoScript and TSconfig.

Usage
=====

Add content elements to the pages and insert the ID of skills as comma separated list, or select one of the available skillsets. (The list includes all public SkillSets on the official SkillDisplay platform. Using custom SkillDisplay instances based on the Open Source extension is possible, check the skilldisplay/php-api package for more information)
