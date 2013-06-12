CANHEIT-2013
============

CANHEIT - The Canadian Higher-Ed IT conference - was held in Ottawa in 2013 and was hosted by the University of Ottawa and Carleton University.

How the site was built:
======================

* **Guidebook.com for conference data**: The dynamic content was pulled from guidebook.com's back-end. That included the program, custom lists like hotels, restaurants, travel options, contact lists, and the like. Guidebook is a mobile app with a back-end CMS and had two options, a read-only API and a SQLite snapshot with a .json manifest. Both options were used: early on the API was used, then the SQLite snapshot download was used. Conference organizers were able to push updates throughout the conference through guidebook.com and the website would get updated.
* **Static pages**: A dozen or so static pages were built for non-guidebook information. They were added, updated, removed (with redirects) over the months leading to the conference, and during the conference itself. This allowed us to prepare versions of the site in advance: registration up, registration removed, pre-conference, during-conference, post-conference. See the tags for the stages of the site's evolution.
* A **responsive design**, powered by [sass/susy](http://susy.oddbird.net).
* A **development environment**, powered by [Vagrant](http://www.vagrantup.com). Drafts of the site were served by URLs with version numbers (1, 2, 3, ..., 24)

Guiding principles
==================

The site was built with the following design goals in mind:

1. **Responsive**: the site has to continue being responsive to different screen sizes, at all stages of the pre-conferences milestones, during the conference, and afterwards.
2. **The home page has to reflect today's top message**: at all stages, the home page is a reflection of the top messages for that time slot. We remove or re-word something if it's outdated, updating the site several times during the progression of conference.
3. **The site and app are matched up**. When viewing the site on your mobile phone, you can switch to the app (powered by [guidebook.com](http://guidebook.com), [iOS here](https://itunes.apple.com/us/app/canheit-2013/id595230973?mt=8), [Android over here](https://play.google.com/store/apps/details?id=com.guidebook.apps.CANHEIT2013.android)) and you'll be taken in the same(ish) spot in the app, when possible (works in iOS versions, Android: we haven't figured out deep-linking yet).
4. **Make the experience of the sub-pages really great**. Purposeful, task-driven, responsive to different screen sizes, and contextual (we hold the user by the hand, repeat from other pages as necessary and point elsewhere for more info, not assume that they have pogosticked all pages of the site to get the whole picture).

Requirements
============

*Assuming here that you're running Mac OS, but if you'd like to contribute a patch that makes it work on Windows, super!*

* [Ruby](http://www.ruby-lang.org/en/)
* [RubyGems](http://rubygems.org)
* [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* [Vagrant](http://www.vagrantup.com)
* [Susy](http://susy.oddbird.net)

Installation
============

Once you installed the requirements, find a spot on your computer where you'll be working from to edit the CANHEIT website and run the following in your terminal:

Clone the project:

    git clone https://github.com/CANHEIT/CANHEIT-2013.git
    
Fetch the necessary sub-modules

    git submodule init
    git submodule update

Start up the local dev server (Vagrant)

    vagrant up
    
The CANHEIT website should now run at [http://localhost:8080/](http://localhost:8080/)

Make sure ``susy`` and ``compass`` are installed

    gem install susy compass

Start the CSS pre-processor that comes with Susy

    compass watch
    
If you make changes to [sass/screen.sass](sass/screen.sass), compass (end Susy) should now update [css/screen.css](css/screen.css)