CANHEIT-2013
============

CANHEIT - The Canadian Higher-Ed IT conference - is being held in Ottawa this year and is being hosted by the University of Ottawa and Carleton University.

Its website's code is hosted here, and if you'd like to propose a change, fork the project, make a change and send us a pull request. We'll review your contribution and put it live if all's well.

Guiding principles
==================

The site was built with the following goals in mind:

1. **Responsive**: the site has to continue being responsive to different screen sizes, at all stages of the pre-conferences milestones, during the conference, and afterwards.
2. **The home page has to reflect today's top message**: at all stages, the home page is a reflection of the top messages for that time slot.
3. **The site and app are matched up**. When viewing the site on your mobile phone, you can switch to the app (powered by [guidebook.com](http://guidebook.com), [iOS here](https://itunes.apple.com/us/app/canheit-2013/id595230973?mt=8), [Android over here](https://play.google.com/store/apps/details?id=com.guidebook.apps.CANHEIT2013.android)) and you'll be taken in the same(ish) spot in the app, when possible (works in iOS versions, Android: we haven't figured out deep-linking yet).
4. **Make the experience of the sub-pages really great**. Purposeful, task-driven, responsive to different screen sizes, and contextual (we hold the user by the hand, repeat from other pages as necessary and point elsewhere for more info, not assume that they have pogosticked all pages of the site to get the whole picture).

Requirements
============

For collaborating on the CANHEIT 2013 website, you'll need the following packages installed on your workstation.

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